<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\BebasPustakaRequest;
use App\Models\SkripsiRequest;
use App\Models\RequestHistory;
use App\Models\Student;
use App\Models\Department;
use App\Helpers\QrHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;
use App\Helpers\LetterNumberHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\VerificationLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LetterController extends Controller
{
    public function index()
    {
        $letters = Letter::with('student')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('letters.index', compact('letters'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('letters.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $rules = [
            'letter_type' => 'required|in:bebas_pustaka,penyerahan_skripsi',
            'nim'         => 'required',
            'nama'        => 'required',
            'department_id' => 'required|exists:departments,id',
            'verified_by' => 'required',
        ];

        if ($request->letter_type === 'penyerahan_skripsi') {
            $rules['judul_skripsi'] = 'required';
            $rules['tahun_lulus']   = 'required|digits:4';
        }

        if ($request->letter_type === 'bebas_pustaka') {
            $rules['has_fine'] = 'required|in:0,1';
        }

        $request->validate($rules);

        if ($request->letter_type === 'bebas_pustaka' && $request->has_fine == 1) {
            return back()->with('error',
                'Mahasiswa masih memiliki tanggungan. Surat tidak dapat diterbitkan.'
            );
        }

        DB::beginTransaction();

        try {
            $department = \App\Models\Department::find($request->department_id);
            $student = Student::updateOrCreate(
                ['nim' => $request->nim],
                [
                    'nama'           => $request->nama,
                    'department_id'  => $department?->id,
                    'judul_skripsi'  => $request->judul_skripsi ?? '-',
                    'tahun_lulus'    => $request->tahun_lulus ?? null,
                ]
            );

            $title = LetterNumberHelper::getTitle($request->letter_type);
            $number = LetterNumberHelper::generate($request->letter_type);

            $letter = Letter::create([
                'student_id'   => $student->id,
                'letter_type'  => $request->letter_type,
                'letter_number'=> $number,
                'token'        => Str::uuid(),
                'has_fine'     => $request->letter_type === 'bebas_pustaka'
                                    ? (bool) $request->has_fine
                                    : false,
                'verified_by'  => $request->verified_by,
                'verified_at'  => now(),
            ]);

            $qrCode = QrHelper::generateBase64(
                url('/letter/verify/' . $letter->token)
            );

            $description = LetterNumberHelper::getDescription($request->letter_type);

            $pdf = PDF::loadView('letters.pdf', [
                'student'     => $student,
                'number'      => $number,
                'description' => $description,
                'verified_by' => $request->verified_by,
                'qrCode'      => $qrCode,
                'title'       => $title,
                'letter_type' => $request->letter_type,
            ]);

            $path = 'letters/surat-' . $student->nim . '-' . time() . '.pdf';
            Storage::disk('public')->put($path, $pdf->output());

            $letter->update(['file_path' => $path]);

            DB::commit();

            return redirect('/letters')->with('success', 'Surat berhasil dibuat.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }




    public function download($id)
    {
        $letter = Letter::findOrFail($id);
        return Storage::disk('public')->download($letter->file_path);
    }

    public function report()
    {
        return view('letters.report');
    }

    public function printReport(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);

        $letters = Letter::with('student')
            ->whereMonth('created_at', $request->bulan)
            ->whereYear('created_at', $request->tahun)
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = PDF::loadView('letters.report-pdf', [
            'letters' => $letters,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun
        ]);

        return $pdf->stream(
            'laporan-surat-'.$request->bulan.'-'.$request->tahun.'.pdf'
        );
    }

    public function exportReportCsv(Request $request): StreamedResponse
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $letters = Letter::with('student.department')
            ->whereMonth('created_at', $request->bulan)
            ->whereYear('created_at', $request->tahun)
            ->orderBy('created_at', 'asc')
            ->get();

        $filename = 'laporan-surat-'.$request->bulan.'-'.$request->tahun.'.csv';

        return response()->streamDownload(function () use ($letters) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Nomor Surat',
                'NIM',
                'Nama',
                'Program Studi',
                'Jenis Surat',
                'Status',
                'Tanggal',
            ]);

            foreach ($letters as $row) {
                fputcsv($handle, [
                    $row->letter_number,
                    $row->student?->nim,
                    $row->student?->nama,
                    $row->student?->department?->name,
                    $row->letter_type,
                    $row->status,
                    optional($row->created_at)->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function verifyForm()
    {
        return view('letters.verify-upload');
    }

    public function verifyScan()
    {
        return view('letters.verify-scan');
    }


    public function verifyUpload(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|max:2048'
        ]);

        $filename = uniqid().'.'.$request->file('qr_image')->getClientOriginalExtension();

        Storage::disk('app')->putFileAs(
            'qr-temp',
            $request->file('qr_image'),
            $filename
        );

        $fullPath = storage_path('app/qr-temp/' . $filename);

        if (!file_exists($fullPath)) {
            $this->logVerification(null, null, 'invalid', 'upload', $request);
            return back()->with('error', 'File QR tidak ditemukan.');
        }

        try {
            $qr = new \Zxing\QrReader($fullPath);
            $text = trim($qr->text());
        } catch (\Throwable $e) {
            unlink($fullPath);
            $this->logVerification(null, null, 'invalid', 'upload', $request);
            return back()->with('error', 'QR tidak dapat dibaca.');
        }

        unlink($fullPath);

        if (!$text) {
            $this->logVerification(null, null, 'invalid', 'upload', $request);
            return back()->with('error', 'QR tidak valid.');
        }

        $token = trim(basename($text));

        $letter = Letter::where('token', $token)->first();

        if (!$letter) {
            $this->logVerification(null, $token, 'not_found', 'upload', $request);
            return view('letters.verify', [
                'valid' => false,
                'error' => 'QR tidak terdaftar di sistem.'
            ]);
        }

        if ($letter->status === 'dibatalkan') {
            $this->logVerification($letter, $token, 'cancelled', 'upload', $request);
            return view('letters.verify', [
                'valid' => false,
                'error' => 'Surat ini telah dibatalkan.'
            ]);
        }

        $this->logVerification($letter, $token, 'valid', 'upload', $request);
        return view('letters.verify', [
            'valid'  => true,
            'letter' => $letter,
            'history' => $this->getRequestHistoryForLetter($letter),
        ]);
    }


    public function verify($token)
    {
        $method = request()->query('method');
        $method = in_array($method, ['scan', 'url'], true) ? $method : 'url';

        $letter = Letter::with('student')
            ->where('token', $token)
            ->first();

        if (!$letter) {
            $this->logVerification(null, $token, 'not_found', $method, request());
            return view('letters.verify', [
                'valid' => false
            ]);
        }

        if ($letter->status === 'dibatalkan') {
            $this->logVerification($letter, $token, 'cancelled', $method, request());
            return view('letters.verify', [
                'valid' => false,
                'error' => 'Surat ini telah dibatalkan.'
            ]);
        }

        $this->logVerification($letter, $token, 'valid', $method, request());
        return view('letters.verify', [
            'valid'  => true,
            'letter' => $letter,
            'history' => $this->getRequestHistoryForLetter($letter),
        ]);
    }

    private function getRequestHistoryForLetter(Letter $letter)
    {
        if ($letter->letter_type === 'bebas_pustaka') {
            $req = BebasPustakaRequest::with('histories')->where('letter_id', $letter->id)->first();
            return $req?->histories ?? collect();
        }

        if ($letter->letter_type === 'penyerahan_skripsi') {
            $req = SkripsiRequest::with('histories')->where('letter_id', $letter->id)->first();
            return $req?->histories ?? collect();
        }

        return collect();
    }

    private function logVerification(?Letter $letter, ?string $token, string $status, string $method, Request $request): void
    {
        VerificationLog::create([
            'letter_id' => $letter?->id,
            'token' => $token,
            'status' => $status,
            'method' => $method,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }


    public function archive(Request $request)
    {
        $query = $this->buildArchiveQuery($request);

        $letters = $query->orderByDesc('created_at')->paginate(10);
        $departments = Department::orderBy('name')->get();

        return view('letters.archive', compact('letters', 'departments'));
    }

    public function exportArchiveCsv(Request $request): StreamedResponse
    {
        $letters = $this->buildArchiveQuery($request)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'arsip-surat-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($letters) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Nomor Surat',
                'NIM',
                'Nama',
                'Program Studi',
                'Jenis Surat',
                'Status',
                'Tanggal',
            ]);

            foreach ($letters as $row) {
                fputcsv($handle, [
                    $row->letter_number,
                    $row->student?->nim,
                    $row->student?->nama,
                    $row->student?->department?->name,
                    $row->letter_type,
                    $row->status,
                    optional($row->created_at)->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function buildArchiveQuery(Request $request)
    {
        $query = Letter::with('student.department');

        if ($request->filled('type')) {
            $query->where('letter_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('letter_number', 'like', '%'.$request->search.'%')
                    ->orWhereHas('student', function ($s) use ($request) {
                        $s->where('nama', 'like', '%'.$request->search.'%')
                            ->orWhere('nim', 'like', '%'.$request->search.'%');
                    });
            });
        }

        return $query;
    }
}
