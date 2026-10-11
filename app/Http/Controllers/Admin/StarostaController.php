<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StarostaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role_id', 3)
            ->whereHas('starostaGroups')  // faqat starostalar
            ->with(['starostaGroups']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'ILIKE', "%{$q}%")
                    ->orWhere('last_name', 'ILIKE', "%{$q}%")
                    ->orWhere('email', 'ILIKE', "%{$q}%");
            });
        }

        $starostas = $query->orderBy('last_name')->paginate(30)->withQueryString();

        return view('admin.starostas.index', compact('starostas'));
    }

    public function create()
    {
        $groups = Group::orderBy('course')->orderBy('name')->get();
        return view('admin.starostas.form', [
            'starosta' => new User(),
            'groups' => $groups,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'group_id' => 'required|exists:groups,id',
        ]);

        // ✅ Avval DB'da email bor-yo'qligini tekshiramiz
        $starosta = User::where('email', $data['email'])->first();

        if ($starosta) {
            // ============ MAVJUD USER — faqat yangilash ============
            $update = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? $starosta->middle_name,
                'phone' => $data['phone'] ?? $starosta->phone,
                'role_id' => 3,  // Talaba roliga o'tkazamiz
            ];

            // Agar parol kiritilgan bo'lsa — yangilaymiz
            if (!empty($data['password'])) {
                $update['password'] = Hash::make($data['password']);
            }

            $starosta->update($update);

            $message = "{$starosta->last_name} {$starosta->first_name} mavjud edi — starosta qilib belgilandi.";
        } else {
            // ============ YANGI USER — yaratamiz ============
            $starosta = User::create([
                'role_id' => 3,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password'] ?? 'parol123'),
                'is_active' => true,
            ]);

            $message = "Yangi starosta qo'shildi.";
        }

        // ============ Guruhga starosta qilib belgilash ============
        $group = Group::find($data['group_id']);

        // Avvalgi guruhdan olib tashlash (agar boshqa guruhda starosta bo'lsa)
        Group::where('starosta_id', $starosta->id)
            ->where('id', '!=', $group->id)
            ->update(['starosta_id' => null]);

        // Yangi guruhga biriktirish
        $group->update(['starosta_id' => $starosta->id]);

        // Guruh talabalariga qo'shish
        $group->students()->syncWithoutDetaching([$starosta->id]);

        return redirect()->route('admin.starostas.index')
            ->with('ok', $message . " Guruh: {$group->name}");
    }

    public function edit(User $starosta)
    {
        // Faqat starostalarni tahrirlash
        if (!$starosta->isStarosta()) {
            abort(404);
        }

        $groups = Group::orderBy('course')->orderBy('name')->get();
        $currentGroup = $starosta->starostaGroups()->first();

        return view('admin.starostas.form', [
            'starosta' => $starosta,
            'groups' => $groups,
            'currentGroup' => $currentGroup,
        ]);
    }

    public function update(Request $request, User $starosta)
    {
        if (!$starosta->isStarosta()) {
            abort(404);
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($starosta->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'group_id' => 'required|exists:groups,id',
        ]);

        $update = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $starosta->update($update);

        // Avvalgi guruhdan olib tashlash
        Group::where('starosta_id', $starosta->id)
            ->update(['starosta_id' => null]);

        // Yangi guruhga biriktirish
        $group = Group::find($data['group_id']);
        $group->update(['starosta_id' => $starosta->id]);
        $group->students()->syncWithoutDetaching([$starosta->id]);

        return redirect()->route('admin.starostas.index')
            ->with('ok', 'Starosta yangilandi.');
    }

    public function destroy(User $starosta)
    {
        if (!$starosta->isStarosta()) {
            abort(404);
        }

        // Guruhdan olib tashlash
        Group::where('starosta_id', $starosta->id)
            ->update(['starosta_id' => null]);

        $starosta->delete();

        return back()->with('ok', "Starosta o'chirildi.");
    }
}