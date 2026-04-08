@extends('layout')

@section('content')
    @php
        $publishedAt = !empty($date) ? \Illuminate\Support\Carbon::parse($date)->format('M d, Y') : null;
    @endphp

    <article class="card-warm fade-in-up rounded-3xl p-8 md:p-12">
        @if ($publishedAt)
            <p class="mb-2 text-sm text-brown-soft">{{ $publishedAt }}</p>
        @endif
        <h1 class="title-serif text-5xl font-semibold tracking-tight text-brown">{{ $title }}</h1>

        @if (!empty($excerpt))
            <p class="mt-4 text-lg leading-relaxed text-brown-soft">{{ $excerpt }}</p>
        @endif

        <div class="prose content-prose mt-8 max-w-none prose-p:text-brown-soft prose-headings:text-brown prose-a:text-ochre prose-strong:text-brown">
            {!! \Statamic\Statamic::modify($content ?? '')->markdown() !!}
        </div>
    </article>

    <p class="mt-8">
        <a href="/blog" class="link-warm text-sm font-semibold">← Volver a recursos</a>
    </p>
@endsection
