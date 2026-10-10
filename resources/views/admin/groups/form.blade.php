@extends('layouts.admin')

@php($isEdit = $group->exists)

@section('title', $isEdit ? $group->name . ' — tahrirlash' : 'Yangi guruh')
@section('page-title', $isEdit ? $group->name : 'Yangi guruh qo‘shish')
@section('page-subtitle', $isEdit ? 'Guruh nomi va talabalar' : 'Guruh nomini kiriting')

@section('content')

@if($isEdit)

  {{-- ========== GURUH NOMI ========== --}}
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
              padding:24px;margin-bottom:24px;max-width:800px;">
    <h2 style="font-family:'Space Grotesk';font-size:16px;margin-bottom:16px;color:var(--grey-2);">
      GURUH MA'LUMOTLARI
    </h2>

    <form method="POST" action="{{ route('admin.groups.update', $group) }}">
      @csrf
      @method('PUT')

      <div class="form-grid">
        <div class="field">
          <label>Guruh nomi *</label>
          <input type="text" name="name" value="{{ old('name', $group->name) }}"
                 placeholder="Masalan: CYB1-26, AD-23" required>
        </div>

        <div class="field">
          <label>Kurs</label>
          <select name="course">
            <option value="">— Tanlanmagan —</option>
            @for($i = 1; $i <= 6; $i++)
              <option value="{{ $i }}" @selected(old('course', $group->course) == $i)>
                {{ $i }}-kurs
              </option>
            @endfor
          </select>
        </div>
      </div>

      <div style="display:flex;gap:10px;margin-top:20px;">
        <button type="submit" class="btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          Saqlash
        </button>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Orqaga</a>
      </div>
    </form>
  </div>

  {{-- ========== TALABALAR ========== --}}
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
              padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;
                margin-bottom:20px;flex-wrap:wrap;">
      <h2 style="font-family:'Space Grotesk';font-size:16px;color:var(--grey-2);">
        TALABALAR ({{ $students->count() }})
      </h2>

      @if($allStudents->count() > 0)
        <form method="POST" action="{{ route('admin.groups.add-student', $group) }}"
              style="display:flex;gap:8px;align-items:center;">
          @csrf
          <select name="student_id" required
                  style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;
                         color:#fff;padding:9px 14px;font-size:13.5px;font-family:inherit;min-width:260px;">
            <option value="">— Talaba tanlang —</option>
            @foreach($allStudents as $s)
              <option value="{{ $s->id }}">
                {{ $s->last_name }} {{ $s->first_name }}
              </option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-sm">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Qo'shish
          </button>
        </form>
      @endif
    </div>

    @if($students->isEmpty())
      <div style="text-align:center;padding:30px;color:var(--grey-2);font-size:13.5px;">
        Bu guruhda hali talabalar yo'q.
      </div>
    @else
      <table>
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Familiya</th>
            <th>Ism</th>
            <th>Email</th>
            <th>Telefon</th>
            <th style="width:80px;">Qamallar</th>
          </tr>
        </thead>
        <tbody>
          @foreach($students as $i => $s)
            <tr>
              <td style="color:var(--grey-2);">{{ $i + 1 }}</td>
              <td><strong>{{ $s->last_name }}</strong></td>
              <td>{{ $s->first_name }}</td>
              <td style="color:var(--grey-2);">{{ $s->email }}</td>
              <td style="color:var(--grey-2);">{{ $s->phone ?? '—' }}</td>
              <td>
                <form method="POST"
                      action="{{ route('admin.groups.remove-student', [$group, $s->id]) }}"
                      onsubmit="return confirm('{{ $s->last_name }} {{ $s->first_name }} guruhdan olib tashlansinmi?');"
                      style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" title="Olib tashlash">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

@else

  {{-- ========== YANGI GURUH ========== --}}
  <form method="POST" action="{{ route('admin.groups.store') }}" style="max-width:800px;">
    @csrf

    <div class="form-grid">
      <div class="field">
        <label>Guruh nomi *</label>
        <input type="text" name="name" value="{{ old('name') }}"
               placeholder="Masalan: CYB1-26, AD-23" required autofocus>
        <div style="font-size:11px;color:var(--grey-2);margin-top:6px;">
          <strong>Kurs avtomatik aniqlanadi:</strong>
          <code>-26</code> → 1-kurs · <code>-25</code> → 2-kurs · <code>-24</code> → 3-kurs · <code>-23</code> → 4-kurs
        </div>
      </div>

      <div class="field">
        <label>Kurs (avtomatik)</label>
        <select name="course">
          <option value="">— Tanlanmagan —</option>
          @for($i = 1; $i <= 6; $i++)
            <option value="{{ $i }}" @selected(old('course') == $i)>{{ $i }}-kurs</option>
          @endfor
        </select>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:26px;">
      <button type="submit" class="btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Saqlash
      </button>
      <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Bekor qilish</a>
    </div>
  </form>

@endif

@endsection