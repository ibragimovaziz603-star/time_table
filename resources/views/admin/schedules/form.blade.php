@extends('layouts.admin')

@php($isEdit = $schedule->exists)

@section('title', $isEdit ? 'Darsni tahrirlash' : 'Yangi dars')
@section('page-title', $isEdit ? 'Darsni tahrirlash' : 'Yangi dars qo‘shish')
@section('page-subtitle', 'Barcha maydonlarni to‘ldiring')

@section('content')

  <form method="POST"
    action="{{ $isEdit ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="form-grid">

      {{-- Guruh --}}
      <div class="field">
        <label>Guruh *</label>
        <select name="group_id" required>
          @foreach($groups as $g)
            <option value="{{ $g->id }}" @selected(old('group_id', $schedule->group_id ?? $prefillGroup ?? null) == $g->id)>
              {{ $g->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Fan --}}
      <div class="field">
        <label>Fan *</label>
        <select name="subject_id" required>
          @foreach($subjects as $s)
            <option value="{{ $s->id }}" @selected(old('subject_id', $schedule->subject_id) == $s->id)>
              {{ $s->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- O'qituvchi --}}
      <div class="field">
        <label>O'qituvchi *</label>
        <select name="teacher_id" required>
          @foreach($teachers as $t)
            <option value="{{ $t->id }}" @selected(old('teacher_id', $schedule->teacher_id) == $t->id)>
              {{ $t->last_name }} {{ $t->first_name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Xona --}}
      <div class="field">
        <label>Xona *</label>
        <select name="room_id" required>
          @foreach($rooms as $r)
            <option value="{{ $r->id }}" @selected(old('room_id', $schedule->room_id ?? $prefillRoom ?? null) == $r->id)>
              {{ $r->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Kun --}}
      <div class="field">
        <label>Kun *</label>
        <select name="day_id" required>
          @foreach($days as $d)
            <option value="{{ $d->id }}" @selected(old('day_id', $schedule->day_id ?? $prefillDay ?? null) == $d->id)>
              {{ $d->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Davr --}}
      <div class="field">
        <label>Davr *</label>
        <select name="period_id" required>
          @foreach($periods as $p)
            <option value="{{ $p->id }}" @selected(old('period_id', $schedule->period_id ?? $prefillPeriod ?? null) == $p->id)>
              {{ $p->number }}.
              ({{ \Carbon\Carbon::parse($p->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->end_time)->format('H:i') }})
            </option>
          @endforeach
        </select>
      </div>

      {{-- Dars turi --}}
      <div class="field">
        <label>Dars turi *</label>
        <select name="lesson_type" required>
          <option value="lec" @selected(old('lesson_type', $schedule->lesson_type) == 'lec')>Ma'ruza (lec)</option>
          <option value="prac" @selected(old('lesson_type', $schedule->lesson_type) == 'prac')>Amaliy (prac)</option>
          <option value="lab" @selected(old('lesson_type', $schedule->lesson_type) == 'lab')>Laboratoriya (lab)</option>
        </select>
      </div>

      {{-- Semestr --}}
      <div class="field">
        <label>Semestr</label>
        <input type="hidden" name="semester" value="2025-fall">
        <div style="padding:11px 13px;background:var(--bg-2);border:1px solid var(--line);
              border-radius:10px;color:var(--grey-2);font-size:13.5px;">
          2025-fall (avtomatik)
        </div>
      </div>

    </div>

    <div style="display:flex;gap:10px;margin-top:26px;">
      <button type="submit" class="btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
          stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        {{ $isEdit ? 'Yangilash' : 'Saqlash' }}
      </button>
      <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Bekor qilish</a>
    </div>
  </form>

@endsection