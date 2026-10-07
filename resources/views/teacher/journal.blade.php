@extends('layouts.starosta')

@section('title', $group->name . ' — Jurnal')
@section('page-title', $group->name . ' — Jurnal')
@section('page-subtitle', ($group->course ? $group->course . '-kurs · ' : '') . $students->count() . ' talaba')

@section('content')

<style>
  select.attendance-select {
    background: var(--bg-2);
    border: 1px solid var(--line);
    border-radius: 8px;
    color: #fff;
    padding: 8px 12px;
    font-family: inherit;
    font-size: 13px;
    cursor: pointer;
    min-width: 100px;
    transition: all .15s;
  }
  select.attendance-select[data-status="b"] {
    background: rgba(74,222,128,0.15) !important;
    border-color: rgba(74,222,128,0.5) !important;
    color: #4ade80 !important;
    font-weight: 700;
  }
  select.attendance-select[data-status="s"] {
    background: rgba(251,191,36,0.15) !important;
    border-color: rgba(251,191,36,0.5) !important;
    color: #fbbf24 !important;
    font-weight: 700;
  }
  select.attendance-select[data-status="nb"] {
    background: rgba(248,113,113,0.15) !important;
    border-color: rgba(248,113,113,0.5) !important;
    color: #f87171 !important;
    font-weight: 700;
  }

  .row-marked {
    animation: rowHighlight .6s ease;
  }
  @keyframes rowHighlight {
    0%   { background: rgba(91,140,255,0.2); }
    100% { background: transparent; }
  }
</style>

@if($todaySchedules->count() > 0)
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
              padding:16px 20px;margin-bottom:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;margin-bottom:8px;">
      Bugungi darslar ({{ $todayName }})
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      @foreach($todaySchedules as $s)
        <span style="background:rgba(91,140,255,0.15);color:#5b8cff;padding:6px 14px;
                     border-radius:999px;font-size:13px;font-weight:600;">
          {{ $s->period->number }}. {{ $s->subject->name }}
        </span>
      @endforeach
    </div>
  </div>
@else
  <div style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.3);
              border-radius:var(--radius);padding:16px 20px;margin-bottom:20px;
              color:#fbbf24;font-size:13.5px;">
    ⚠️ Bugun ({{ $todayName }}) dars yo'q
  </div>
@endif

<form method="POST" action="{{ route('starosta.journal.save') }}">
  @csrf

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px;min-width:700px;">
      <thead>
        <tr style="background:rgba(255,255,255,0.03);">
          <th style="padding:14px 16px;text-align:left;font-size:11.5px;color:var(--grey-2);
                     text-transform:uppercase;letter-spacing:.4px;font-weight:600;width:50px;">#</th>
          <th style="padding:14px 16px;text-align:left;font-size:11.5px;color:var(--grey-2);
                     text-transform:uppercase;letter-spacing:.4px;font-weight:600;">ISM</th>
          @foreach($todaySchedules as $s)
            <th style="padding:14px 16px;text-align:center;font-size:11.5px;color:var(--grey-2);
                       text-transform:uppercase;letter-spacing:.4px;font-weight:600;">
              {{ $s->subject->name }}<br>
              <span style="font-size:10px;opacity:.6;">({{ $s->period->number }}-para)</span>
            </th>
          @endforeach
          <th style="padding:14px 16px;text-align:center;font-size:11.5px;color:var(--grey-2);
                     text-transform:uppercase;letter-spacing:.4px;font-weight:600;width:80px;">QALAM</th>
        </tr>
      </thead>
      <tbody>
        @foreach($students as $i => $student)
          <tr class="student-row" style="border-top:1px solid var(--line);">
            <td style="padding:12px 16px;color:var(--grey-2);">{{ $i + 1 }}</td>
            <td style="padding:12px 16px;font-weight:600;">
              {{ $student->last_name }} {{ $student->first_name }}
            </td>
            @foreach($todaySchedules as $s)
              @php
                $key = $student->id . '_' . $s->id;
                $status = $attendances[$key] ?? '';
              @endphp
              <td style="padding:8px 12px;text-align:center;">
                <select name="attendance[{{ $student->id }}][{{ $s->id }}]"
                        class="attendance-select"
                        data-status="{{ $status }}">
                  <option value="">—</option>
                  <option value="b" @selected($status === 'b')>B — Bor</option>
                  <option value="s" @selected($status === 's')>S — Sababli</option>
                  <option value="nb" @selected($status === 'nb')>NB — Yo'q</option>
                </select>
              </td>
            @endforeach
            <td style="padding:8px 12px;text-align:center;">
              <button type="button"
                      class="mark-btn"
                      onclick="markRow(this)"
                      title="Qatorni belgilash"
                      style="background:rgba(91,140,255,0.15);color:#5b8cff;
                             border:1px solid rgba(91,140,255,0.3);border-radius:8px;
                             padding:8px 12px;cursor:pointer;font-family:inherit;
                             transition: all .15s;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     style="vertical-align:middle;pointer-events:none;">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px;">
    <button type="submit" style="background:#fff;color:#0a0a0c;border:none;
            border-radius:10px;padding:12px 24px;font-weight:700;font-size:13.5px;
            cursor:pointer;font-family:inherit;">
      💾 Hammasini saqlash
    </button>
  </div>
</form>

@endsection

@section('scripts')
<script>
  console.log('Scripts loaded!');

  // Rangli select
  document.querySelectorAll('select.attendance-select').forEach(select => {
    select.addEventListener('change', function () {
      this.dataset.status = this.value;
    });
  });

  // Qalam tugmasi
  window.markRow = function(btn) {
    console.log('Qalam bosildi!');
    const row = btn.closest('tr');
    if (!row) return;

    row.classList.remove('row-marked');
    void row.offsetWidth;
    row.classList.add('row-marked');

    btn.style.background = 'rgba(91,140,255,0.35)';
    btn.style.color = '#fff';
    btn.style.borderColor = 'rgba(91,140,255,0.6)';

    setTimeout(() => {
      btn.style.background = 'rgba(91,140,255,0.15)';
      btn.style.color = '#5b8cff';
      btn.style.borderColor = 'rgba(91,140,255,0.3)';
    }, 1000);
  };
</script>
@endsection