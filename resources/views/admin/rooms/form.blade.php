@extends('layouts.admin')

@php($isEdit = $room->exists)

@section('title', $isEdit ? 'Xonani tahrirlash' : 'Yangi xona')
@section('page-title', $isEdit ? 'Xonani tahrirlash' : 'Yangi xona qo‘shish')
@section('page-subtitle', $isEdit ? $room->name : 'Xona nomini kiriting')

@section('content')

<form method="POST"
      action="{{ $isEdit ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="form-grid">
    <div class="field full">
      <label>Xona nomi *</label>
      <input type="text" name="name" value="{{ old('name', $room->name) }}"
             placeholder="Masalan: 105, 206, BLUE HALL, LAB C101..."
             required autofocus>
      <div style="font-size:11px;color:var(--grey-2);margin-top:6px;">
        Xona raqami yoki nomi
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
    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Bekor qilish</a>
  </div>
</form>

@endsection