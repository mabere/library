<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['users' => function ($q) {
                $q->wherePivot('role_in_department', 'kaprodi');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
            'code' => ['nullable', 'string', 'max:20', 'unique:departments,code'],
        ]);

        Department::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $department = Department::findOrFail($id);
        $kaprodiRole = Role::where('name', 'kaprodi')->first();
        $kaprodiUsers = $kaprodiRole
            ? User::whereHas('roles', function ($q) use ($kaprodiRole) {
                $q->where('roles.id', $kaprodiRole->id);
            })->orderBy('name')->get()
            : collect();
        $selectedKaprodiUserId = $department->users()
            ->wherePivot('role_in_department', 'kaprodi')
            ->value('users.id');

        return view('admin.departments.edit', compact('department', 'kaprodiUsers', 'selectedKaprodiUserId'));
    }

    public function update(Request $request, int $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($department->id)],
            'code' => ['nullable', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($department->id)],
            'kaprodi_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $department->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        if ($request->filled('kaprodi_user_id')) {
            // Replace existing kaprodi mapping for this department
            $department->users()->wherePivot('role_in_department', 'kaprodi')->detach();
            $department->users()->attach($request->kaprodi_user_id, [
                'role_in_department' => 'kaprodi',
            ]);
        } else {
            $department->users()->wherePivot('role_in_department', 'kaprodi')->detach();
        }

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $department = Department::findOrFail($id);

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department berhasil dihapus.');
    }
}
