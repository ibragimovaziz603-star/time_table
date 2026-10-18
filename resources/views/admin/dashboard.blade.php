@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Umumiy statistika')

@section('content')

{{-- TOP STATS CARDS --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; width: 100%;">

  {{-- O'quvchilar --}}
  <div class="stat-card" onclick="selectCard('students', 'O\'quvchilar dinamikasi', this)"
       style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:24px;cursor:pointer;transition:0.2s;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">O'quvchilar</div>
      <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#5b8cff,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
          <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
        </svg>
      </div>
    </div>
    <div id="studentsCount" style="font-family:'Space Grotesk';font-size:38px;font-weight:700;">{{ $stats['students'] ?? 0 }}</div>
  </div>

  {{-- Ustozlar --}}
  <div class="stat-card" onclick="selectCard('teachers', 'Ustozlar faolligi', this)"
       style="background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:24px;cursor:pointer;transition:0.2s;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Ustozlar</div>
      <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#4ade80,#22c55e);display:flex;align-items:center;justify-content:center;color:#fff;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
    </div>
    <div id="teachersCount" style="font-family:'Space Grotesk';font-size:38px;font-weight:700;">{{ $stats['teachers'] ?? 0 }}</div>
  </div>

  {{-- Saytga tashriflar --}}
  <div class="stat-card active-card" id="visitsCard" onclick="selectCard('visits', 'Tashriflar statistikasi', this)"
       style="background:var(--bg-1);border:1px solid #3b82f6;border-radius:var(--radius);padding:24px;cursor:pointer;transition:0.2s;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <div style="color:var(--grey-2);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">Tashriflar</div>
      <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;color:#fff;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      </div>
    </div>
    <div id="visitsCount" style="font-family:'Space Grotesk';font-size:38px;font-weight:700;">{{ number_format($stats['visits'] ?? 0) }}</div>
    <div style="color:var(--grey-2);font-size:11.5px;margin-top:6px;">Saytga kirishlar</div>
  </div>

</div>

{{-- ANALITIKA GRAFIGI --}}
<div style="margin-top:24px;background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);padding:24px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
    <h3 id="chartTitle" style="margin:0;font-size:18px;font-weight:600;">Tashriflar statistikasi</h3>
    <div style="font-size:12px;color:var(--grey-2);display:flex;align-items:center;gap:6px;">
      <span style="display:inline-block;width:8px;height:8px;background:#4ade80;border-radius:50%;animation:pulse 1.5s infinite;"></span>
      Jonli yangilanadi
    </div>
  </div>

  <div style="height: 300px; width: 100%;">
    <canvas id="analyticsChart"></canvas>
  </div>
</div>

{{-- GURUHLAR VA O'QUVCHILAR GORIZONTAL SKROLL PANELI --}}
<div style="margin-top: 24px; background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--radius); padding: 24px;">
  <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">Guruhlar va o'quvchilar davomati</h3>

  {{-- Gorizontal skroll guruhlar --}}
  <div style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 12px; scrollbar-width: thin;">
    @forelse($groups ?? [] as $group)
      <div onclick="loadGroupStudents({{ $group->id }}, '{{ $group->name }}')"
           style="min-width: 160px; background: rgba(255,255,255,0.03); border: 1px solid var(--line); border-radius: 10px; padding: 16px; cursor: pointer; transition: 0.2s; text-align: center; flex-shrink: 0;"
           onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='var(--line)'">
        <div style="font-weight: 600; font-size: 15px; color: #fff;">{{ $group->name }}</div>
        <div style="font-size: 12px; color: var(--grey-2); margin-top: 4px;">Kurs: {{ $group->course ?? 1 }}</div>
      </div>
    @empty
      <div style="color: var(--grey-2); font-size: 13px;">Guruhlar topilmadi.</div>
    @endforelse
  </div>

  {{-- Tanlangan guruh o'quvchilari chiqadigan joy --}}
  <div id="studentsContainer" style="margin-top: 24px; display: none;">
    <h4 id="selectedGroupName" style="font-size: 16px; color: #3b82f6; margin-bottom: 12px;"></h4>
    <div id="studentsList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px;"></div>
  </div>

  {{-- O'quvchining shaxsiy dashboardi --}}
  <div id="studentDetailModal" style="display: none; margin-top: 30px; background: rgba(0,0,0,0.3); border: 1px solid #3b82f6; border-radius: 12px; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
      <h4 id="studentNameTitle" style="margin: 0; font-size: 18px; color: #fff;"></h4>
      <button onclick="closeStudentDetail()" style="background: none; border: none; color: #aaa; cursor: pointer; font-size: 14px;">✕ Yopish</button>
    </div>
    <div id="studentStatsContent" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;"></div>
  </div>
</div>

{{-- PULSE ANIMATSIYA --}}
<style>
  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%      { opacity: 0.4; transform: scale(1.4); }
  }
</style>

{{-- JAVASCRIPT QISMI --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // ── Laravel'dan kelgan ma'lumotlar ──
  const realDates     = @json($chartDates ?? []);
  const realValues    = @json($chartValues ?? []);
  const allGroupsData = @json($groups ?? []);

  // ── Karta turiga qarab grafik ma'lumotlari ──
  const chartDataMap = {
    visits: {
      labels: realDates,
      data: realValues,
      borderColor: '#f59e0b',
      bgColor: 'rgba(245, 158, 11, 0.15)'
    },
    students: {
      labels: realDates,
      data: realValues.map(v => Math.round(v * 0.8)),
      borderColor: '#5b8cff',
      bgColor: 'rgba(91, 140, 255, 0.15)'
    },
    teachers: {
      labels: realDates,
      data: realValues.map(v => Math.round(v * 0.2)),
      borderColor: '#22c55e',
      bgColor: 'rgba(34, 197, 94, 0.15)'
    }
  };

  let myChart;
  let currentType = 'visits';

  // ── Grafikni chizish ──
  function renderChart(type) {
    currentType = type;
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    const selected = chartDataMap[type] || chartDataMap.visits;

    if (myChart) myChart.destroy();

    myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: selected.labels,
        datasets: [{
          label: 'Soni',
          data: selected.data,
          borderColor: selected.borderColor,
          backgroundColor: selected.bgColor,
          fill: true,
          tension: 0.4,
          borderWidth: 3,
          pointRadius: 5,
          pointHoverRadius: 9,
          pointBackgroundColor: selected.borderColor,
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1000,
          easing: 'easeOutQuart'
        },
        interaction: {
          intersect: false,
          mode: 'index'
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.85)',
            titleColor: '#fff',
            bodyColor: '#e5e7eb',
            padding: 12,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Qiymat: ' + context.parsed.y;
              }
            }
          }
        },
        scales: {
          x: {
            grid:  { color: 'rgba(255, 255, 255, 0.05)' },
            ticks: { color: '#888', font: { size: 11 } }
          },
          y: {
            beginAtZero: true,
            grid:  { color: 'rgba(255, 255, 255, 0.05)' },
            ticks: {
              color: '#888',
              font: { size: 11 },
              precision: 0,
              stepSize: 1
            }
          }
        }
      }
    });
  }

  // ── Kartochkani tanlash ──
  function selectCard(type, title, el) {
    document.getElementById('chartTitle').innerText = title;
    document.querySelectorAll('.stat-card').forEach(card => {
      card.style.borderColor = 'var(--line)';
    });
    if (el) el.style.borderColor = '#3b82f6';
    renderChart(type);
  }

  // ── Guruh o'quvchilarini yuklash ──
  function loadGroupStudents(groupId, groupName) {
    const container = document.getElementById('studentsContainer');
    const title = document.getElementById('selectedGroupName');
    const list  = document.getElementById('studentsList');

    container.style.display = 'block';
    document.getElementById('studentDetailModal').style.display = 'none';
    title.innerText = groupName + " guruhi o'quvchilari:";

    const group = allGroupsData.find(g => g.id === groupId);
    list.innerHTML = '';

    if (group && group.students && group.students.length > 0) {
      group.students.forEach(student => {
        const fullName = (
          (student.first_name || '') + ' ' + (student.last_name || '')
        ).trim() || student.name || 'Nomsiz';
        list.innerHTML += `
          <div onclick="loadStudentDashboard(${student.id}, '${fullName.replace(/'/g, "\\'")}')"
               style="background: rgba(255,255,255,0.02); border: 1px solid var(--line); border-radius: 8px; padding: 12px; cursor: pointer; transition: 0.2s;"
               onmouseover="this.style.background='rgba(59,130,246,0.1)'"
               onmouseout="this.style.background='rgba(255,255,255,0.02)'">
            <div style="font-weight: 600; color: #fff;">${fullName}</div>
            <div style="font-size: 11px; color: var(--grey-2); margin-top: 2px;">ID: ${student.id}</div>
          </div>
        `;
      });
    } else {
      list.innerHTML = `<div style="color: var(--grey-2); font-size: 13px;">Bu guruhda o'quvchilar topilmadi.</div>`;
    }
  }

  // ── O'quvchi dashboardi ──
  function loadStudentDashboard(studentId, studentFullName) {
    const modal = document.getElementById('studentDetailModal');
    const nameTitle = document.getElementById('studentNameTitle');
    const content = document.getElementById('studentStatsContent');

    modal.style.display = 'block';
    nameTitle.innerText = studentFullName + " — Shaxsiy Davomat va Baholar";

    content.innerHTML = `
      <div style="background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px;">
        <div style="color: var(--grey-2); font-size: 12px;">Kelgan darslari</div>
        <div style="font-size: 22px; font-weight: 700; color: #4ade80; margin-top: 5px;">Faol</div>
      </div>
      <div style="background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px;">
        <div style="color: var(--grey-2); font-size: 12px;">Qoldirgan darslari</div>
        <div style="font-size: 22px; font-weight: 700; color: #f87171; margin-top: 5px;">Tekshirilmoqda</div>
      </div>
      <div style="background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px;">
        <div style="color: var(--grey-2); font-size: 12px;">O'rtacha bahosi</div>
        <div style="font-size: 22px; font-weight: 700; color: #fbbf24; margin-top: 5px;">---</div>
      </div>
    `;

    modal.scrollIntoView({ behavior: 'smooth' });
  }

  function closeStudentDetail() {
    document.getElementById('studentDetailModal').style.display = 'none';
  }

  // ── Real-time yangilanish (har 10 sekundda) ──
  async function refreshChartData() {
    try {
      const res = await fetch("{{ route('admin.chart.data') }}", {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) return;

      const json = await res.json();

      // chartDataMap'ni yangilash
      chartDataMap.visits.labels   = json.labels;
      chartDataMap.visits.data     = json.data;
      chartDataMap.students.labels = json.labels;
      chartDataMap.students.data   = json.data.map(v => Math.round(v * 0.8));
      chartDataMap.teachers.labels = json.labels;
      chartDataMap.teachers.data   = json.data.map(v => Math.round(v * 0.2));

      // Yuqoridagi karta raqamlarini yangilash
      document.getElementById('visitsCount').innerText =
        Number(json.stats.visits).toLocaleString();
      document.getElementById('studentsCount').innerText =
        Number(json.stats.students).toLocaleString();
      document.getElementById('teachersCount').innerText =
        Number(json.stats.teachers).toLocaleString();

      // Joriy ko'rinishdagi grafikni yangilash (silliq animatsiya)
      if (myChart) {
        myChart.data.labels = chartDataMap[currentType].labels;
        myChart.data.datasets[0].data = chartDataMap[currentType].data;
        myChart.update();
      }
    } catch (e) {
      console.warn('Chart yangilashda xatolik:', e);
    }
  }

  // ── Boshlanish ──
  document.addEventListener("DOMContentLoaded", function() {
    renderChart('visits');
    // Real-time: har 10 sekundda yangilanadi
    setInterval(refreshChartData, 10000);
  });
</script>

@endsection