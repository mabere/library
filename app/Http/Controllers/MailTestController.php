<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailTestController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'to' => ['required', 'email'],
        ]);

        Mail::raw('Test SMTP dari aplikasi perpustakaan.', function ($message) use ($request) {
            $message->to($request->to)
                ->subject('Test SMTP');
        });

        return back()->with('success', 'Test email dikirim.');
    }
}
