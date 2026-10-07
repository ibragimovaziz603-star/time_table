@extends('layouts.teacher')

@section('title', 'Dashboard')
@section('page-title', 'Salom, ' . auth()->user()->first_name . '!')
@section('page-subtitle', 'Bugungi darslaringiz')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:22px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Haftalik darslar</div>
    <div style="font-family:'Space Grotesk';font-size:36px;font-weight:700;margin-top:8px;">{{ $totalSchedules }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:22px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Guruhlar</div>
    <div style="font-family:'Space Grotesk';font-size:36px;font-weight:700;margin-top:8px;">{{ $totalGroups }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:22px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Fanlar</div>
    <div style="font-family:'Space Grotesk';font-size:36px;font-weight:700;margin-top:8px;">{{ $totalSubjects }}</div>
  </div>
</div>

<h2 style="font-size:18px;margin-bottom:14px;">Bugungi darslar</h2>

@if($todayLessons->isEmpty())
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:26px;color:var(--grey-2);text-align:center;">
    Bugun darslaringiz yo'q.
  </div>
@else
  <div style="display:flex;flex-direction:column;gap:10px;">
    @foreach($todayLessons as $l)
      <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:12px;padding:16px 20px;
                  display:grid;grid-template-columns:80px 1fr auto;gap:16px;align-items:center;">
        <div style="color:var(--grey-2);font-size:13px;font-weight:600;">
          {{ \Carbon\Carbon::parse($l->period->start_time)->format('H:i') }}
        </div>
        <div>
          <div style="font-weight:700;font-size:14.5px;">{{ $l->subject->name }}</div>
          <div style="color:var(--grey-2);font-size:12.5px;margin-top:2px;">
            {{ $l->group->name }} · {{ $l->room->name }}
          </div>
        </div>
        <a href="{{ route('teacher.journal.edit', [$l->group, $l->subject]) }}" class="btn btn-secondary">
          Jurnal
        </a>
      </div>
    @endforeach
  </div>
@endif

@endsection