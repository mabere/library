<?php

namespace App\Helpers;

use App\Models\Letter;
use Carbon\Carbon;

class LetterNumberHelper
{
    public static function generate($type)
    {
        $year = date('Y');
        $month = date('n');

        // Romawi bulan
        $romanMonth = self::toRoman($month);

        // Kode jenis surat
        $typeCode = match ($type) {
            'bebas_pustaka' => 'BP',
            'penyerahan_skripsi' => 'SKR',
            default => 'LTR'
        };

        // Hitung jumlah surat tahun ini & jenis ini
        $count = Letter::whereYear('created_at', $year)
            ->where('letter_type', $type)
            ->count();

        $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "{$number}/{$typeCode}/UPT-PERPUS/{$romanMonth}/{$year}";
    }

    private static function toRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $map[$month];
    }

    public static function getTitle($type)
    {
        return match ($type) {
            'bebas_pustaka' => 'SURAT KETERANGAN BEBAS PUSTAKA',
            'penyerahan_skripsi' => 'SURAT KETERANGAN PENYERAHAN SKRIPSI',
            default => 'SURAT KETERANGAN'
        };
    }

    public static function getDescription($type)
    {
        return match ($type) {
            'bebas_pustaka' =>
                'Telah menyelesaikan kewajiban perpustakaan dan tidak memiliki tanggungan peminjaman maupun denda.',
            'penyerahan_skripsi' =>
                'Telah menyerahkan skripsi sebagai salah satu syarat penyelesaian studi.',
            default => ''
        };
    }

    public function verify($id)
    {
        $letter = Letter::with('student')->find($id);

        if (!$letter) {
            return view('letters.verify', [
                'valid' => false
            ]);
        }

        return view('letters.verify', [
            'valid' => true,
            'letter' => $letter
        ]);
    }

}
