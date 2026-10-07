<!DOCTYPE html>
<html lang="uz">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadval — TTPU uslubida</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap"
    rel="stylesheet">

  {{-- ============================================================
  1. CSS — Stillar
  ============================================================ --}}
  <style>
    :root {
      --bg-0: #08080a;
      --bg-1: #141419;
      --bg-2: #1e1e26;
      --line: rgba(255, 255, 255, 0.1);
      --white: #ffffff;
      --grey: #c7c7cf;
      --accent: #5b8cff;
      --accent-soft: rgba(91, 140, 255, 0.15);
      --good: #4ade80;
      --warn: #fbbf24;
      --bad: #f87171;
      --radius: 14px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Manrope', sans-serif;
      background: #0a0a0c;
      color: var(--white);
      min-height: 100vh;
    }

    #vanta-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
    }

    .main-content {
      position: relative;
      z-index: 1;
    }

    h1,
    h2,
    h3 {
      font-family: 'Space Grotesk', sans-serif;
    }

    /* ---- TOPBAR ---- */
    .topbar {
      position: sticky;
      top: 0;
      z-index: 10;
      background: rgba(6, 6, 8, 0.82);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--line);
    }

    header {
      display: flex;
      flex-direction: column;
      gap: 16px;
      padding: 18px 40px 22px;
    }

    .nav-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      gap: 16px;
    }

    /* ---- BRAND ---- */
    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand-mark {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      background: linear-gradient(135deg, #fff, #8890a8);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #0a0a0c;
      font-weight: 700;
      font-family: 'Space Grotesk';
    }

    .brand-name {
      font-weight: 600;
      font-size: 15px;
      letter-spacing: 0.2px;
    }

    .brand-sub {
      font-size: 11px;
      color: var(--grey);
    }

    /* ---- NAV ---- */
    nav {
      display: flex;
      gap: 4px;
      background: var(--bg-2);
      padding: 4px;
      border-radius: 999px;
      border: 1px solid var(--line);
    }

    nav button {
      font-family: 'Manrope';
      font-size: 13.5px;
      font-weight: 600;
      color: var(--grey);
      background: none;
      border: none;
      padding: 9px 16px;
      border-radius: 999px;
      cursor: pointer;
      transition: transform .18s ease, background .18s ease, color .18s ease;
    }

    nav button.active {
      background: var(--white);
      color: #0a0a0c;
    }

    nav button:hover:not(.active) {
      color: var(--white);
      transform: translateY(-2px);
    }

    /* ---- AUTH (login/register) ---- */
    .auth-group {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .auth-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 18px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      text-decoration: none;
      transition: all .18s ease;
      cursor: pointer;
      font-family: inherit;
    }

    .auth-btn:hover {
      background: rgba(255, 255, 255, 0.14);
    }

    .auth-btn.primary {
      background: #5b8cff;
      border-color: #5b8cff;
    }

    .auth-btn.primary:hover {
      background: #4a7bef;
    }

    /* ---- USER MENU ---- */
    .user-menu {
      position: relative;
    }

    .user-menu-btn {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 6px 14px 6px 6px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      cursor: pointer;
      font-family: inherit;
      font-size: 13px;
      font-weight: 600;
    }

    .user-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: linear-gradient(135deg, #5b8cff, #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      color: #fff;
    }

    .user-dropdown {
      display: none;
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      min-width: 200px;
      background: #141419;
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 6px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.6);
      z-index: 50;
    }

    .user-dropdown.open {
      display: block;
    }

    .user-dropdown a,
    .user-dropdown button {
      display: block;
      width: 100%;
      text-align: left;
      padding: 10px 14px;
      border-radius: 8px;
      color: #c7c7cf;
      font-size: 13px;
      text-decoration: none;
      background: none;
      border: none;
      cursor: pointer;
      font-family: inherit;
      transition: background .15s;
    }

    .user-dropdown a:hover,
    .user-dropdown button:hover {
      background: rgba(255, 255, 255, 0.06);
      color: #fff;
    }

    .user-dropdown button.logout {
      color: #f87171;
    }

    .user-dropdown button.logout:hover {
      background: rgba(248, 113, 113, 0.12);
      color: #f87171;
    }

    /* ---- MAIN ---- */
    main {
      padding: 40px;
      max-width: 1180px;
      margin: 0 auto;
    }

    .page {
      display: none;
      animation: fade .25s ease;
    }

    .page.active {
      display: block;
    }

    @keyframes fade {
      from {
        opacity: 0;
        transform: translateY(6px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .page-head {
      margin-bottom: 28px;
    }

    .page-head h2 {
      font-size: 26px;
      font-weight: 600;
    }

    .page-head p {
      color: var(--grey);
      font-size: 14px;
      margin-top: 4px;
    }

    /* ---- JADVAL WRAP ---- */
    .grid-wrap {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      overflow: hidden;
      background: rgba(20, 20, 25, 0.55);
      backdrop-filter: blur(14px);
    }

    .loading-msg {
      color: var(--grey);
      font-size: 12.5px;
      padding: 26px;
    }

    /* ---- GURUHLAR/XONALAR layout ---- */
    .groups-layout {
      display: grid;
      grid-template-columns: 210px 1fr;
      gap: 24px;
      align-items: start;
      margin-left: -26px;
    }

    .groups-sidebar {
      display: flex;
      flex-direction: column;
      gap: 2px;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: rgba(20, 20, 25, 0.55);
      backdrop-filter: blur(14px);
      padding: 8px;
      position: sticky;
      top: 230px;
      max-height: calc(100vh - 260px);
      overflow-y: auto;
    }

    .group-item {
      text-align: left;
      background: none;
      border: none;
      color: var(--grey);
      font-family: 'Manrope';
      font-size: 12.5px;
      font-weight: 600;
      padding: 9px 12px;
      border-radius: 8px;
      cursor: pointer;
      transition: background .15s ease, color .15s ease;
      white-space: nowrap;
    }

    .group-item:hover {
      color: var(--white);
      background: rgba(255, 255, 255, 0.05);
    }

    .group-item.active {
      background: var(--white);
      color: #0a0a0c;
    }

    /* ---- PERIOD TABLE ---- */
    .ptable-scroll {
      overflow-x: auto;
    }

    .ptable {
      border-collapse: separate;
      border-spacing: 6px;
      width: 100%;
      min-width: 760px;
    }

    .ptable th {
      font-size: 11px;
      color: var(--grey);
      font-weight: 600;
      text-align: center;
      padding: 6px 4px;
      white-space: nowrap;
    }

    .ptable th .pn {
      display: block;
      font-family: 'Space Grotesk';
      font-size: 13px;
      color: var(--white);
      font-weight: 700;
    }

    .ptable td.day-cell {
      font-family: 'Space Grotesk';
      font-weight: 700;
      font-size: 13px;
      color: var(--white);
      vertical-align: middle;
      text-align: center;
      width: 44px;
    }

    .ptable td.slot {
      vertical-align: top;
      padding: 0;
      width: 110px;
    }

    .slot-empty {
      height: 100%;
      min-height: 44px;
    }

    .lesson-block {
      border-radius: 10px;
      padding: 8px 9px;
      min-height: 60px;
      border: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.05);
    }

    .lesson-block .lt {
      font-size: 10px;
      font-weight: 700;
      color: var(--white);
      opacity: .85;
    }

    .lesson-block .ls {
      font-size: 11.5px;
      font-weight: 700;
      color: var(--white);
      margin: 2px 0;
      line-height: 1.25;
    }

    .lesson-block .lr {
      font-size: 10px;
      color: rgba(255, 255, 255, 0.7);
    }

    /* ---- O'QITUVCHILAR ---- */
    .name-list {
      columns: 3;
      column-gap: 18px;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: rgba(20, 20, 25, 0.55);
      backdrop-filter: blur(14px);
      padding: 20px 24px;
    }

    .name-list .n-item {
      font-size: 13px;
      color: var(--grey);
      padding: 6px 0;
      break-inside: avoid;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    @media (max-width:900px) {
      .name-list {
        columns: 2;
      }
    }

    @media (max-width:560px) {
      .name-list {
        columns: 1;
      }
    }

    /* ---- FANLAR CARD ---- */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 14px;
    }

    .card {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 18px;
      background: rgba(20, 20, 25, 0.55);
      backdrop-filter: blur(14px);
      transition: border-color .2s ease, transform .2s ease;
    }

    .card:hover {
      border-color: rgba(255, 255, 255, 0.2);
      transform: translateY(-4px);
    }

    .card .tag {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.5);
      font-weight: 600;
      letter-spacing: .3px;
      margin-bottom: 8px;
    }

    .card h3 {
      font-size: 16px;
      font-weight: 600;
    }

    .card .info {
      color: var(--grey);
      font-size: 12.5px;
      margin-top: 6px;
    }

    /* ---- RESPONSIVE ---- */
    @media (max-width:760px) {
      .groups-layout {
        grid-template-columns: 1fr;
        margin-left: 0;
      }

      .groups-sidebar {
        position: static;
        flex-direction: row;
        overflow-x: auto;
        max-height: none;
      }

      header {
        padding: 14px 20px 18px;
      }

      main {
        padding: 24px 20px;
      }
    }
  </style>
</head>

<body>

  <div id="vanta-canvas"></div>
  <div class="main-content">

    {{-- ============================================================
    2. HTML — Topbar
    ============================================================ --}}
    <div class="topbar">
      <header>

        {{-- Brand --}}
        <div class="brand">
          <div class="brand-mark">T</div>
          <div>
            <div class="brand-name">TTPU Jadval</div>
            <div class="brand-sub">Dars jadvali va natijalar</div>
          </div>
        </div>

        {{-- Nav + Auth --}}
        <div class="nav-row">

          <nav>
            <button class="active" data-page="classes">Guruhlar</button>
            <button data-page="teachers">O'qituvchilar</button>
            <button data-page="classrooms">Xonalar</button>
            <button data-page="subjects">Fanlar</button>
            <button data-page="summary">Umumiy jadval</button>
            <button data-page="results">Natijalar</button>
          </nav>

          <div class="auth-group">
            @auth
              {{-- ADMIN --}}
              @if(auth()->user()->role_id === 1)
                <a href="{{ route('admin.dashboard') }}" class="auth-btn primary">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                  </svg>
                  Admin panel
                </a>
              @endif

              {{-- O'QITUVCHI --}}
              @if(auth()->user()->role_id === 2)
                <a href="{{ route('teacher.my-schedule') }}" class="auth-btn primary">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                  </svg>
                  Mening jadvalim
                </a>
              @endif

              {{-- STAROSTA (role_id = 3 + starostaGroups) --}}
              @if(auth()->user()->role_id === 3 && auth()->user()->isStarosta())
                <a href="{{ route('starosta.journal') }}" class="auth-btn primary">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                  </svg>
                  Jurnal
                </a>
              @endif

              {{-- Avatar dropdown --}}
              <div class="user-menu">
                <button type="button" class="user-menu-btn" onclick="toggleUserMenu(event)">
                  <span class="user-avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</span>
                  {{ auth()->user()->first_name }}
                </button>
                <div class="user-dropdown">
                  @if(auth()->user()->role_id === 1)
                    <a href="{{ route('admin.dashboard') }}">Admin panel</a>
                  @endif
                  @if(auth()->user()->role_id === 2)
                    <a href="{{ route('teacher.dashboard') }}">O'qituvchi paneli</a>
                    <a href="{{ route('teacher.journal') }}">Jurnal</a>
                  @endif
                  @if(auth()->user()->role_id === 3 && auth()->user()->isStarosta())
                    <a href="{{ route('starosta.journal') }}">Jurnal (starosta)</a>
                  @endif
                  <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout">Chiqish</button>
                  </form>
                </div>
              </div>
            @else
              <a href="{{ route('register') }}" class="auth-btn">Ro'yxatdan o'tish</a>
              <a href="{{ route('login') }}" class="auth-btn primary">Kirish</a>
            @endauth
          </div>

        </div>

      </header>
    </div>

    {{-- ============================================================
    3. HTML — Sahifalar
    ============================================================ --}}
    <main>

      {{-- GURUHLAR --}}
      <div class="page active" id="classes">
        <div class="page-head">
          <h2>Guruhlar</h2>
          <p>Chapdan guruhni tanlang — o'ng tomonda o'sha guruhning haftalik dars jadvali ko'rinadi</p>
        </div>
        <div class="groups-layout">
          <aside class="groups-sidebar" id="groupsSidebar"></aside>
          <div id="groupTimetable"></div>
        </div>
      </div>

      {{-- O'QITUVCHILAR --}}
      <div class="page" id="teachers">
        <div class="page-head">
          <h2>O'qituvchilar</h2>
          <p>Fakultet a'zolari — to'liq ro'yxat</p>
        </div>
        <div class="name-list" id="teachersList"></div>
      </div>

      {{-- XONALAR --}}
      <div class="page" id="classrooms">
        <div class="page-head">
          <h2>Xonalar</h2>
          <p>Chapdan xonani tanlang — o'ng tomonda o'sha xonaning band-bo'sh jadvali ko'rinadi</p>
        </div>
        <div class="groups-layout">
          <aside class="groups-sidebar" id="roomsSidebar"></aside>
          <div id="roomTimetable"></div>
        </div>
      </div>

      {{-- FANLAR --}}
      <div class="page" id="subjects">
        <div class="page-head">
          <h2>Fanlar</h2>
          <p>Semestr davomidagi barcha fanlar</p>
        </div>
        <div class="cards" id="subjectsCards"></div>
      </div>

      {{-- UMUMIY JADVAL --}}
      <div class="page" id="summary">
        <div class="page-head">
          <h2>Umumiy jadval</h2>
          <p>Haftalik to'liq ko'rinish</p>
        </div>
        <div class="grid-wrap" style="padding:26px;">
          <p style="color:var(--grey);font-size:13px;">Tez kunda ishga tushadi.</p>
        </div>
      </div>

      {{-- NATIJALAR --}}
      <div class="page" id="results">
        <div class="page-head">
          <h2>Natijalar</h2>
          <p>Ism va familiyangizni kiriting</p>
        </div>
        <div class="grid-wrap" style="padding:26px;">
          <p style="color:var(--grey);font-size:13px;">Tez kunda ishga tushadi.</p>
        </div>
      </div>

    </main>

  </div>

  {{-- ============================================================
  4. JS — Blade'dan ma'lumotlarni o'tkazish
  ============================================================ --}}
  <script>
    window.TTPU = {
      groups: @json($groups->map(fn($g) => ['id' => $g->id, 'name' => $g->name])),
      rooms: @json($rooms->map(fn($r) => ['id' => $r->id, 'name' => $r->name])),
      subjects: @json($subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name])),
      teachers: @json($teachers->map(fn($t) => ['id' => $t->id, 'last_name' => $t->last_name, 'first_name' => $t->first_name])),
    };
  </script>

  {{-- ============================================================
  5. JS — Vanta fon
  ============================================================ --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  <script>
    VANTA.WAVES({
      el: "#vanta-canvas",
      mouseControls: true, touchControls: true, gyroControls: false,
      minHeight: 200.00, minWidth: 200.00,
      scale: 1.00, scaleMobile: 1.00,
      color: 0x111111, waveSpeed: 0.25, zoom: 0.75
    });
  </script>

  {{-- ============================================================
  6. JS — Asosiy mantiq
  ============================================================ --}}
  <script>
    // ============ KONSTANTALAR ============
    const periods = [
      { n: 1, time: '9:00-10:20' },
      { n: 2, time: '10:30-11:50' },
      { n: 3, time: '12:00-13:20' },
      { n: 4, time: '14:20-15:40' },
      { n: 5, time: '15:50-17:10' },
      { n: 6, time: '17:20-18:40' },
      { n: 7, time: '18:50-20:10' },
      { n: 8, time: '20:20-21:40' }
    ];
    const weekDays = ['Du', 'Se', 'Chor', 'Pay', 'Ju', 'Shan'];

    // ============ NAVIGATION ============
    document.querySelectorAll('nav button').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('nav button').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.page).classList.add('active');
      });
    });

    // ============ USER DROPDOWN ============
    function toggleUserMenu(e) {
      e.stopPropagation();
      const menu = e.currentTarget.nextElementSibling;
      document.querySelectorAll('.user-dropdown').forEach(m => {
        if (m !== menu) m.classList.remove('open');
      });
      menu.classList.toggle('open');
    }
    document.addEventListener('click', () => {
      document.querySelectorAll('.user-dropdown.open').forEach(m => m.classList.remove('open'));
    });

    // ============ FAN RANGI ============
    function subjColor(name) {
      const map = [
        ['MATH', '91,140,255'], ['CHEM', '244,114,182'], ['PHY', '74,222,128'],
        ['ENG', '192,132,252'], ['CMEEES', '167,139,250'], ['DRW', '45,212,191'],
        ['NHU', '134,239,172'], ['PHS', '110,231,183']
      ];
      for (const [key, rgb] of map) {
        if (name.toUpperCase().includes(key)) return rgb;
      }
      return '255,255,255';
    }

    // ============ GRID RENDER ============
    function renderPeriodGrid(schedule, emptyMsg) {
      const hasData = schedule && Object.keys(schedule).length > 0;
      if (!hasData) {
        return `<div class="grid-wrap"><div class="loading-msg">${emptyMsg}</div></div>`;
      }

      let html = `<div class="grid-wrap" style="padding:16px"><div class="ptable-scroll"><table class="ptable"><thead><tr><th></th>`;
      periods.forEach(p => { html += `<th><span class="pn">${p.n}.</span>${p.time}</th>`; });
      html += `</tr></thead><tbody>`;

      weekDays.forEach(day => {
        html += `<tr><td class="day-cell">${day}</td>`;
        periods.forEach(p => {
          const l = schedule[day] && schedule[day][p.n];
          if (l) {
            const rgb = subjColor(l.s);
            html += `<td class="slot"><div class="lesson-block" style="background:rgba(${rgb},0.14);border-color:rgba(${rgb},0.35)">
            <div class="lt">${l.t}</div>
            <div class="ls">${l.s}</div>
            <div class="lr">${l.r}</div>
          </div></td>`;
          } else {
            html += `<td class="slot"><div class="slot-empty"></div></td>`;
          }
        });
        html += `</tr>`;
      });

      html += `</tbody></table></div></div>`;
      return html;
    }

    // ============ GURUHLAR ============
    async function renderGroupTimetable(name) {
      const head = `<div class="page-head" style="margin-bottom:16px"><h2 style="font-size:20px">${name}</h2><p>Haftalik dars jadvali</p></div>`;
      const box = document.getElementById('groupTimetable');

      box.innerHTML = head + `<div class="grid-wrap"><div class="loading-msg">Yuklanmoqda...</div></div>`;

      try {
        const group = window.TTPU.groups.find(g => g.name === name);
        if (!group) {
          box.innerHTML = head + renderPeriodGrid(null, 'Guruh topilmadi.');
          return;
        }

        const res = await fetch(`/api/group/${group.id}`);
        const data = await res.json();

        box.innerHTML = head + renderPeriodGrid(data.schedule, "Bu guruh uchun jadval hali kiritilmagan.");
      } catch (e) {
        console.error(e);
        box.innerHTML = head + renderPeriodGrid(null, 'Xato yuz berdi.');
      }
    }

    function renderGroupsSidebar() {
      const sidebar = document.getElementById('groupsSidebar');
      sidebar.innerHTML = '';

      window.TTPU.groups.forEach((g, i) => {
        const btn = document.createElement('button');
        btn.className = 'group-item' + (i === 0 ? ' active' : '');
        btn.textContent = g.name;
        btn.addEventListener('click', () => {
          document.querySelectorAll('#groupsSidebar .group-item').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          renderGroupTimetable(g.name);
        });
        sidebar.appendChild(btn);
      });

      if (window.TTPU.groups.length > 0) {
        renderGroupTimetable(window.TTPU.groups[0].name);
      }
    }

    // ============ XONALAR ============
    async function renderRoomTimetable(name) {
      const head = `<div class="page-head" style="margin-bottom:16px"><h2 style="font-size:20px">${name}</h2><p>Xonaning band-bo'sh jadvali</p></div>`;
      const box = document.getElementById('roomTimetable');

      box.innerHTML = head + `<div class="grid-wrap"><div class="loading-msg">Yuklanmoqda...</div></div>`;

      try {
        const room = window.TTPU.rooms.find(r => r.name === name);
        if (!room) {
          box.innerHTML = head + renderPeriodGrid(null, 'Xona topilmadi.');
          return;
        }

        const res = await fetch(`/api/room/${room.id}`);
        const data = await res.json();

        box.innerHTML = head + renderPeriodGrid(data.schedule, "Bu xona uchun jadval hali kiritilmagan.");
      } catch (e) {
        console.error(e);
        box.innerHTML = head + renderPeriodGrid(null, 'Xato yuz berdi.');
      }
    }

    function renderRoomsSidebar() {
      const sidebar = document.getElementById('roomsSidebar');
      sidebar.innerHTML = '';

      window.TTPU.rooms.forEach((r, i) => {
        const btn = document.createElement('button');
        btn.className = 'group-item' + (i === 0 ? ' active' : '');
        btn.textContent = r.name;
        btn.addEventListener('click', () => {
          document.querySelectorAll('#roomsSidebar .group-item').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          renderRoomTimetable(r.name);
        });
        sidebar.appendChild(btn);
      });

      if (window.TTPU.rooms.length > 0) {
        renderRoomTimetable(window.TTPU.rooms[0].name);
      }
    }

    // ============ FANLAR ============
    function renderSubjectsCards() {
      const box = document.getElementById('subjectsCards');
      box.innerHTML = window.TTPU.subjects.map(s =>
        `<div class="card"><div class="tag">FAN</div><h3>${s.name}</h3></div>`
      ).join('');
    }

    // ============ O'QITUVCHILAR ============
    function renderTeachersList() {
      const box = document.getElementById('teachersList');
      box.innerHTML = window.TTPU.teachers.map(t =>
        `<div class="n-item">${t.last_name} ${t.first_name}</div>`
      ).join('');
    }

    // ============ INIT ============
    renderGroupsSidebar();
    renderRoomsSidebar();
    renderSubjectsCards();
    renderTeachersList();
  </script>

</body>

</html>