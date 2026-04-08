@extends('layout')

@section('content')
    <section class="fade-in-up mb-12">
        <p class="eyebrow mb-3">Biblioteca de bienestar</p>
        <h1 class="title-serif text-5xl font-semibold tracking-tight text-brown">{{ $title ?? 'Blog' }}</h1>
        <p class="mt-4 max-w-3xl text-lg text-brown-soft">{{ $content ?? 'Articulos y guias para fortalecer tu bienestar emocional.' }}</p>
    </section>

    @php
        $posts = \Statamic\Facades\Entry::query()
            ->where('collection', 'blog')
            ->orderByDesc('date')
            ->get();
    @endphp

    <section class="space-y-5">
        @forelse ($posts as $post)
            <article class="card-warm fade-in-up rounded-2xl p-7">
                <p class="mb-2 text-sm text-brown-soft">{{ optional($post->date())->format('M d, Y') }}</p>
                <h2 class="title-serif text-3xl font-semibold text-brown">
                    <a class="link-warm" href="{{ $post->url() }}">{{ $post->get('title') }}</a>
                </h2>
                <p class="content-prose mt-3 text-brown-soft">{{ $post->get('excerpt') }}</p>
            </article>
        @empty
            <p class="rounded-xl border border-[#dec4a3] bg-[#fbf3e8] p-4 text-[#795336]">Aun no hay articulos. Publica el primero desde el panel de Statamic.</p>
        @endforelse
    </section>
@endsection
