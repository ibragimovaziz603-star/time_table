<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', "O'qituvchi") — TTPU Jadval</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#0a0a0c;--bg-1:#141419;--bg-2:#1e1e26;
    --line:rgba(255,255,255,0.08);--white:#fff;--grey:#c7c7cf;--grey-2:#8a8a95;
    --accent:#5b8cff;--good:#4ade80;--warn:#fbbf24;--bad:#f87171;--radius:12px;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  html,body{height:100%;}
  body{font-family:'Manrope',system-ui,sans-serif;background:var(--bg);color:var(--white);display:flex;min-height:100vh;}
  h1,h2,h3,h4{font-family:'Space Grotesk',sans-serif;}
  a{color:var(--accent);text-decoration:none;}

  .sidebar{width:230px;flex-shrink:0;background:var(--bg-1);border-right:1px solid var(--line);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;}
  .sidebar-brand{padding:22px 20px;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:12px;}
  .brand-mark{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#5b8cff,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-family:'Space Grotesk';font-size:17px;}
  .brand-name{font-weight:700;font-size:14px;}
  .brand-sub{font-size:10.5px;color:var(--grey-2);margin-top:1px;}

  .sidebar-nav{padding:14px 10px;display:flex;flex-direction:column;gap:3px;flex:1;}
  .nav-item{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:9px;color:var(--grey);font-size:13.5px;font-weight:600;transition:background .12s ease,color .12s ease;}
  .nav-item:hover{background:rgba(255,255,255,0.05);color:var(--white);}
  .nav-item.active{background:var(--white);color:#0a0a0c;}
  .nav-item svg{width:17px;height:17px;flex-shrink:0;}

  .sidebar-bottom{padding:14px 10px;border-top:1px solid var(--line);}
  .logout-btn{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:9px;background:none;border:none;color:var(--grey);font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;text-align:left;width:100%;}
  .logout-btn:hover{background:rgba(248,113,113,0.1);color:var(--bad);}
  .logout-btn svg{width:17px;height:17px;}

  .main{flex:1;display:flex;flex-direction:column;min-width:0;}
  .topbar{background:rgba(10,10,12,0.85);backdrop-filter:blur(16px);border-bottom:1px solid var(--line);padding:16px 28px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:5;}
  .page-title{font-size:18px;font-weight:600;}
  .page-title small{display:block;color:var(--grey-2);font-size:12px;font-weight:400;margin-top:2px;}
  .user-info{display:flex;align-items:center;gap:12px;font-size:13px;color:var(--grey);}
  .user-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#5b8cff,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;}

  .content{padding:28px;flex:1;}

  .alert{padding:12px 18px;border-radius:var(--radius);margin-bottom:20px;font-size:13.5px;}
  .alert.success{background:rgba(74,222,128,.12);color:var(--good);border:1px solid rgba(74,222,128,.3);}
  .alert.error{background:rgba(248,113,113,.12);color:var(--bad);border:1px solid rgba(248,113,113,.3);}

  .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;background:var(--white);color:#0a0a0c;font-weight:700;font-size:13px;border:none;cursor:pointer;font-family:inherit;text-decoration:none;}
  .btn:hover{opacity:.88;}
  .btn-secondary{background:var(--bg-2);color:var(--white);border:1px solid var(--line);}

  table{width:100%;border-collapse:collapse;font-size:13.5px;background:var(--bg-1);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;}
  th,td{padding:12px 16px;text-align:left;border-bottom:1px solid var(--line);}
  th{background:rgba(255,255,255,.03);font-size:11.5px;color:var(--grey-2);text-transform:uppercase;letter-spacing:.4px;font-weight:600;}

  .grid-wrap{border:1px solid var(--line);border-radius:var(--radius);background:rgba(20,20,25,0.55);padding:16px;overflow-x:auto;}
</style>
</head>
<body>

<aside class="sidebar">
  <a href="{{ route('teacher.dashboard') }}" class="sidebar-brand" style="text-decoration:none;color:inherit;">
    <div class="brand-mark">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</div>
    <div>
      <div class="brand-name">{{ auth()->user()->last_name }}</div>
      <div class="brand-sub">O'qituvchi paneli</div>
    </div>
  </a>

  <nav class="sidebar-nav">
    <a href="{{ route('teacher.my-schedule') }}"
       class="nav-item {{ request()->routeIs('teacher.my-schedule') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
      </svg>
      Dars jadvalim
    </a>

    <a href="{{ route('teacher.journal') }}"
       class="nav-item {{ request()->routeIs('teacher.journal*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
      </svg>
      Jurnal
    </a>
  </nav>

  <div class="sidebar-bottom">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
      <h1 class="page-title">@yield('page-title', 'Panel')</h1>
      @hasSection('page-subtitle')
        <small>@yield('page-subtitle')</small>
      @endif
    </div>
    <div class="user-info">
      <span>{{ auth()->user()->last_name }} {{ auth()->user()->first_name }}</span>
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</div>
    </div>
  </div>

  <div class="content">
    @if(session('ok'))
      <div class="alert success">{{ session('ok') }}</div>
    @endif

    @if($errors->any())
      <div class="alert error">
        <ul style="margin-left:18px;">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </div>
</div>

</body>
</html>