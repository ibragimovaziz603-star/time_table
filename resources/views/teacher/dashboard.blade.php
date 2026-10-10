@extends('layouts.teacher')

@section('title', 'Dashboard')
@section('page-title', 'Salom, ' . auth()->user()->first_name . '!')
@section('page-subtitle', $todayDay ? $todayDay->name . ' — bugungi darslaringiz' : 'Bugungi darslar')

@section('content')

{{-- Statistika --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin-bottom:28px;">

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Guruhlar</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;">{{ $stats['groups'] }}</div>
  </div>

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Fanlar</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;">{{ $stats['subjects'] }}</div>
  </div>

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Haftalik darslar</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;">{{ $stats['schedules'] }}</div>
  </div>

</div>

{{-- Bugungi darslar --}}
<h2 style="font-family:'Space Grotesk';font-size:18px;margin-bottom:14px;">Bugungi darslar</h2>

@if($todayLessons->isEmpty())
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
              padding:26px;color:var(--grey-2);text-align:center;">
    Bugun darslaringiz yo'q.
  </div>
@else
  <div style="display:flex;flex-direction:column;gap:10px;">
    @foreach($todayLessons as $l)
      <a href="{{ route('teacher.journal.edit', [$l->group_id, $l->subject_id]) }}"
         style="background:var(--bg-1);border:1px solid var(--line);border-radius:12px;
                padding:16px 20px;display:grid;grid-template-columns:90px 1fr auto;
                gap:16px;align-items:center;text-decoration:none;color:inherit;
                transition:all .15s;"
         onmouseover="this.style.borderColor='rgba(91,140,255,0.5)';this.style.transform='translateY(-2px)'"
         onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.transform='translateY(0)'">

        <div>
          <div style="color:var(--grey-2);font-size:12px;font-weight:600;">
            {{ $l->period->number }}-para
          </div>
          <div style="color:var(--grey-2);font-size:11px;margin-top:2px;">
            {{ \Carbon\Carbon::parse($l->period->start_time)->format('H:i') }}
          </div>
        </div>

        <div>
          <div style="font-weight:700;font-size:15px;">{{ $l->subject->name }}</div>
          <div style="color:var(--grey-2);font-size:12.5px;margin-top:3px;">
            {{ $l->group->name }} · {{ $l->room->name }}
          </div>
        </div>

        <div style="background:rgba(74,222,128,0.15);color:#4ade80;
                    padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;">
          Jurnal →
        </div>
      </a>
    @endforeach
  </div>
@endif

@endsection