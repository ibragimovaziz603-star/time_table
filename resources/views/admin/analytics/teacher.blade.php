@extends('layouts.admin')

@section('title', $teacher->last_name . ' — analitika')
@section('page-title', $teacher->last_name . ' ' . $teacher->first_name)
@section('page-subtitle', 'O\'qituvchi analitikasi')

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

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;">O'rtacha baho</div>
    <div style="font-family:'Space Grotesk';font-size:32px;font-weight:700;margin-top:6px;color:#4ade80;">{{ $stats['avg_score'] }}</div>
  </div>

</div>

{{-- Guruhlar --}}
<h2 style="font-family:'Space Grotesk';font-size:18px;margin-bottom:14px;">Guruhlari</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-bottom:28px;">
  @forelse($groups as $g)
    <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:16px;">
      <div style="font-family:'Space Grotesk';font-size:15px;font-weight:700;">{{ $g->name }}</div>
    </div>
  @empty
    <div style="color:var(--grey-2);">Guruhlar yo'q</div>
  @endforelse
</div>

{{-- Fanlar --}}
<h2 style="font-family:'Space Grotesk';font-size:18px;margin-bottom:14px;">Fanlari</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;">
  @forelse($subjects as $s)
    <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:16px;">
      <div style="font-family:'Space Grotesk';font-size:15px;font-weight:700;">{{ $s->name }}</div>
    </div>
  @empty
    <div style="color:var(--grey-2);">Fanlar yo'q</div>
  @endforelse
</div>

@endsection