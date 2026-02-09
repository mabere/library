<?php

namespace App\Services\Skripsi;

use App\Models\SkripsiRequest;
use App\Models\Student;
use App\Models\Letter;
use App\Models\Department;
use App\Enums\SkripsiStatus;
use App\Helpers\LetterNumberHelper;
use App\Helpers\QrHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\PDF;
use DomainException;

class ApproveSkripsiService
{
    public function approve(SkripsiRequest $req, int $kepalaUserId): SkripsiRequest
    {
        if ($req->status !== SkripsiStatus::DIVERIFIKASI_STAF->value) {
            throw new DomainException('Pengajuan belum diverifikasi staf.');
        }

        if ($req->letter_id) {
            throw new DomainException('Surat sudah dibuat.');
        }

        return DB::transaction(function () use ($req, $kepalaUserId) {

            // 🔹 Sinkronisasi student
            $department = Department::where('name', $req->prodi)->first();

            $student = Student::updateOrCreate(
                ['nim' => $req->nim],
                [
                    'nama'          => $req->nama,
                    'department_id' => $department?->id,
                    'judul_skripsi' => $req->judul_skripsi,
                    'tahun_lulus'   => $req->tahun_lulus,
                ]
            );

            // 🔹 Buat surat
            $title  = LetterNumberHelper::getTitle('penyerahan_skripsi');
            $number = LetterNumberHelper::generate('penyerahan_skripsi');

            $letter = Letter::create([
                'student_id'   => $student->id,
                'letter_type'  => 'penyerahan_skripsi',
                'letter_number'=> $number,
                'token'        => Str::uuid(),
                'verified_by'  => 'Kepala Perpustakaan',
                'verified_at'  => now(),
            ]);

            // 🔹 Generate PDF + QR
            $qrCode = QrHelper::generateBase64(
                url('/letter/' . $letter->token)
            );

            $pdf = PDF::loadView('letters.pdf', [
                'student'      => $student,
                'number'       => $number,
                'description'  => LetterNumberHelper::getDescription('penyerahan_skripsi'),
                'verified_by'  => $letter->verified_by,
                'qrCode'       => $qrCode,
                'title'        => $title,
                'letter_type'  => 'penyerahan_skripsi',
            ]);

            $path = 'letters/surat-skripsi-' . $student->nim . '-' . time() . '.pdf';
            Storage::disk('public')->put($path, $pdf->output());

            $letter->update(['file_path' => $path]);

            // 🔹 Update request
            $req->update([
                'student_id' => $student->id,
                'letter_id'  => $letter->id,
                'status'     => SkripsiStatus::DISETUJUI_KEPALA->value,
                'approved_by'=> $kepalaUserId,
                'approved_at'=> now(),
            ]);

            return $req;
        });
    }
}
