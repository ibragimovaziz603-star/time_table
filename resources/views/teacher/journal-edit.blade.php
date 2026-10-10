@extends('layouts.teacher')

@section('title', $group->name . ' — ' . $subject->name)
@section('page-title', $group->name . ' — ' . $subject->name)
@section('page-subtitle', ($todayDay?->name ?? 'Bugun') . ' · ' . $students->count() . ' talaba')

@section('content')

<style>
  .status-select {
    padding: 8px 12px; border-radius: 8px; border: 1px solid var(--line);
    background: var(--bg-2); color: #fff; font-family: inherit; font-size: 13px;
    cursor: pointer; min-width: 90px; transition: all .15s;
  }
  .status-select[data-status="b"] { background: rgba(74,222,128,0.15); border-color: rgba(74,222,128,0.5); color: #4ade80; font-weight: 700; }
  .status-select[data-status="s"] { background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.5); color: #fbbf24; font-weight: 700; }
  .status-select[data-status="nb"] { background: rgba(248,113,113,0.15); border-color: rgba(248,113,113,0.5); color: #f87171; font-weight: 700; }

  .grade-input {
    width: 70px; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--line);
    background: var(--bg-2); color: #fff; font-family: inherit; font-size: 14px;
    text-align: center; font-weight: 700; transition: all .15s;
  }
  .grade-input:focus { outline: none; border-color: #5b8cff; box-shadow: 0 0 0 3px rgba(91,140,255,0.15); }
</style>

@if($todaySchedules->isEmpty())
  <div style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.3);
              border-radius:var(--radius);padding:20px;color:#fbbf24;font-size:13.5px;">
    ⚠️ Bugun ({{ $todayDay?->name ?? 'Bugun' }}) bu fandan darsingiz yo'q.
  </div>
@else

  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);
              padding:16px 20px;margin-bottom:20px;">
    <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;font-weight:600;margin-bottom:8px;">
      Bugungi {{ $subject->name }} darslari
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      @foreach($todaySchedules as $s)
        <span style="background:rgba(91,140,255,0.15);color:#5b8cff;padding:6px 14px;
                     border-radius:999px;font-size:13px;font-weight:600;">
          {{ $s->period->number }}-para ({{ \Carbon\Carbon::parse($s->period->start_time)->format('H:i') }})
        </span>
      @endforeach
    </div>
  </div>

  <form method="POST" action="{{ route('teacher.journal.save', [$group->id, $subject->id]) }}">
    @csrf

    <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;font-size:13.5px;min-width:900px;">
        <thead>
          <tr style="background:rgba(255,255,255,0.03);">
            <th rowspan="2" style="padding:14px 16px;text-align:left;font-size:11.5px;color:var(--grey-2);
                       text-transform:uppercase;letter-spacing:.4px;font-weight:600;width:50px;">#</th>
            <th rowspan="2" style="padding:14px 16px;text-align:left;font-size:11.5px;color:var(--grey-2);
                       text-transform:uppercase;letter-spacing:.4px;font-weight:600;">ISM</th>
            @foreach($todaySchedules as $s)
              <th colspan="2" style="padding:14px 16px;text-align:center;font-size:11.5px;color:var(--grey-2);
                         text-transform:uppercase;letter-spacing:.4px;font-weight:600;border-left:1px solid var(--line);">
                {{ $s->period->number }}-para
              </th>
            @endforeach
          </tr>
          <tr style="background:rgba(255,255,255,0.02);">
            @foreach($todaySchedules as $s)
              <th style="padding:8px 12px;text-align:center;font-size:10.5px;color:var(--grey-2);font-weight:600;border-left:1px solid var(--line);">
                DAVOMAT
              </th>
              <th style="padding:8px 12px;text-align:center;font-size:10.5px;color:var(--grey-2);font-weight:600;">
                BAHO
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($students as $i => $student)
            <tr style="border-top:1px solid var(--line);">
              <td style="padding:12px 16px;color:var(--grey-2);">{{ $i + 1 }}</td>
              <td style="padding:12px 16px;font-weight:600;">
                {{ $student->last_name }} {{ $student->first_name }}
              </td>
              @foreach($todaySchedules as $s)
                @php
                  $key = $student->id . '_' . $s->id;
                  $att = $todayAttendances[$key] ?? '';
                  $gradeScore = $todayGrades[$key] ?? '';
                @endphp
                <td style="padding:8px 12px;text-align:center;border-left:1px solid var(--line);">
                  <select name="attendance[{{ $student->id }}][{{ $s->id }}]"
                          class="status-select"
                          data-status="{{ $att }}">
                    <option value="">—</option>
                    <option value="b" @selected($att === 'b')>B</option>
                    <option value="s" @selected($att === 's')>S</option>
                    <option value="nb" @selected($att === 'nb')>NB</option>
                  </select>
                </td>
                <td style="padding:8px 12px;text-align:center;">
                  <input type="number"
                         name="grades[{{ $student->id }}][{{ $s->id }}]"
                         value="{{ $gradeScore }}"
                         min="0" max="10"
                         class="grade-input"
                         placeholder="—">
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div style="margin-top:20px;display:flex;gap:10px;">
      <button type="submit" class="btn">
        💾 Saqlash
      </button>
      <a href="{{ route('teacher.journal') }}" class="btn btn-secondary">
        ← Orqaga
      </a>
    </div>
  </form>

@endif

@endsection

@section('scripts')
<script>
  document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function () {
      this.dataset.status = this.value;
    });
  });
</script>
@endsection