<?php

namespace App\Http\Controllers;

use App\Models\User;
use DomainException;
use App\Enums\SkripsiStatus;
use App\Helpers\AuditHelper;
use Illuminate\Http\Request;
use App\Jobs\SendWhatsappJob;
use App\Models\SkripsiRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\Skripsi\StafSkripsiService;
use App\Services\Skripsi\ApproveSkripsiService;
use App\Notifications\StatusChangedNotification;
use App\Services\Skripsi\MahasiswaSkripsiService;

class SkripsiRequestController extends Controller
{
    // Metode Mahasiswa
    public function indexMahasiswa(MahasiswaSkripsiService $service)
    {
        $this->authorize('viewAny', SkripsiRequest::class);
        $user = auth()->user();

        $requests = SkripsiRequest::with(['letter', 'histories'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $canSubmit = true;
        $statusInfo = null;

        try {
            $service->ensureCanSubmit($user);
        } catch (DomainException $e) {
            $canSubmit = false;
            $statusInfo = $e->getMessage();
        }

        return view('skripsi.mahasiswa.index', compact(
            'requests',
            'canSubmit',
            'statusInfo'
        ));
    }


    public function create(MahasiswaSkripsiService $service)
    {
        $this->authorize('create', SkripsiRequest::class);

        try {
            $service->ensureCanSubmit(auth()->user());

            $student = auth()->user()->student;

            return view('skripsi.mahasiswa.create', compact('student'));
        } catch (DomainException $e) {
            return redirect()
                ->route('mahasiswa.skripsi.index')
                ->with('error', $e->getMessage());
        }
    }

    public function store(Request $request, MahasiswaSkripsiService $service)
    {
        $this->authorize('create', SkripsiRequest::class);

        $data = $request->validate([
            'judul_skripsi' => ['nullable', 'string', 'max:255'],
            'tahun_lulus' => ['nullable', 'integer', 'digits:4'],
        ]);


        try {
            $service->ensureCanSubmit($request->user());

            $req = $service->submit($request->user(), $data);

            AuditHelper::logRequestHistory(
                'skripsi',
                $req->id,
                null,
                SkripsiStatus::DIAJUKAN->value,
                'Pengajuan penyerahan skripsi dibuat oleh mahasiswa.',
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'skripsi.create',
                $req,
                [],
                $request
            );

            $this->notifyStakeholders($req, 'Pengajuan dibuat.');
            // Invalidate Kaprodi dashboard cache
            Cache::forgetPattern('dashboard.kaprodi.*');

            return redirect()
                ->route('mahasiswa.skripsi.index')
                ->with('success', 'Pengajuan penyerahan skripsi berhasil dikirim.');
        } catch (DomainException $e) {
            return redirect()
                ->route('mahasiswa.skripsi.index')
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return redirect()
                ->route('mahasiswa.skripsi.index')
                ->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    // Metode Staf
    public function indexStaf()
    {
        $this->authorize('viewAny', SkripsiRequest::class);
        $requests = SkripsiRequest::with(['user', 'histories'])
            ->orderByDesc('created_at')
            ->get();

        return view('skripsi.staf.index', compact('requests'));
    }

    public function verifyStaf(Request $request, int $id, StafSkripsiService $service)
    {
        $this->authorize('verify', SkripsiRequest::class);
        $req = SkripsiRequest::findOrFail($id);

        try {
            $fromStatus = $req->status;

            $req = $service->verify(
                $req,
                $request->user()->id,
                true,
                null
            );

            AuditHelper::logRequestHistory(
                'skripsi',
                $req->id,
                $fromStatus,
                $req->status,
                $req->rejection_note ?? 'Verifikasi staf.',
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'skripsi.status_changed',
                $req,
                ['from' => $fromStatus, 'to' => $req->status],
                $request
            );

            $this->notifyStakeholders($req, $req->rejection_note ?? 'Verifikasi staf.');
            // Invalidate Kaprodi dashboard cache
            Cache::forgetPattern('dashboard.kaprodi.*');

            return back()->with('success', 'Verifikasi staf tersimpan.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }


    public function rejectStaf(Request $request, int $id, StafSkripsiService $service)
    {
        $this->authorize('verify', SkripsiRequest::class);
        $data = $request->validate([
            'rejection_note' => ['required', 'string'],
        ]);

        $req = SkripsiRequest::findOrFail($id);

        try {
            $fromStatus = $req->status;

            $req = $service->reject(
                $req,
                $request->user()->id,
                $data['rejection_note']
            );

            AuditHelper::logRequestHistory(
                'skripsi',
                $req->id,
                $fromStatus,
                $req->status,
                $data['rejection_note'],
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'skripsi.status_changed',
                $req,
                ['from' => $fromStatus, 'to' => $req->status],
                $request
            );

            $this->notifyStakeholders($req, $data['rejection_note']);
            // Invalidate Kaprodi dashboard cache
            Cache::forgetPattern('dashboard.kaprodi.*');

            return back()->with('success', 'Pengajuan ditolak oleh staf.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    // Metode Kepala UPT
    public function indexKepala()
    {
        $this->authorize('viewAny', SkripsiRequest::class);
        $requests = SkripsiRequest::with(['user', 'verifiedBy', 'histories'])
            ->where('status', SkripsiStatus::DIVERIFIKASI_STAF->value)
            ->orderByDesc('verified_at')
            ->get();

        return view('skripsi.kepala.index', compact('requests'));
    }

    public function approveKepala(Request $request, int $id, ApproveSkripsiService $service)
    {
        $this->authorize('approve', SkripsiRequest::class);

        $req = SkripsiRequest::with(['verifiedBy'])->findOrFail($id);

        try {
            $fromStatus = $req->status;

            $req = $service->approve(
                $req,
                $request->user()->id
            );

            AuditHelper::logRequestHistory(
                'skripsi',
                $req->id,
                $fromStatus,
                SkripsiStatus::DISETUJUI_KEPALA->value,
                'Disetujui kepala perpustakaan.',
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'skripsi.status_changed',
                $req,
                [
                    'from' => $fromStatus,
                    'to'   => SkripsiStatus::DISETUJUI_KEPALA->value,
                ],
                $request
            );

            $this->notifyStakeholders(
                $req,
                'Disetujui kepala perpustakaan.'
            );

            // Invalidate Kaprodi dashboard cache
            Cache::forgetPattern('dashboard.kaprodi.*');
            
            return back()->with(
                'success',
                'Surat penyerahan skripsi berhasil disetujui.'
            );
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    // Metode Kaprodi
    public function indexKaprodi()
    {
        $this->authorize('viewAny', SkripsiRequest::class);
        $departmentIds = auth()->user()->departmentIdsForRole('kaprodi');

        $requests = SkripsiRequest::with(['user', 'letter', 'student', 'histories'])
            ->whereHas('student', function ($q) use ($departmentIds) {
                $q->whereIn('department_id', $departmentIds);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('skripsi.kaprodi.index', compact('requests'));
    }

    public function show(Request $request, int $id)
    {
        $req = SkripsiRequest::with([
            'user',
            'student.department',
            'letter',
            'verifiedBy',
            'approvedBy',
            'histories.user',
        ])->findOrFail($id);

        $this->authorize('view', $req);

        $user = $request->user();

        if ($user->hasRole('admin') || $user->hasAnyRole(['staf', 'kepala'])) {
            return view('skripsi.show', compact('req'));
        }

        if ($user->hasRole('kaprodi')) {
            $departmentIds = $user->departmentIdsForRole('kaprodi');
            if ($req->student && in_array($req->student->department_id, $departmentIds, true)) {
                return view('skripsi.show', compact('req'));
            }
        }

        if ($user->hasRole('mahasiswa') && (int) $req->user_id === (int) $user->id) {
            return view('skripsi.show', compact('req'));
        }

        abort(403);
    }

    public function downloadLetter(Request $request, int $id)
    {
        $req = SkripsiRequest::with('letter')->findOrFail($id);

        $this->authorize('download', $req);

        if (!$req->letter || !$req->letter->file_path) {
            abort(404);
        }

        AuditHelper::logActivity($request->user(), 'skripsi.download', $req, [
            'letter_id' => $req->letter_id,
        ], $request);

        return Storage::disk('public')->download($req->letter->file_path);
    }

    private function notifyStakeholders(SkripsiRequest $req, string $note): void
    {
        $recipients = collect();

        if ($req->status === 'diajukan') {
            $recipients = User::whereHas('roles', function ($q) {
                $q->where('name', 'staf');
            })->get();
        }

        if ($req->status === 'diverifikasi_staf') {
            $recipients = User::whereHas('roles', function ($q) {
                $q->where('name', 'kepala');
            })->get();
        }

        if ($req->user) {
            $recipients->push($req->user);
        }

        $uniqueRecipients = $recipients->unique('id');

        foreach ($uniqueRecipients as $user) {
            $user->notify(new StatusChangedNotification($req, 'skripsi', $note));
            SendWhatsappJob::dispatch($user, 'skripsi', $req, $note);
        }
    }
}
