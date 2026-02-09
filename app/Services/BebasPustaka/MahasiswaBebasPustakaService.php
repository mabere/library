<?php

namespace App\Services\BebasPustaka;

use App\Models\BebasPustakaRequest;
use App\Models\User;
use App\Enums\BebasPustakaStatus;
use DomainException;

class MahasiswaBebasPustakaService
{
    public function ensureCanSubmit(User $user): void
    {
        $userId = $user->id;

        $sudahDisetujui = BebasPustakaRequest::where('user_id', $userId)
            ->where('status', BebasPustakaStatus::DISETUJUI_KEPALA->value)
            ->exists();

        if ($sudahDisetujui) {
            throw new DomainException(
                'Pengajuan bebas pustaka sudah disetujui. Anda tidak dapat mengajukan lagi.'
            );
        }

        $sedangDiproses = BebasPustakaRequest::where('user_id', $userId)
            ->whereIn('status', [
                BebasPustakaStatus::DIAJUKAN->value,
                BebasPustakaStatus::DIVERIFIKASI_STAF->value,
            ])
            ->exists();

        if ($sedangDiproses) {
            throw new DomainException(
                'Masih ada pengajuan bebas pustaka yang sedang diproses.'
            );
        }
    }

    public function submit(User $user): BebasPustakaRequest
    {
        $student = $user->student;

        if (!$student) {
            throw new DomainException(
                'Data mahasiswa belum terhubung dengan akun. Hubungi admin.'
            );
        }

        return BebasPustakaRequest::create([
            'user_id'      => $user->id,
            'student_id'   => $student->id,
            'nim'          => $student->nim,
            'nama'         => $student->nama,
            'prodi'        => $student->department?->name ?? '-',
            'status'       => BebasPustakaStatus::DIAJUKAN->value,
            'submitted_at' => now(),
        ]);
    }
}
