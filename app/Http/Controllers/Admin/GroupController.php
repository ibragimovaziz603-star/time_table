<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
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
            'name'    => 'required|string|max:50|unique:groups,name',
            'course'  => 'nullable|integer|min:1|max:6',
        ], [
            'name.unique' => 'Bu guruh allaqachon mavjud.',
        ]);

        Group::create([
            'name'   => $data['name'],
            'course' => $data['course'] ?? null,
        ]);

        return redirect()->route('admin.groups.index')
                         ->with('ok', "Guruh qo'shildi.");
    }

    public function edit(Group $group)
    {
        return view('admin.groups.form', compact('group'));
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

        return redirect()->route('admin.groups.index')
                         ->with('ok', 'Guruh yangilandi.');
    }

    public function destroy(Group $group)
    {
        $name = $group->name;
        $group->delete();

        return back()->with('ok', "\"{$name}\" guruhi va u bilan bog'liq ma'lumotlar o‘chirildi.");
    }
}