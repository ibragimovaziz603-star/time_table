<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — TTPU Jadval</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a0c;
            --bg-1: #141419;
            --bg-2: #1e1e26;
            --line: rgba(255, 255, 255, 0.08);
            --white: #fff;
            --grey: #c7c7cf;
            --grey-2: #8a8a95;
            --accent: #5b8cff;
            --accent-soft: rgba(91, 140, 255, 0.15);
            --good: #4ade80;
            --warn: #fbbf24;
            --bad: #f87171;
            --radius: 12px;
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
            font-family: 'Manrope', system-ui, sans-serif;
            background: var(--bg);
            color: var(--white);
            display: flex;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Space Grotesk', sans-serif;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--bg-1);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar-brand {
            padding: 22px 20px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #fff, #8890a8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a0a0c;
            font-weight: 700;
            font-family: 'Space Grotesk';
            font-size: 17px;
        }

        .brand-name {
            font-weight: 700;
            font-size: 14px;
            letter-spacing: .2px;
        }

        .brand-sub {
            font-size: 10.5px;
            color: var(--grey-2);
            margin-top: 1px;
        }

        .sidebar-nav {
            padding: 14px 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 9px;
            color: var(--grey);
            font-size: 13.5px;
            font-weight: 600;
            transition: background .12s ease, color .12s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--white);
            text-decoration: none;
        }

        .nav-item.active {
            background: var(--white);
            color: #0a0a0c;
        }

        .nav-item svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .sidebar-bottom {
            padding: 14px 10px;
            border-top: 1px solid var(--line);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 9px;
            background: none;
            border: none;
            color: var(--grey);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
            width: 100%;
            transition: background .12s ease, color .12s ease;
        }

        .logout-btn:hover {
            background: rgba(248, 113, 113, 0.1);
            color: var(--bad);
        }

        .logout-btn svg {
            width: 17px;
            height: 17px;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            background: rgba(10, 10, 12, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--line);
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
        }

        .page-title small {
            display: block;
            color: var(--grey-2);
            font-size: 12px;
            font-weight: 400;
            margin-top: 2px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: var(--grey);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5b8cff, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        .alert {
            padding: 12px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: rgba(74, 222, 128, .12);
            color: var(--good);
            border: 1px solid rgba(74, 222, 128, .3);
        }

        .alert.error {
            background: rgba(248, 113, 113, .12);
            color: var(--bad);
            border: 1px solid rgba(248, 113, 113, .3);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 9px;
            background: var(--white);
            color: #0a0a0c;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: opacity .15s ease;
            text-decoration: none;
        }

        .btn:hover {
            opacity: .88;
            text-decoration: none;
            color: #0a0a0c;
        }

        .btn svg {
            width: 15px;
            height: 15px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-secondary {
            background: var(--bg-2);
            color: var(--white);
            border: 1px solid var(--line);
        }

        .btn-secondary:hover {
            color: var(--white);
            background: rgba(255, 255, 255, .08);
        }

        .btn-danger {
            background: rgba(248, 113, 113, .15);
            color: var(--bad);
        }

        .btn-danger:hover {
            background: rgba(248, 113, 113, .25);
            color: var(--bad);
        }

        .btn-icon {
            padding: 6px;
            border-radius: 8px;
            background: var(--bg-2);
            color: var(--grey);
            border: 1px solid var(--line);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .15s ease;
        }

        .btn-icon:hover {
            color: var(--white);
            background: rgba(255, 255, 255, .08);
        }

        .btn-icon svg {
            width: 15px;
            height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            background: var(--bg-1);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--line);
        }

        th {
            background: rgba(255, 255, 255, .03);
            font-size: 11.5px;
            color: var(--grey-2);
            text-transform: uppercase;
            letter-spacing: .4px;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            max-width: 900px;
        }

        .field label {
            display: block;
            font-size: 12px;
            color: var(--grey);
            margin-bottom: 6px;
            font-weight: 600;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            background: var(--bg-2);
            border: 1px solid var(--line);
            border-radius: 10px;
            color: var(--white);
            padding: 11px 13px;
            font-size: 13.5px;
            font-family: inherit;
            transition: border-color .15s ease;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .sidebar-brand:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .field.full {
            grid-column: 1 / -1;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <a href="{{ route('timetable') }}" class="sidebar-brand" style="text-decoration:none;color:inherit;">
            <div class="brand-mark">T</div>
            <div>
                <div class="brand-name">TTPU Jadval</div>
                <div class="brand-sub">Admin panel</div>
            </div>
        </a>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.schedules.index') }}"
                class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Jadval
            </a>
            <a href="{{ route('admin.teachers.index') }}"
                class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                O'qituvchilar
            </a>
            <a href="{{ route('admin.starostas.index') }}"
                class="nav-item {{ request()->routeIs('admin.starostas.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                    </path>
                </svg>
                Starostalar
            </a>
            <a href="{{ route('admin.groups.index') }}"
                class="nav-item {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Guruhlar
            </a>
            <a href="{{ route('admin.subjects.index') }}"
                class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                Fanlar
            </a>
            <a href="{{ route('admin.rooms.index') }}"
                class="nav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Xonalar
            </a>
        </nav>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Chiqish
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                @hasSection('page-subtitle')
                    <small>@yield('page-subtitle')</small>
                @endif
            </div>
            <div class="user-info">
                <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</div>
            </div>
        </div>

        <div class="content">
            @if(session('ok'))
                <div class="alert success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    {{ session('ok') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        <strong>Xatolar:</strong>
                        <ul style="margin-left:18px;margin-top:4px;">
                            @foreach($errors->all() as $e)
                            <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @yield('scripts')

</body>

</html>