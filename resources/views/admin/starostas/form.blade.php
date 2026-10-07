@extends('layouts.admin')

@php($isEdit = $starosta->exists)

@section('title', $isEdit ? "Starostani tahrirlash" : "Yangi starosta")
@section('page-title', $isEdit ? "Starostani tahrirlash" : "Yangi starosta qo'shish")
@section('page-subtitle', 'Starosta ma’lumotlarini kiriting')

@section('content')

<form method="POST"
      action="{{ $isEdit ? route('admin.starostas.update', $starosta) : route('admin.starostas.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="form-grid">

    <div class="field">
      <label>Familiya *</label>
      <input type="text" name="last_name" value="{{ old('last_name', $starosta->last_name) }}"
             placeholder="Yusupov" required autofocus>
    </div>

    <div class="field">
      <label>Ism *</label>
      <input type="text" name="first_name" value="{{ old('first_name', $starosta->first_name) }}"
             placeholder="Aziz" required>
    </div>

    <div class="field">
      <label>Otasining ismi</label>
      <input type="text" name="middle_name" value="{{ old('middle_name', $starosta->middle_name) }}"
             placeholder="Baxtiyorovich">
    </div>

    <div class="field">
      <label>Email *</label>
      <input type="email" name="email" value="{{ old('email', $starosta->email) }}"
             placeholder="starosta@ttpu.uz" required>
    </div>

    <div class="field">
      <label>Telefon</label>
      <input type="text" name="phone" value="{{ old('phone', $starosta->phone) }}"
             placeholder="+998901234567">
    </div>

    <div class="field">
      <label>Parol {{ $isEdit ? '(o‘zgartirmasangiz bo‘sh qoldiring)' : '*' }}</label>
      <input type="password" name="password"
             placeholder="{{ $isEdit ? '••••••••' : 'kamida 6 belgi' }}"
             {{ $isEdit ? '' : 'required' }}>
    </div>

    <div class="field full" style="grid-column:1/-1;">
      <label>Guruh * (qaysi guruhga starosta)</label>
      <select name="group_id" required>
        <option value="">— Guruh tanlang —</option>
        @foreach($groups as $g)
          <option value="{{ $g->id }}"
            @selected(old('group_id', $isEdit ? ($currentGroup->id ?? null) : null) == $g->id)>
            {{ $g->name }}{{ $g->course ? ' (' . $g->course . '-kurs)' : '' }}
          </option>
        @endforeach
      </select>
      <div style="font-size:11px;color:var(--grey-2);margin-top:6px;">
        <strong>Diqqat:</strong> bitta guruhda faqat 1 ta starosta bo'lishi mumkin. Agar boshqa starosta tanlansa, avvalgi almashtiriladi.
      </div>
    </div>

  </div>

  <div style="display:flex;gap:10px;margin-top:26px;">
    <button type="submit" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      {{ $isEdit ? 'Yangilash' : 'Saqlash' }}
    </button>
    <a href="{{ route('admin.starostas.index') }}" class="btn btn-secondary">Bekor qilish</a>
  </div>
</form>

@endsection