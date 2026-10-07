@extends('layouts.admin')

@section('title', 'Xonalar')
@section('page-title', 'Xonalar')
@section('page-subtitle', 'Universitet xonalari boshqaruvi')

@section('content')

  <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
    <form method="GET" style="display:flex;gap:10px;align-items:center;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Xona nomi bo'yicha qidirish..." style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;
                    color:#fff;padding:10px 14px;font-size:13.5px;font-family:inherit;
                    min-width:280px;outline:none;">
      <button class="btn btn-secondary" type="submit">Qidirish</button>
      @if(request('search'))
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Tozalash</a>
      @endif
    </form>

    <a href="{{ route('admin.rooms.create') }}" class="btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
        stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      Yangi xona
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:50px;">#</th>
        <th>Xona nomi</th>
        <th style="width:150px;">Darslar soni</th>
        <th style="width:200px;">Amallar</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rooms as $r)
        <tr>
          <td style="color:var(--grey-2);">{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>
          <td>
            <a href="{{ route('admin.rooms.show', $r) }}" style="color:#fff;font-weight:600;text-decoration:none;">
              {{ $r->name }}
            </a>
          </td>
          <td style="color:var(--grey-2);">
            <span
              style="background:rgba(91,140,255,0.15);color:#5b8cff;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;">
              {{ $r->schedules_count }} dars
            </span>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('admin.rooms.show', $r) }}" class="btn btn-sm btn-secondary" title="Jadvalni ko'rish">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                  stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </a>
              <a href="{{ route('admin.rooms.edit', $r) }}" class="btn btn-sm btn-secondary" title="Tahrirlash">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </a>
              <form method="POST" action="{{ route('admin.rooms.destroy', $r) }}" style="display:inline;"
                onsubmit="return confirm('{{ $r->name }} o‘chirilsinmi?\n\nDiqqat: bu xona bilan bog‘liq BARCHA darslar ham o‘chadi!');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" title="O'chirish">
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
          <td colspan="4" style="text-align:center;color:var(--grey-2);padding:30px;">Xonalar topilmadi.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:20px;">{{ $rooms->links('vendor.pagination.custom') }}</div>

@endsection