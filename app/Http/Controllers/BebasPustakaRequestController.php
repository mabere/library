<?php

namespace App\Http\Controllers;

use App\Models\User;
use DomainException;
use App\Helpers\AuditHelper;
use Illuminate\Http\Request;
use App\Jobs\SendWhatsappJob;
use App\Enums\BebasPustakaStatus;
use App\Models\BebasPustakaRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Notifications\StatusChangedNotification;
use App\Services\BebasPustaka\StafBebasPustakaService;
use App\Services\BebasPustaka\ApproveBebasPustakaService;
use App\Services\BebasPustaka\MahasiswaBebasPustakaService;

class BebasPustakaRequestController extends Controller
{

    // Metode Mahasiswa
    public function indexMahasiswa(MahasiswaBebasPustakaService $service) {
        $user = auth()->user();

        $requests = BebasPustakaRequest::with(['letter', 'histories'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // default boleh ajukan
        $canSubmit = true;
        $statusInfo = null;

        try {
            $service->ensureCanSubmit($user);
        } catch (DomainException $e) {
            $canSubmit = false;
            $statusInfo = $e->getMessage();
        }

        return view('bebas_pustaka.mahasiswa.index', compact(
            'requests',
            'canSubmit',
            'statusInfo'
        ));
    }

    public function create(MahasiswaBebasPustakaService $service) {
        try {
            $service->ensureCanSubmit(auth()->user());

            $student = auth()->user()->student;

            return view('bebas_pustaka.mahasiswa.create', compact('student'));
        } catch (DomainException $e) {
            return redirect()
                ->route('mahasiswa.bebas_pustaka.index')
                ->with('error', $e->getMessage());
        }
    }


    public function store(Request $request, MahasiswaBebasPustakaService $service) {
        try {
            $service->ensureCanSubmit($request->user());

            $req = $service->submit($request->user());

            AuditHelper::logRequestHistory(
                'bebas_pustaka',
                $req->id,
                null,
                BebasPustakaStatus::DIAJUKAN->value,
                'Pengajuan dibuat oleh mahasiswa.',
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'bebas_pustaka.create',
                $req,
                [],
                $request
            );

            $this->notifyStakeholders($req, 'Pengajuan dibuat.');

            // Invalidate Kaprodi dashboard cache
            Cache::forgetPattern('dashboard.kaprodi.*');

            return redirect()
                ->route('mahasiswa.bebas_pustaka.index')
                ->with('success', 'Pengajuan bebas pustaka berhasil dikirim.');
        } catch (DomainException $e) {
            return redirect()
                ->route('mahasiswa.bebas_pustaka.index')
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return redirect()
                ->route('mahasiswa.bebas_pustaka.index')
                ->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    // Metode Staff
    public function indexStaf()
    {
        $requests = BebasPustakaRequest::with(['user', 'histories'])
            ->orderByDesc('created_at')
            ->get();

        return view('bebas_pustaka.staf.index', compact('requests'));
    }


    public function verifyStaf(Request $request, int $id, StafBebasPustakaService $service)
    {
        $data = $request->validate([
            'has_fine'  => 'required|boolean',
            'fine_note' => 'nullable|string',
        ]);

        $req = BebasPustakaRequest::findOrFail($id);

        try {
            $fromStatus = $req->status;

            $req = $service->verify(
                $req,
                $request->user()->id,
                (bool) $data['has_fine'],
                $data['fine_note'] ?? null
            );

            AuditHelper::logRequestHistory(
                'bebas_pustaka',
                $req->id,
                $fromStatus,
                $req->status,
                $req->rejection_note ?? 'Verifikasi staf.',
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'bebas_pustaka.status_changed',
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


    public function rejectStaf(Request $request, int $id, StafBebasPustakaService $service)
    {
        $data = $request->validate([
            'rejection_note' => 'required|string',
        ]);

        $req = BebasPustakaRequest::findOrFail($id);

        try {
            $fromStatus = $req->status;

            $req = $service->reject(
                $req,
                $request->user()->id,
                $data['rejection_note']
            );

            AuditHelper::logRequestHistory(
                'bebas_pustaka',
                $req->id,
                $fromStatus,
                $req->status,
                $data['rejection_note'],
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'bebas_pustaka.status_changed',
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
        $requests = BebasPustakaRequest::with(['user', 'verifiedBy', 'histories'])
            ->where('status', 'diverifikasi_staf')
            ->orderByDesc('verified_at')
            ->get();

        return view('bebas_pustaka.kepala.index', compact('requests'));
    }


    public function approveKepala(Request $request, int $id, ApproveBebasPustakaService $service) {
        $req = BebasPustakaRequest::with('verifiedBy')->findOrFail($id);

        try {
            $service->approve($req, $request->user()->id);

            AuditHelper::logRequestHistory(
                'bebas_pustaka',
                $req->id,
                BebasPustakaStatus::DIVERIFIKASI_STAF->value,
                BebasPustakaStatus::DISETUJUI_KEPALA->value,
                'Disetujui kepala perpustakaan.',
                $request->user()->id
            );

            AuditHelper::logActivity(
                $request->user(),
                'bebas_pustaka.status_changed',
                $req,
                ['to' => BebasPustakaStatus::DISETUJUI_KEPALA->value],
                $request
            );

            $this->notifyStakeholders($req, 'Disetujui kepala perpustakaan.');

            // Invalidate Kaprodi dashboard cache
            Cache::forgetPattern('dashboard.kaprodi.*');

            return back()->with('success', 'Surat bebas pustaka berhasil disetujui.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses persetujuan.');
        }
    }

    // Metode Kaprodi
    public function indexKaprodi()
    {
        $departmentIds = auth()->user()->departmentIdsForRole('kaprodi');

        $requests = BebasPustakaRequest::with(['user', 'letter', 'student', 'histories'])
            ->whereHas('student', function ($q) use ($departmentIds) {
                $q->whereIn('department_id', $departmentIds);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('bebas_pustaka.kaprodi.index', compact('requests'));
    }

    public function show(Request $request, int $id)
    {
        $req = BebasPustakaRequest::with([
            'user',
            'student.department',
            'letter',
            'verifiedBy',
            'approvedBy',
            'histories.user',
        ])->findOrFail($id);

        $user = $request->user();

        if ($user->hasRole('admin') || $user->hasAnyRole(['staf', 'kepala'])) {
            return view('bebas_pustaka.show', compact('req'));
        }

        if ($user->hasRole('kaprodi')) {
            $departmentIds = $user->departmentIdsForRole('kaprodi');
            if ($req->student && in_array($req->student->department_id, $departmentIds, true)) {
                return view('bebas_pustaka.show', compact('req'));
            }
        }

        if ($user->hasRole('mahasiswa') && (int) $req->user_id === (int) $user->id) {
            return view('bebas_pustaka.show', compact('req'));
        }

        abort(403);
    }

    public function downloadLetter(Request $request, int $id)
    {
        $req = BebasPustakaRequest::with('letter')->findOrFail($id);

        Gate::authorize('download', $req);

        if (!$req->letter || !$req->letter->file_path) {
            abort(404);
        }

        AuditHelper::logActivity($request->user(), 'bebas_pustaka.download', $req, [
            'letter_id' => $req->letter_id,
        ], $request);

        return Storage::disk('public')->download($req->letter->file_path);
    }

    private function notifyStakeholders(BebasPustakaRequest $req, string $note): void
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
            $user->notify(new StatusChangedNotification($req, 'bebas_pustaka', $note));
            SendWhatsappJob::dispatch($user, 'bebas_pustaka', $req, $note);
        }
    }
}
