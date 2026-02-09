<?php

namespace App\Services\BebasPustaka;

use App\Models\BebasPustakaRequest;
use App\Models\Department;
use App\Models\Letter;
use App\Models\Student;
use App\Enums\BebasPustakaStatus;
use App\Helpers\LetterNumberHelper;
use App\Helpers\QrHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\PDF;
use Throwable;

class ApproveBebasPustakaService
{
    public function approve(BebasPustakaRequest $req, int $approvedByUserId): void
    {
        if ($req->status !== BebasPustakaStatus::DIVERIFIKASI_STAF->value) {
            throw new \DomainException('Pengajuan belum diverifikasi staf.');
        }

        if ($req->letter_id) {
            throw new \DomainException('Surat sudah dibuat.');
        }

        DB::transaction(function () use ($req, $approvedByUserId) {

            // 1. Pastikan student konsisten
            $department = Department::where('name', $req->prodi)->first();

            $student = Student::updateOrCreate(
                ['nim' => $req->nim],
                [
                    'nama' => $req->nama,
                    'department_id' => $department?->id,
                    'judul_skripsi' => '-',
                    'tahun_lulus' => null,
                ]
            );

            // 2. Generate surat
            $number = LetterNumberHelper::generate('bebas_pustaka');
            $title = LetterNumberHelper::getTitle('bebas_pustaka');
            $description = LetterNumberHelper::getDescription('bebas_pustaka');

            $letter = Letter::create([
                'student_id'   => $student->id,
                'letter_type'  => 'bebas_pustaka',
                'letter_number'=> $number,
                'token'        => (string) Str::uuid(),
                'has_fine'     => false,
                'verified_by'  => $req->verifiedBy?->name ?? 'Petugas Perpustakaan',
                'verified_at'  => $req->verified_at,
            ]);

            // 3. Generate PDF
            $qrCode = QrHelper::generateBase64(
                url('/letter/' . $letter->token)
            );

            $pdf = PDF::loadView('letters.pdf', [
                'student'      => $student,
                'number'       => $number,
                'description'  => $description,
                'verified_by'  => $letter->verified_by,
                'qrCode'       => $qrCode,
                'title'        => $title,
                'letter_type'  => 'bebas_pustaka',
            ]);

            $path = 'letters/surat-' . $student->nim . '-' . time() . '.pdf';
            Storage::disk('public')->put($path, $pdf->output());

            $letter->update(['file_path' => $path]);

            // 4. Update request
            $req->update([
                'student_id' => $student->id,
                'letter_id'  => $letter->id,
                'status'     => BebasPustakaStatus::DISETUJUI_KEPALA->value,
                'approved_by'=> $approvedByUserId,
                'approved_at'=> now(),
            ]);
        });
    }
}
