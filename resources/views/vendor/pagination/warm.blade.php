@if ($paginator->hasPages())
    <nav class="mt-12 flex flex-wrap items-center justify-center gap-2" role="navigation" aria-label="Paginacion">
        @if ($paginator->onFirstPage())
            <span class="rounded-2xl bg-white/50 px-4 py-2 text-sm text-brown-soft/50">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-medium text-brown-soft shadow-[0_2px_12px_rgba(90,63,42,0.06)] transition hover:bg-white hover:text-brown">Anterior</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-brown-soft/50">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="rounded-2xl bg-[rgba(193,138,59,0.2)] px-4 py-2 text-sm font-semibold text-brown" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-medium text-brown-soft shadow-[0_2px_12px_rgba(90,63,42,0.06)] transition hover:bg-white hover:text-brown">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-medium text-brown-soft shadow-[0_2px_12px_rgba(90,63,42,0.06)] transition hover:bg-white hover:text-brown">Siguiente</a>
        @else
            <span class="rounded-2xl bg-white/50 px-4 py-2 text-sm text-brown-soft/50">Siguiente</span>
        @endif
    </nav>
@endif
