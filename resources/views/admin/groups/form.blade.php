@extends('layouts.admin')

@php($isEdit = $group->exists)

@section('title', $isEdit ? 'Guruhni tahrirlash' : 'Yangi guruh')
@section('page-title', $isEdit ? 'Guruhni tahrirlash' : 'Yangi guruh qo‘shish')
@section('page-subtitle', $isEdit ? $group->name : 'Guruh nomini va kursni kiriting')

@section('content')

<form method="POST"
      action="{{ $isEdit ? route('admin.groups.update', $group) : route('admin.groups.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="form-grid">

    <div class="field">
      <label>Guruh nomi *</label>
      <input type="text" name="name" value="{{ old('name', $group->name) }}"
             placeholder="Masalan: AD-23, IT-25..." required autofocus>
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

  <div style="display:flex;gap:10px;margin-top:26px;">
    <button type="submit" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      {{ $isEdit ? 'Yangilash' : 'Saqlash' }}
    </button>
    <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Bekor qilish</a>
  </div>
</form>

@endsection