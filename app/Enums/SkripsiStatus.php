<?php

namespace App\Enums;

enum SkripsiStatus: string
{
    case DIAJUKAN = 'diajukan';
    case DIVERIFIKASI_STAF = 'diverifikasi_staf';
    case DITOLAK_STAF = 'ditolak_staf';
    case DISETUJUI_KEPALA = 'disetujui_kepala';

    public function label(): string
    {
        return match ($this) {
            self::DIAJUKAN => 'Diajukan',
            self::DIVERIFIKASI_STAF => 'Diverifikasi Staf',
            self::DITOLAK_STAF => 'Ditolak Staf',
            self::DISETUJUI_KEPALA => 'Disetujui Kepala',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DIAJUKAN => 'secondary',
            self::DIVERIFIKASI_STAF => 'warning',
            self::DITOLAK_STAF => 'danger',
            self::DISETUJUI_KEPALA => 'success',
        };
    }


}
