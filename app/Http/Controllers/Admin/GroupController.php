<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::withCount(['students', 'schedules']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where('name', 'ILIKE', "%{$q}%");
        }

        $groups = $query->orderBy('course')->orderBy('name')->paginate(40)->withQueryString();

        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.groups.form', ['group' => new Group()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:50|unique:groups,name',
            'course' => 'nullable|integer|min:1|max:6',
        ], [
            'name.unique' => 'Bu guruh allaqachon mavjud.',
        ]);

        $group = Group::create([
            'name'   => $data['name'],
            'course' => $data['course'] ?? null,
        ]);

        // Kursni nomdan avtomatik aniqlash
        if (method_exists($group, 'syncCourseFromName')) {
            $group->syncCourseFromName();
        }

        return redirect()->route('admin.groups.index')
                         ->with('ok', "Guruh qo'shildi.");
    }

    public function edit(Group $group)
    {
        // Guruhdagi talabalar
        $students = $group->students()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Boshqa talabalar (qo'shish uchun)
        $allStudents = User::where('role_id', 3)
            ->whereNotIn('id', $students->pluck('id'))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.groups.form', compact('group', 'students', 'allStudents'));
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:50', Rule::unique('groups', 'name')->ignore($group->id)],
            'course' => 'nullable|integer|min:1|max:6',
        ], [
            'name.unique' => 'Bu guruh allaqachon mavjud.',
        ]);

        $group->update([
            'name'   => $data['name'],
            'course' => $data['course'] ?? null,
        ]);

        // Kursni nomdan avtomatik aniqlash
        if (method_exists($group, 'syncCourseFromName')) {
            $group->syncCourseFromName();
        }

        return redirect()->route('admin.groups.edit', $group)
                         ->with('ok', 'Guruh yangilandi.');
    }

    public function destroy(Group $group)
    {
        $name = $group->name;
        $group->delete();

        return redirect()->route('admin.groups.index')
                         ->with('ok', "\"{$name}\" guruhi va u bilan bog'liq ma'lumotlar o‘chirildi.");
    }

    /** Guruhga talaba qo'shish */
    public function addStudent(Request $request, Group $group)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $group->students()->syncWithoutDetaching([$data['student_id']]);

        return redirect()->route('admin.groups.edit', $group)
                         ->with('ok', "Talaba guruhga qo'shildi.");
    }

    /** Guruhdan talaba olib tashlash */
    public function removeStudent(Group $group, $studentId)
    {
        $group->students()->detach($studentId);

        return redirect()->route('admin.groups.edit', $group)
                         ->with('ok', "Talaba guruhdan olib tashlandi.");
    }
}