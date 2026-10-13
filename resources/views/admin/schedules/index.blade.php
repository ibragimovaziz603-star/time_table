@extends('layouts.admin')

@section('title', 'Jadval')
@section('page-title', 'Dars jadvali')
@section('page-subtitle', 'Guruhni tanlang — katakchani ✏️ bilan tahrirlang')

@section('content')

{{-- Guruh tanlash + Yangi dars --}}
<div style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
  <form method="GET" style="display:flex;gap:10px;align-items:center;">
    <label style="font-size:12px;color:var(--grey-2);">Guruh:</label>
    <select name="group_id" onchange="this.form.submit()"
            style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;
                   color:var(--white);padding:10px 14px;font-size:13.5px;font-family:inherit;min-width:180px;">
      @foreach($groups as $g)
        <option value="{{ $g->id }}" @selected($selectedGroup && $selectedGroup->id == $g->id)>
          {{ $g->name }}
        </option>
      @endforeach
    </select>
  </form>

  <a href="{{ route('admin.schedules.create', ['group_id' => $selectedGroup?->id]) }}" class="btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="5" x2="12" y2="19"></line>
      <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    Yangi dars
  </a>
</div>

@if(!$selectedGroup)
  <div class="alert error">Guruh tanlanmagan.</div>
@else

  {{-- Jadval --}}
  <div style="border:1px solid var(--line);border-radius:var(--radius);background:var(--bg-1);padding:16px;overflow-x:auto;">
    <table style="min-width:900px;border-collapse:separate;border-spacing:6px;background:transparent;">
      <thead>
        <tr>
          <th style="width:50px;background:transparent;border:none;padding:6px;"></th>
          @foreach($periods as $p)
            <th style="background:transparent;border:none;text-align:center;padding:6px 4px;font-size:11px;color:var(--grey-2);text-transform:none;letter-spacing:0;">
              <span style="display:block;font-family:'Space Grotesk';font-size:14px;color:#fff;font-weight:700;">{{ $p->number }}.</span>
              {{ \Carbon\Carbon::parse($p->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->end_time)->format('H:i') }}
            </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($days as $day)
          <tr>
            <td style="border:none;text-align:center;font-family:'Space Grotesk';font-weight:700;font-size:13px;color:#fff;background:transparent;">
              {{ $day->short }}
            </td>

            @foreach($periods as $p)
              @php
                $lesson = $grid[$day->short][$p->number] ?? null;
              @endphp

              <td style="vertical-align:top;padding:0;background:transparent;border:none;width:110px;">

                @if($lesson)
                  {{-- Dars bloki + ✏️ qalam --}}
                  <div style="position:relative;border-radius:10px;padding:8px 9px;min-height:70px;
                              background:rgba(91,140,255,0.12);border:1px solid rgba(91,140,255,0.35);">

                    {{-- Tahrirlash tugmasi (qalam) --}}
                    <a href="{{ route('admin.schedules.edit', $lesson) }}"
                       title="Tahrirlash"
                       style="position:absolute;top:4px;right:4px;width:22px;height:22px;
                              border-radius:6px;background:rgba(255,255,255,0.1);color:#fff;
                              display:flex;align-items:center;justify-content:center;
                              text-decoration:none;transition:background .15s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                    </a>

                    {{-- O'qituvchi (null bo'lsa — '—') --}}
                    <div style="font-size:10px;font-weight:700;color:#fff;opacity:.85;padding-right:22px;">
                      {{ $lesson->teacher ? $lesson->teacher->last_name : '—' }}
                    </div>

                    {{-- Fan (null bo'lsa — '—') --}}
                    <div style="font-size:11.5px;font-weight:700;color:#fff;margin:2px 0;line-height:1.25;">
                      {{ $lesson->subject?->name ?? '—' }}
                    </div>

                    {{-- Xona (null bo'lsa — '—') --}}
                    <div style="font-size:10px;color:rgba(255,255,255,0.7);">
                      {{ $lesson->room?->name ?? '—' }}
                    </div>
                  </div>

                @else
                  {{-- Bo'sh katak — ✏️ qalam bilan --}}
                  <a href="{{ route('admin.schedules.create', [
                        'group_id'  => $selectedGroup->id,
                        'day_id'    => $day->id,
                        'period_id' => $p->id,
                     ]) }}"
                     title="Dars qo'shish"
                     style="display:flex;align-items:center;justify-content:center;
                            min-height:70px;border-radius:10px;
                            border:1px dashed rgba(255,255,255,0.08);
                            color:rgba(255,255,255,0.15);text-decoration:none;
                            transition:all .15s;"
                     onmouseover="this.style.borderColor='rgba(91,140,255,0.5)';this.style.color='#5b8cff';this.style.background='rgba(91,140,255,0.05)'"
                     onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.15)';this.style.background='transparent'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                @endif

              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

@endif

@endsection