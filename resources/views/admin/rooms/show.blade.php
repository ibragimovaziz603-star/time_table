@extends('layouts.admin')

@section('title', $room->name)
@section('page-title', $room->name . ' — xona jadvali')
@section('page-subtitle', 'Katakchani ✏️ bilan tahrirlang')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;">
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
    ← Orqaga
  </a>

  <a href="{{ route('admin.schedules.create') }}" class="btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="5" x2="12" y2="19"></line>
      <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    Yangi dars
  </a>
</div>

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
            @php $lesson = $grid[$day->short][$p->number] ?? null; @endphp
            <td style="vertical-align:top;padding:0;background:transparent;border:none;width:110px;">
              @if($lesson)
                <div style="position:relative;border-radius:10px;padding:8px 9px;min-height:70px;
                            background:rgba(91,140,255,0.12);border:1px solid rgba(91,140,255,0.35);">
                  <a href="{{ route('admin.schedules.edit', $lesson) }}"
                     title="Tahrirlash"
                     style="position:absolute;top:4px;right:4px;width:22px;height:22px;
                            border-radius:6px;background:rgba(255,255,255,0.1);color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            text-decoration:none;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>

                  <div style="font-size:10px;font-weight:700;color:#fff;opacity:.85;padding-right:22px;">
                    {{ $lesson->subject->name }}
                  </div>
                  <div style="font-size:11.5px;font-weight:700;color:#fff;margin:2px 0;line-height:1.25;">
                    {{ $lesson->group->name }}
                  </div>
                  <div style="font-size:10px;color:rgba(255,255,255,0.7);">
                    {{ $lesson->teacher->last_name }}
                  </div>
                </div>
              @else
                <div style="min-height:70px;"></div>
              @endif
            </td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection 