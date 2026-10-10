@extends('layouts.admin')

@section('title', 'Guruhlar')
@section('page-title', 'Guruhlar')
@section('page-subtitle', 'Universitet guruhlari boshqaruvi')

@section('content')

  <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
    <form method="GET" style="display:flex;gap:10px;align-items:center;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Guruh nomi..." style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;
                    color:#fff;padding:10px 14px;font-size:13.5px;font-family:inherit;
                    min-width:280px;outline:none;">
      <button class="btn btn-secondary" type="submit">Qidirish</button>
      @if(request('search'))
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Tozalash</a>
      @endif
    </form>

    <a href="{{ route('admin.groups.create') }}" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
        stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      Yangi guruh
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:50px;">#</th>
        <th>Guruh nomi</th>
        <th style="width:120px;">Kurs</th>
        <th style="width:120px;">Talabalar</th>
        <th style="width:120px;">Darslar</th>
        <th style="width:140px;">Qamallar</th>
      </tr>
    </thead>
    <tbody>
      @forelse($groups as $g)
        <tr>
          <td style="color:var(--grey-2);">{{ $loop->iteration + ($groups->currentPage() - 1) * $groups->perPage() }}</td>
          <td><strong>{{ $g->name }}</strong></td>
          <td>
            @if($g->course)
              <span
                style="background:rgba(91,140,255,0.15);color:#5b8cff;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;">
                {{ $g->course }}-kurs
              </span>
            @else
              <span style="color:var(--grey-2);font-size:11.5px;">—</span>
            @endif
          </td>
          <td style="color:var(--grey-2);">{{ $g->students_count }}</td>
          <td style="color:var(--grey-2);">{{ $g->schedules_count }}</td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('admin.groups.edit', $g) }}" class="btn btn-sm btn-secondary" title="Tahrirlash">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </a>
              <form method="POST" action="{{ route('admin.groups.destroy', $g) }}" style="display:inline;"
                onsubmit="return confirm('{{ $g->name }} o‘chirilsinmi?\n\nDiqqat: bu guruhning darslari ham o‘chadi!');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                  </svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align:center;color:var(--grey-2);padding:30px;">Guruhlar topilmadi.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:20px;">{{ $groups->links('vendor.pagination.custom') }}</div>

@endsection