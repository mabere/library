<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\QrHelper;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function card($id)
    {
        $student = Student::findOrFail($id);

        $qrCode = QrHelper::generateBase64(
            url('/verify-member/' . $student->nim)
        );

        return Pdf::loadView('cards.index', [
            'student' => $student,
            'qrCode'  => $qrCode,
        ])
        ->setPaper([0, 0, 242, 153])
        ->stream('kartu-'.$student->nim.'.pdf');
    }


}
