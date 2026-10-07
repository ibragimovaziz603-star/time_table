@extends('layouts.admin')

@php($isEdit = $subject->exists)

@section('title', $isEdit ? 'Fanni tahrirlash' : 'Yangi fan')
@section('page-title', $isEdit ? 'Fanni tahrirlash' : 'Yangi fan qo‘shish')
@section('page-subtitle', $isEdit ? $subject->name : 'Fan nomini kiriting')

@section('content')

<form method="POST"
      action="{{ $isEdit ? route('admin.subjects.update', $subject) : route('admin.subjects.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="form-grid">
    <div class="field full">
      <label>Fan nomi *</label>
      <input type="text" name="name" value="{{ old('name', $subject->name) }}"
             placeholder="Masalan: Matematik analiz, Fizika II, Dasturlash asoslari"
             required autofocus>
    </div>
  </div>

  <div style="display:flex;gap:10px;margin-top:26px;">
    <button type="submit" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      {{ $isEdit ? 'Yangilash' : 'Saqlash' }}
    </button>
    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Bekor qilish</a>
  </div>
</form>

@endsection