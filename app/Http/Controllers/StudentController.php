<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * List students (READ ONLY)
     */
    public function index()
    {
        $this->authorize('viewAny', Student::class);

        $students = Student::with(['department', 'user'])
            ->orderBy('nama')
            ->paginate(15);

        return view('students.index', compact('students'));
    }

    /**
     * Edit student (KOREKSI TERBATAS)
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        $departments = Department::orderBy('name')->get();

        return view('students.edit', compact('student', 'departments'));
    }

    /**
     * Update student (FIELD DIBATASI)
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $data = $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
        ]);

        // ⛔ tidak boleh update nim, user_id, skripsi, dll
        $student->update($data);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Assign user ke student
     */
    public function assignUser(Request $request, Student $student)
    {
        $this->authorize('assignUser', $student);

        $data = $request->validate([
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($data, $student) {

            $password = $data['password'] ?? Str::random(10);

            $user = User::create([
                'name'     => $student->nama,
                'email'    => $data['email'],
                'password' => Hash::make($password),
            ]);

            // role mahasiswa (ID = 5)
            $user->roles()->syncWithoutDetaching([5]);

            // relasi ke student (source of truth)
            $student->forceFill([
                'user_id' => $user->id,
            ])->save();
        });

        return back()->with(
            'success',
            'Akun mahasiswa berhasil dibuat dan dihubungkan.'
        );
    }
}

