@if ($paginator->hasPages())
  <div style="display:flex;justify-content:center;align-items:center;gap:8px;margin-top:24px;flex-wrap:wrap;">

    {{-- Oldingi --}}
    @if ($paginator->onFirstPage())
      <span style="padding:8px 14px;background:var(--bg-2);border:1px solid var(--line);
                   border-radius:8px;color:var(--grey-2);font-size:13px;">
        ← Oldingi
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}"
         style="padding:8px 14px;background:var(--bg-2);border:1px solid var(--line);
                border-radius:8px;color:#fff;font-size:13px;text-decoration:none;font-weight:600;">
        ← Oldingi
      </a>
    @endif

    {{-- Sahifa raqamlari --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span style="color:var(--grey-2);padding:8px;">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span style="padding:8px 14px;background:var(--white);color:#0a0a0c;
                         border-radius:8px;font-weight:700;font-size:13px;">
              {{ $page }}
            </span>
          @else
            <a href="{{ $url }}"
               style="padding:8px 14px;background:var(--bg-2);border:1px solid var(--line);
                      border-radius:8px;color:var(--grey);font-size:13px;text-decoration:none;font-weight:600;">
              {{ $page }}
            </a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Keyingi --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}"
         style="padding:8px 14px;background:var(--bg-2);border:1px solid var(--line);
                border-radius:8px;color:#fff;font-size:13px;text-decoration:none;font-weight:600;">
        Keyingi →
      </a>
    @else
      <span style="padding:8px 14px;background:var(--bg-2);border:1px solid var(--line);
                   border-radius:8px;color:var(--grey-2);font-size:13px;">
        Keyingi →
      </span>
    @endif

  </div>
@endif