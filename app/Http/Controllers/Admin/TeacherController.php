<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role_id', 2)->with('subjects');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'ILIKE', "%{$q}%")
                  ->orWhere('last_name', 'ILIKE', "%{$q}%")
                  ->orWhere('middle_name', 'ILIKE', "%{$q}%")
                  ->orWhere('email', 'ILIKE', "%{$q}%");
            });
        }

        $teachers = $query->orderBy('last_name')->paginate(30)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.form', [
            'teacher'  => new User(),
            'subjects' => \App\Models\Subject::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $teacher = User::create([
            'role_id'     => 2,
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'email'       => $data['email'],
            'phone'       => $data['phone'] ?? null,
            'password'    => Hash::make($data['password']),
            'is_active'   => true,
        ]);

        // Fanlarni bog'lash
        if (!empty($data['subjects'])) {
            $teacher->subjects()->sync($data['subjects']);
        }

        return redirect()->route('admin.teachers.index')
                         ->with('ok', "O'qituvchi qo'shildi.");
    }

    public function edit(User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        return view('admin.teachers.form', [
            'teacher'  => $teacher,
            'subjects' => \App\Models\Subject::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        $data = $this->validated($request, $teacher->id);

        $update = [
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'email'       => $data['email'],
            'phone'       => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $teacher->update($update);

        // Fanlarni yangilash
        $teacher->subjects()->sync($data['subjects'] ?? []);

        return redirect()->route('admin.teachers.index')
                         ->with('ok', "O'qituvchi yangilandi.");
    }

    public function destroy(User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        if ($teacher->schedules()->count() > 0) {
            return back()->withErrors(['error' => "Bu o'qituvchining darslari bor."]);
        }

        $teacher->delete();

        return back()->with('ok', "O'qituvchi o'chirildi.");
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'first_name'  => 'required|string|max:50',
            'last_name'   => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email'       => ['required', 'email', Rule::unique('users', 'email')->ignore($ignoreId)],
            'phone'       => 'nullable|string|max:20',
            'password'    => $ignoreId ? 'nullable|string|min:6' : 'required|string|min:6',
            'subjects'    => 'nullable|array',
            'subjects.*'  => 'exists:subjects,id',
        ], [
            'email.unique' => 'Bu email allaqachon ro‘yxatdan o‘tgan.',
            'password.min' => 'Parol kamida 6 ta belgidan iborat bo‘lishi kerak.',
        ]);
    }
}