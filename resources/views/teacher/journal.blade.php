@extends('layouts.teacher')

@section('title', 'Jurnal')
@section('page-title', 'Jurnal')
@section('page-subtitle', $todayDay ? $todayDay->name . ' — bugungi darslaringiz' : 'Bugungi darslar')

@section('content')

@if(empty($grouped))
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
              padding:26px;color:var(--grey-2);text-align:center;">
    Bugun ({{ $todayDay?->name ?? 'Bugun' }}) darslaringiz yo'q.
  </div>
@else
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;">
    @foreach($grouped as $key => $item)
      @php
        // Paralar ro'yxati (masalan, "1, 3")
        $periods = $item['schedules']->pluck('period.number')->unique()->sort()->implode(', ');
      @endphp
      <a href="{{ route('teacher.journal.edit', [$item['group']->id, $item['subject']->id]) }}"
         style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
                padding:20px;text-decoration:none;color:inherit;transition:all .15s;display:block;"
         onmouseover="this.style.borderColor='rgba(91,140,255,0.5)';this.style.transform='translateY(-3px)'"
         onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.transform='translateY(0)'">

        <div style="font-family:'Space Grotesk';font-size:17px;font-weight:700;margin-bottom:6px;">
          {{ $item['group']->name }}
        </div>

        <div style="color:var(--grey-2);font-size:13px;margin-bottom:14px;">
          {{ $item['group']->course ? $item['group']->course . '-kurs · ' : '' }}
          {{ $item['group']->students()->count() }} talaba
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;">
          <span style="background:rgba(91,140,255,0.15);color:#5b8cff;padding:4px 12px;
                       border-radius:999px;font-size:12px;font-weight:700;">
            {{ $item['subject']->name }}
          </span>
          <span style="color:var(--grey-2);font-size:12px;">
            {{ $periods }}-para
          </span>
        </div>
      </a>
    @endforeach
  </div>
@endif

@endsection