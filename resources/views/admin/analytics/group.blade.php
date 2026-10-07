@extends('layouts.admin')

@section('title', $group->name . ' — analitika')
@section('page-title', $group->name . ' analitikasi')
@section('page-subtitle', 'Baho va statistika')

@section('content')

{{-- Davr filtri --}}
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
  @foreach(['day' => '1 kun', 'week' => '1 hafta', 'month' => '1 oy', 'year' => '1 yil'] as $key => $label)
    <a href="{{ route('admin.analytics.group', ['group' => $group, 'period' => $key]) }}"
       class="btn {{ $period === $key ? '' : 'btn-secondary' }}">
      {{ $label }}
    </a>
  @endforeach
</div>

{{-- Statistika --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin-bottom:28px;">

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Talabalar</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;">{{ $stats['students_count'] }}</div>
  </div>

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">O'rtacha baho</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;color:#4ade80;">{{ $stats['avg_score'] }}</div>
  </div>

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Jami baholar</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;">{{ $stats['total_lessons'] }}</div>
  </div>

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Eng yuqori</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;color:#4ade80;">{{ $stats['max_score'] }}</div>
  </div>

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">Eng past</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;color:#f87171;">{{ $stats['min_score'] }}</div>
  </div>

</div>

{{-- Talabalar jurnali --}}
<h2 style="font-family:'Space Grotesk';font-size:18px;margin-bottom:14px;">Talabalar</h2>
<table>
  <thead>
    <tr>
      <th style="width:50px;">#</th>
      <th>Familiya</th>
      <th>Ism</th>
      <th style="width:120px;">O'rtacha</th>
      <th style="width:120px;">Jami</th>
      <th style="width:120px;">Yig'indi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($studentStats as $i => $st)
      <tr>
        <td style="color:var(--grey-2);">{{ $i + 1 }}</td>
        <td><strong>{{ $st['student']->last_name }}</strong></td>
        <td>{{ $st['student']->first_name }}</td>
        <td>
          <span style="display:inline-block;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;
                background:{{ $st['avg_score'] >= 8 ? 'rgba(74,222,128,0.15)' : ($st['avg_score'] >= 5 ? 'rgba(251,191,36,0.15)' : 'rgba(248,113,113,0.15)') }};
                color:{{ $st['avg_score'] >= 8 ? '#4ade80' : ($st['avg_score'] >= 5 ? '#fbbf24' : '#f87171') }};">
            {{ $st['avg_score'] }}
          </span>
        </td>
        <td style="color:var(--grey-2);">{{ $st['total_lessons'] }}</td>
        <td style="color:var(--grey-2);">{{ $st['total_score'] }}</td>
      </tr>
    @empty
      <tr><td colspan="6" style="text-align:center;color:var(--grey-2);padding:30px;">
        Bu guruhda talabalar yo'q.
      </td></tr>
    @endforelse
  </tbody>
</table>

{{-- Guruh jadvali --}}
@if($schedules->count() > 0)
  <h2 style="font-family:'Space Grotesk';font-size:18px;margin:32px 0 14px;">Guruh jadvali</h2>
  <table>
    <thead>
      <tr><th>Kun</th><th>Davr</th><th>Fan</th><th>O'qituvchi</th><th>Xona</th></tr>
    </thead>
    <tbody>
      @foreach($schedules->sortBy([['day_id', 'asc'], ['period_id', 'asc']]) as $s)
        <tr>
          <td>{{ $s->day->name }}</td>
          <td>{{ $s->period->number }}. ({{ $s->period->start_time }}–{{ $s->period->end_time }})</td>
          <td><strong>{{ $s->subject->name }}</strong></td>
          <td>{{ $s->teacher->last_name }} {{ $s->teacher->first_name }}</td>
          <td style="color:var(--grey-2);">{{ $s->room->name }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

@endsection