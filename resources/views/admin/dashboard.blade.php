@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Umumiy statistika')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:18px;">

  {{-- O'quvchilar --}}
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">O'quvchilar</div>
      <div style="width:36px;height:36px;border-radius:10px;
                  background:linear-gradient(135deg,#5b8cff,#8b5cf6);
                  display:flex;align-items:center;justify-content:center;color:#fff;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
          <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
        </svg>
      </div>
    </div>
    <div style="font-family:'Space Grotesk';font-size:38px;font-weight:700;">{{ $stats['students'] ?? 0 }}</div>
  </div>

  {{-- Ustozlar --}}
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Ustozlar</div>
      <div style="width:36px;height:36px;border-radius:10px;
                  background:linear-gradient(135deg,#4ade80,#22c55e);
                  display:flex;align-items:center;justify-content:center;color:#fff;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
    </div>
    <div style="font-family:'Space Grotesk';font-size:38px;font-weight:700;">{{ $stats['teachers'] ?? 0 }}</div>
  </div>

  {{-- Saytga tashriflar --}}
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Tashriflar</div>
      <div style="width:36px;height:36px;border-radius:10px;
                  background:linear-gradient(135deg,#fbbf24,#f59e0b);
                  display:flex;align-items:center;justify-content:center;color:#fff;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      </div>
    </div>
    <div style="font-family:'Space Grotesk';font-size:38px;font-weight:700;">{{ number_format($stats['visits'] ?? 0) }}</div>
    <div style="color:var(--grey-2);font-size:11.5px;margin-top:6px;">Saytga kirishlar</div>
  </div>

</div>

@endsection