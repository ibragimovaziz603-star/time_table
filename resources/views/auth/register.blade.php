<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Ro'yxatdan o'tish — TTPU Jadval</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    background: #0a0a0c; color: #fff; font-family: 'Manrope', system-ui, sans-serif;
    padding: 20px; position: relative;
  }
  body::before {
    content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background:
      radial-gradient(circle at 20% 20%, rgba(91, 140, 255, 0.15), transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.12), transparent 50%);
    z-index: -1;
  }
  .box {
    background: rgba(20, 20, 25, 0.85); backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px;
    padding: 42px; width: 100%; max-width: 440px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
  }
  .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 26px; justify-content: center; }
  .brand-mark {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, #fff, #8890a8);
    display: flex; align-items: center; justify-content: center;
    color: #0a0a0c; font-weight: 700; font-size: 20px; font-family: 'Space Grotesk';
  }
  .brand-name { font-weight: 700; font-size: 16px; }
  .brand-sub { font-size: 11.5px; color: #c7c7cf; margin-top: 1px; }
  h1 { font-family: 'Space Grotesk'; font-size: 24px; font-weight: 700; text-align: center; margin-bottom: 8px; }
  .sub { text-align: center; color: #c7c7cf; font-size: 13px; margin-bottom: 26px; }
  .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .field { margin-bottom: 16px; }
  .field label { display: block; font-size: 12px; color: #c7c7cf; margin-bottom: 7px; font-weight: 600; }
  .field input {
    width: 100%; background: rgba(30, 30, 38, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 11px;
    color: #fff; padding: 12px 14px; font-size: 14px; font-family: inherit;
    transition: border-color .15s ease, box-shadow .15s ease;
  }
  .field input:focus { outline: none; border-color: #5b8cff; box-shadow: 0 0 0 3px rgba(91, 140, 255, 0.15); }
  .field input::placeholder { color: #5a5a66; }
  .err {
    background: rgba(248, 113, 113, 0.12); color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
    padding: 12px 16px; border-radius: 11px; font-size: 13px; margin-bottom: 20px;
  }
  .err ul { margin-left: 18px; margin-top: 4px; }
  .btn {
    width: 100%; padding: 14px; border: none; border-radius: 11px;
    background: #fff; color: #0a0a0c; font-weight: 700; font-size: 14.5px;
    cursor: pointer; font-family: inherit; transition: opacity .15s; margin-top: 6px;
  }
  .btn:hover { opacity: .9; }
  .link { display: block; text-align: center; margin-top: 22px; color: #8a8a95; font-size: 13px; }
  .link a { color: #5b8cff; text-decoration: none; font-weight: 600; }
  .link a:hover { text-decoration: underline; }

  /* Parol ko'rish */
  .password-wrapper { position: relative; }
  .toggle-btn {
    position: absolute; top: 50%; right: 12px; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #c7c7cf; padding: 4px;
    display: flex; align-items: center; justify-content: center;
  }
  .toggle-btn:hover { color: #fff; }
</style>
</head>
<body>

<form class="box" method="POST" action="{{ route('register') }}">
  @csrf

  <div class="brand">
    <div class="brand-mark">T</div>
    <div>
      <div class="brand-name">TTPU Jadval</div>
      <div class="brand-sub">Ro'yxatdan o'tish</div>
    </div>
  </div>

  <h1>Ro'yxatdan o'tish</h1>
  <p class="sub">Yangi hisob yarating</p>

  @if($errors->any())
    <div class="err">
      <ul>
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="row">
    <div class="field">
      <label>Ism *</label>
      <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Aziz" required autofocus>
    </div>
    <div class="field">
      <label>Familiya *</label>
      <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Yusupov" required>
    </div>
  </div>

  <div class="field">
    <label>Email *</label>
    <input type="email" name="email" value="{{ old('email') }}" placeholder="aziz@ttpu.uz" required>
  </div>

  <div class="row">
    <div class="field">
      <label>Parol *</label>
      <div class="password-wrapper">
        <input type="password" name="password" id="reg-password" placeholder="••••••••" required style="padding-right:44px;">
        <button type="button" class="toggle-btn" onclick="togglePasswordVisibility('reg-password', this)">
          <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
          <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
          </svg>
        </button>
      </div>
    </div>
    <div class="field">
      <label>Parolni tasdiqlang *</label>
      <div class="password-wrapper">
        <input type="password" name="password_confirmation" id="reg-password2" placeholder="••••••••" required style="padding-right:44px;">
        <button type="button" class="toggle-btn" onclick="togglePasswordVisibility('reg-password2', this)">
          <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
          <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <button type="submit" class="btn">Ro'yxatdan o'tish</button>

  <div class="link">
    Hisobingiz bormi? <a href="{{ route('login') }}">Kirish</a>
  </div>
</form>

<script>
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  const eyeOpen = btn.querySelector('.eye-open');
  const eyeClosed = btn.querySelector('.eye-closed');

  if (input.type === 'password') {
    input.type = 'text';
    eyeOpen.style.display = 'none';
    eyeClosed.style.display = 'block';
  } else {
    input.type = 'password';
    eyeOpen.style.display = 'block';
    eyeClosed.style.display = 'none';
  }
}
</script>

</body>
</html>