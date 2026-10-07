<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::withCount('schedules');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where('name', 'ILIKE', "%{$q}%");
        }

        $subjects = $query->orderBy('name')->paginate(30)->withQueryString();

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.form', ['subject' => new Subject()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:subjects,name',
        ], [
            'name.unique' => 'Bu fan allaqachon mavjud.',
        ]);

        Subject::create([
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('ok', "Fan qo'shildi.");
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.form', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('subjects', 'name')->ignore($subject->id)],
        ], [
            'name.unique' => 'Bu fan allaqachon mavjud.',
        ]);

        $subject->update([
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('ok', 'Fan yangilandi.');
    }

    public function destroy(Subject $subject)
    {
        $name = $subject->name;

        // Fan bilan bog'liq darslar ham avtomatik o'chadi (cascade)
        $subject->delete();

        return back()->with('ok', "\"{$name}\" fani va u bilan bog'liq darslar o‘chirildi.");
    }
}