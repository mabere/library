<?php

namespace App\Services\Skripsi;

use App\Models\SkripsiRequest;
use App\Models\User;
use App\Enums\SkripsiStatus;
use DomainException;

class MahasiswaSkripsiService
{
    public function ensureCanSubmit(User $user): void
    {
        $userId = $user->id;

        $sudahDisetujui = SkripsiRequest::where('user_id', $userId)
            ->where('status', SkripsiStatus::DISETUJUI_KEPALA->value)
            ->exists();

        if ($sudahDisetujui) {
            throw new DomainException(
                'Pengajuan penyerahan skripsi sudah disetujui. Anda tidak dapat mengajukan lagi.'
            );
        }

        $sedangDiproses = SkripsiRequest::where('user_id', $userId)
            ->whereIn('status', [
                SkripsiStatus::DIAJUKAN->value,
                SkripsiStatus::DIVERIFIKASI_STAF->value,
            ])
            ->exists();

        if ($sedangDiproses) {
            throw new DomainException(
                'Masih ada pengajuan penyerahan skripsi yang sedang diproses.'
            );
        }
    }

    public function submit(User $user, array $payload = []): SkripsiRequest
    {
        $student = $user->student;

        if (!$student) {
            throw new DomainException(
                'Data mahasiswa belum terhubung dengan akun. Hubungi admin.'
            );
        }

        return SkripsiRequest::create([
            'user_id'      => $user->id,
            'student_id'   => $student->id,
            'nim'          => $student->nim,
            'nama'         => $student->nama,
            'prodi'        => $student->department?->name ?? '-',
            'judul_skripsi' => $payload['judul_skripsi'],
            'tahun_lulus'   => $payload['tahun_lulus'],
            'status'       => SkripsiStatus::DIAJUKAN->value,
            'submitted_at' => now(),
        ]);
    }
}
