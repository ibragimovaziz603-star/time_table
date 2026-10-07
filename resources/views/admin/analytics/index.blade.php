@extends('layouts.admin')

@section('title', 'Analitika')
@section('page-title', 'Analitika')
@section('page-subtitle', 'Universitet statistikasi')

@section('content')

<style>
  /* 3 ta dropdown */
  .analytics-menus {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 28px;
  }
  .analytics-menu {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    overflow: hidden;
    transition: all .3s ease;
  }
  .analytics-menu.open {
    grid-column: span 3;
    box-shadow: 0 20px 60px rgba(0,0,0,0.6);
  }
  .analytics-menu-header {
    padding: 20px 22px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Space Grotesk';
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    user-select: none;
    background: rgba(91,140,255,0.05);
    transition: background .15s;
  }
  .analytics-menu-header:hover {
    background: rgba(91,140,255,0.12);
  }
  .analytics-menu-arrow {
    transition: transform .3s;
    font-size: 14px;
    color: var(--grey-2);
  }
  .analytics-menu.open .analytics-menu-arrow {
    transform: rotate(180deg);
  }
  .analytics-menu-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .4s ease;
  }
  .analytics-menu.open .analytics-menu-body {
    max-height: 600px;
    overflow-y: auto;
  }
  .analytics-item {
    padding: 12px 22px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    color: var(--grey);
    border-bottom: 1px solid rgba(255,255,255,0.04);
    transition: all .15s;
  }
  .analytics-item:hover {
    background: rgba(91,140,255,0.08);
    color: #fff;
    padding-left: 28px;
  }
  .analytics-item .badge {
    font-size: 11px;
    color: var(--grey-2);
  }
  .analytics-item.selected {
    background: rgba(91,140,255,0.15);
    color: #fff;
    border-left: 3px solid #5b8cff;
  }
</style>

{{-- 6 ta statistika --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:28px;">
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:18px;">
    <div style="color:var(--grey-2);font-size:11px;text-transform:uppercase;font-weight:600;">Talabalar</div>
    <div style="font-family:'Space Grotesk';font-size:28px;font-weight:700;margin-top:6px;">{{ $stats['students'] }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:18px;">
    <div style="color:var(--grey-2);font-size:11px;text-transform:uppercase;font-weight:600;">O'qituvchilar</div>
    <div style="font-family:'Space Grotesk';font-size:28px;font-weight:700;margin-top:6px;">{{ $stats['teachers'] }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:18px;">
    <div style="color:var(--grey-2);font-size:11px;text-transform:uppercase;font-weight:600;">Guruhlar</div>
    <div style="font-family:'Space Grotesk';font-size:28px;font-weight:700;margin-top:6px;">{{ $stats['groups'] }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:18px;">
    <div style="color:var(--grey-2);font-size:11px;text-transform:uppercase;font-weight:600;">Fanlar</div>
    <div style="font-family:'Space Grotesk';font-size:28px;font-weight:700;margin-top:6px;">{{ $stats['subjects'] }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:18px;">
    <div style="color:var(--grey-2);font-size:11px;text-transform:uppercase;font-weight:600;">Xonalar</div>
    <div style="font-family:'Space Grotesk';font-size:28px;font-weight:700;margin-top:6px;">{{ $stats['rooms'] }}</div>
  </div>
  <div style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:18px;">
    <div style="color:var(--grey-2);font-size:11px;text-transform:uppercase;font-weight:600;">Darslar</div>
    <div style="font-family:'Space Grotesk';font-size:28px;font-weight:700;margin-top:6px;">{{ $stats['schedules'] }}</div>
  </div>
</div>

{{-- 3 ta dropdown menyu --}}
<h2 style="font-family:'Space Grotesk';font-size:16px;margin-bottom:14px;color:var(--grey-2);">
  Batafsil analitika
</h2>

<div class="analytics-menus">

  {{-- 1. USTOZLAR --}}
  <div class="analytics-menu" id="menu-teachers">
    <div class="analytics-menu-header" onclick="toggleMenu('menu-teachers')">
      <span>👨‍🏫 Ustozlar</span>
      <span class="analytics-menu-arrow">▼</span>
    </div>
    <div class="analytics-menu-body">
      @forelse($teachers as $t)
        <a href="{{ route('admin.analytics.teacher', $t) }}" class="analytics-item"
           style="text-decoration:none;">
          <span>{{ $t->last_name }} {{ $t->first_name }}</span>
          <span class="badge">{{ $t->schedules_count }} dars</span>
        </a>
      @empty
        <div class="analytics-item" style="color:var(--grey-2);">Ustozlar yo'q</div>
      @endforelse
    </div>
  </div>

  {{-- 2. GURUHLAR --}}
  <div class="analytics-menu" id="menu-groups">
    <div class="analytics-menu-header" onclick="toggleMenu('menu-groups')">
      <span>👥 Guruhlar</span>
      <span class="analytics-menu-arrow">▼</span>
    </div>
    <div class="analytics-menu-body">
      @php
        $groupsByCourse = $groups->groupBy('course');
      @endphp
      @forelse($groupsByCourse as $course => $courseGroups)
        <div style="padding:10px 22px;font-size:11px;color:var(--grey-2);text-transform:uppercase;font-weight:600;background:rgba(255,255,255,0.02);">
          {{ $course ? $course . '-kurs' : 'Kurs belgilanmagan' }}
        </div>
        @foreach($courseGroups as $g)
          <a href="{{ route('admin.analytics.group', $g) }}" class="analytics-item"
             style="text-decoration:none;">
            <span>{{ $g->name }}</span>
            <span class="badge">{{ $g->students_count }} talaba</span>
          </a>
        @endforeach
      @empty
        <div class="analytics-item" style="color:var(--grey-2);">Guruhlar yo'q</div>
      @endforelse
    </div>
  </div>

  {{-- 3. TALABALAR --}}
  <div class="analytics-menu" id="menu-students">
    <div class="analytics-menu-header" onclick="toggleMenu('menu-students')">
      <span>🎓 Talabalar</span>
      <span class="analytics-menu-arrow">▼</span>
    </div>
    <div class="analytics-menu-body">
      @php
        $studentsByCourse = [];
        foreach ($students as $s) {
            $course = $s->groups->first()?->course ?? 0;
            $studentsByCourse[$course][] = $s;
        }
        ksort($studentsByCourse);
      @endphp
      @forelse($studentsByCourse as $course => $courseStudents)
        <div style="padding:10px 22px;font-size:11px;color:var(--grey-2);text-transform:uppercase;font-weight:600;background:rgba(255,255,255,0.02);">
          {{ $course ? $course . '-kurs' : 'Kurs belgilanmagan' }}
        </div>
        @foreach($courseStudents as $s)
          <div class="analytics-item">
            <span>{{ $s->last_name }} {{ $s->first_name }}</span>
            <span class="badge">{{ $s->groups->first()?->name ?? '—' }}</span>
          </div>
        @endforeach
      @empty
        <div class="analytics-item" style="color:var(--grey-2);">Talabalar yo'q</div>
      @endforelse
    </div>
  </div>

</div>

<script>
function toggleMenu(id) {
  const menu = document.getElementById(id);
  const isOpen = menu.classList.contains('open');
  
  // Barcha menyularni yopish
  document.querySelectorAll('.analytics-menu').forEach(m => m.classList.remove('open'));
  
  // Agar yopiq bo'lsa — ochish
  if (!isOpen) {
    menu.classList.add('open');
  }
}
</script>

@endsection