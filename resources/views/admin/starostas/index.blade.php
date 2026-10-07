@extends('layouts.admin')

@section('title', 'Starostalar')
@section('page-title', 'Starostalar')
@section('page-subtitle', 'Guruh starostalari boshqaruvi')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
  <form method="GET" style="display:flex;gap:10px;align-items:center;">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Ism, familiya, email..."
           style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;
                  color:#fff;padding:10px 14px;font-size:13.5px;font-family:inherit;
                  min-width:280px;outline:none;">
    <button class="btn btn-secondary" type="submit">Qidirish</button>
    @if(request('search'))
      <a href="{{ route('admin.starostas.index') }}" class="btn btn-secondary">Tozalash</a>
    @endif
  </form>

  <a href="{{ route('admin.starostas.create') }}" class="btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="5" x2="12" y2="19"></line>
      <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    Yangi starosta
  </a>
</div>

<table>
  <thead>
    <tr>
      <th style="width:50px;">#</th>
      <th>Familiya</th>
      <th>Ism</th>
      <th>Email</th>
      <th>Telefon</th>
      <th style="width:140px;">Guruh</th>
      <th style="width:160px;">Amallar</th>
    </tr>
  </thead>
  <tbody>
    @forelse($starostas as $s)
      <tr>
        <td style="color:var(--grey-2);">{{ $loop->iteration + ($starostas->currentPage() - 1) * $starostas->perPage() }}</td>
        <td><strong>{{ $s->last_name }}</strong></td>
        <td>{{ $s->first_name }}</td>
        <td style="color:var(--grey);">{{ $s->email }}</td>
        <td style="color:var(--grey-2);">{{ $s->phone ?? '—' }}</td>
        <td>
          @if($s->starostaGroups->first())
            <span style="background:rgba(251,191,36,0.15);color:#fbbf24;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;">
              {{ $s->starostaGroups->first()->name }}
            </span>
          @else
            <span style="color:var(--grey-2);font-size:11.5px;">—</span>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.starostas.edit', $s) }}" class="btn btn-sm btn-secondary" title="Tahrirlash">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </a>
            <form method="POST" action="{{ route('admin.starostas.destroy', $s) }}" style="display:inline;"
                  onsubmit="return confirm('{{ $s->last_name }} {{ $s->first_name }} o‘chirilsinmi?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                </svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="7" style="text-align:center;color:var(--grey-2);padding:30px;">Starostalar topilmadi.</td></tr>
    @endforelse
  </tbody>
</table>

<div style="margin-top:20px;">{{ $starostas->links('vendor.pagination.custom') }}</div>

@endsection