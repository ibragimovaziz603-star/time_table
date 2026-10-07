@extends('layouts.admin')

@php($isEdit = $teacher->exists)

@section('title', $isEdit ? "O'qituvchini tahrirlash" : "Yangi o'qituvchi")
@section('page-title', $isEdit ? "O'qituvchini tahrirlash" : "Yangi o'qituvchi qo'shish")
@section('page-subtitle', $isEdit ? $teacher->last_name . ' ' . $teacher->first_name : 'Barcha maydonlarni to‘ldiring')

@section('content')

<form method="POST" action="{{ $isEdit ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="form-grid">

    <div class="field">
      <label>Familiya *</label>
      <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}"
             placeholder="Yusupov" required autofocus>
    </div>

    <div class="field">
      <label>Ism *</label>
      <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}"
             placeholder="Aziz" required>
    </div>

    <div class="field">
      <label>Otasining ismi</label>
      <input type="text" name="middle_name" value="{{ old('middle_name', $teacher->middle_name) }}"
             placeholder="Baxtiyorovich">
    </div>

    <div class="field">
      <label>Email *</label>
      <input type="email" name="email" value="{{ old('email', $teacher->email) }}"
             placeholder="aziz@ttpu.uz" required>
    </div>

    <div class="field">
      <label>Telefon</label>
      <input type="text" name="phone" id="phone-input"
             value="{{ old('phone', $teacher->phone) }}"
             placeholder="93 - 999 - 99 - 99"
             maxlength="17" inputmode="numeric">
    </div>

    <div class="field">
      <label>Parol {{ $isEdit ? '(o‘zgartirmasangiz bo‘sh qoldiring)' : '*' }}</label>
      <div style="position:relative;">
        <input type="password" name="password" id="password-input"
               placeholder="{{ $isEdit ? '••••••••' : 'kamida 6 belgi' }}"
               style="padding-right:44px;"
               {{ $isEdit ? '' : 'required' }}>
        <button type="button" id="toggle-password"
                onclick="togglePassword()"
                style="position:absolute;top:50%;right:12px;transform:translateY(-50%);
                       background:none;border:none;cursor:pointer;color:var(--grey-2);
                       padding:4px;display:flex;align-items:center;justify-content:center;"
                title="Parolni ko'rsatish">
          <svg id="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
          <svg id="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               style="display:none;">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
          </svg>
        </button>
      </div>
    </div>

    <div class="field full">
      <label>Fanlar</label>
      <div style="display:flex;flex-wrap:wrap;gap:8px;padding:12px;
                  background:var(--bg-2);border:1px solid var(--line);border-radius:10px;">
        @foreach(\App\Models\Subject::orderBy('name')->get() as $s)
          <label style="display:flex;align-items:center;gap:8px;padding:8px 14px;
                        background:var(--bg-1);border:1px solid var(--line);border-radius:999px;
                        cursor:pointer;font-size:13px;user-select:none;transition:all .15s;">
            <input type="checkbox" name="subjects[]" value="{{ $s->id }}"
                   @checked(in_array($s->id, old('subjects', $isEdit ? $teacher->subjects->pluck('id')->toArray() : [])))
                   style="accent-color:#5b8cff;cursor:pointer;">
            {{ $s->name }}
          </label>
        @endforeach
      </div>
      <div style="font-size:11px;color:var(--grey-2);margin-top:6px;">
        Bir nechta fanni tanlashingiz mumkin
      </div>
    </div>

  </div>

  <div style="display:flex;gap:10px;margin-top:26px;">
    <button type="submit" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      {{ $isEdit ? 'Yangilash' : 'Saqlash' }}
    </button>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Bekor qilish</a>
  </div>
</form>

<script>
  // Parolni ko'rsatish / yashirish
  function togglePassword() {
    const input = document.getElementById('password-input');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

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

  // Telefon maskasi: 93 - 999 - 99 - 99
  const phoneInput = document.getElementById('phone-input');
  if (phoneInput) {
    if (phoneInput.value) phoneInput.value = formatPhone(phoneInput.value);

    phoneInput.addEventListener('input', (e) => {
      e.target.value = formatPhone(e.target.value);
    });

    function formatPhone(value) {
      let digits = value.replace(/\D/g, '');
      if (digits.startsWith('998')) digits = digits.slice(3);
      digits = digits.slice(0, 9);

      const parts = [];
      if (digits.length > 0) parts.push(digits.slice(0, 2));
      if (digits.length > 2) parts.push(digits.slice(2, 5));
      if (digits.length > 5) parts.push(digits.slice(5, 7));
      if (digits.length > 7) parts.push(digits.slice(7, 9));
      return parts.join(' - ');
    }
  }
</script>

@endsection