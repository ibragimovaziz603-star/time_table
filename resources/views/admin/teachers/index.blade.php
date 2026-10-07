@extends('layouts.admin')

@section('title', "O'qituvchilar")
@section('page-title', "O'qituvchilar")
@section('page-subtitle', 'Fakultet a\'zolari boshqaruvi')

@section('content')

  <style>
    .teachers-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 16px;
    }

    .teacher-card {
      background: var(--bg-1);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 20px;
      transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
      cursor: pointer;
      position: relative;
    }

    .teacher-card:hover {
      transform: translateY(-6px);
      border-color: rgba(91, 140, 255, 0.5);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
    }

    .teacher-avatar {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: linear-gradient(135deg, #5b8cff, #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      font-weight: 700;
      color: #fff;
      font-family: 'Space Grotesk';
      margin-bottom: 14px;
    }

    .teacher-name {
      font-family: 'Space Grotesk';
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 4px;
    }

    .teacher-meta {
      font-size: 12.5px;
      color: var(--grey-2);
      margin-bottom: 2px;
    }

    .teacher-subject {
      display: inline-block;
      margin-top: 10px;
      padding: 3px 10px;
      border-radius: 999px;
      background: rgba(91, 140, 255, 0.15);
      color: #5b8cff;
      font-size: 11px;
      font-weight: 700;
    }

    .teacher-actions {
      position: absolute;
      top: 14px;
      right: 14px;
      display: flex;
      gap: 6px;
      opacity: 0;
      transition: opacity .2s ease;
    }

    .teacher-card:hover .teacher-actions {
      opacity: 1;
    }
  </style>

  {{-- Tepa: qidiruv + Yangi --}}
  <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:22px;flex-wrap:wrap;">
    <form method="GET" style="display:flex;gap:10px;align-items:center;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Ism, familiya, email..." style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;
                      color:#fff;padding:10px 14px;font-size:13.5px;font-family:inherit;
                      min-width:280px;outline:none;">
      <button class="btn btn-secondary" type="submit">Qidirish</button>
      @if(request('search'))
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Tozalash</a>
      @endif
    </form>

    <a href="{{ route('admin.teachers.create') }}" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
        stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      Yangi o'qituvchi
    </a>
  </div>

  {{-- Kartalar --}}
  <div class="teachers-grid">
    @forelse($teachers as $t)
      <div class="teacher-card" onclick="location.href='{{ route('admin.teachers.edit', $t) }}'">
        <div class="teacher-actions" onclick="event.stopPropagation();">
          <a href="{{ route('admin.teachers.edit', $t) }}" class="btn btn-sm btn-secondary" title="Tahrirlash">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </a>
          <form method="POST" action="{{ route('admin.teachers.destroy', $t) }}" style="display:inline;"
            onsubmit="return confirm('{{ $t->last_name }} {{ $t->first_name }} o‘chirilsinmi?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
              </svg>
            </button>
          </form>
        </div>

        <div class="teacher-avatar">{{ strtoupper(substr($t->first_name, 0, 1)) }}</div>
        <div class="teacher-name">{{ $t->last_name }} {{ $t->first_name }}</div>
        <div class="teacher-meta">{{ $t->email }}</div>
        <div class="teacher-meta">{{ $t->phone ?? '—' }}</div>

        {{-- Fanlar --}}
        @if($t->subjects->count() > 0)
          <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px;">
            @foreach($t->subjects as $subj)
              <span class="teacher-subject">{{ $subj->name }}</span>
            @endforeach
          </div>
        @else
          <div class="teacher-subject" style="background:rgba(255,255,255,0.05);color:var(--grey-2);">
            Fan belgilanmagan
          </div>
        @endif
      </div>
    @empty
      <div style="grid-column:1/-1;text-align:center;color:var(--grey-2);padding:40px;">
        O'qituvchilar topilmadi.
      </div>
    @endforelse
  </div>

  <div style="margin-top:24px;">
    {{ $teachers->links('vendor.pagination.custom') }}
  </div>

@endsection