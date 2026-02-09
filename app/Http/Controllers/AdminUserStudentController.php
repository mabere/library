<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserStudentController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'mahasiswa');
            })
            ->with('student')
            ->orderBy('name')
            ->get();

        $students = Student::orderBy('nama')->get();

        return view('admin.user-student', compact('users', 'students'));
    }

    public function update(Request $request, int $userId)
    {
        $request->validate([
            'student_id' => 'nullable|exists:students,id',
        ]);

        $user = User::whereHas('roles', function ($q) {
                $q->where('name', 'mahasiswa');
            })
            ->findOrFail($userId);
        $user->update([
            'student_id' => $request->student_id,
        ]);

        return back()->with('success', 'Mapping user dan mahasiswa berhasil diperbarui.');
    }
}
