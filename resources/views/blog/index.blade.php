@extends('layout')

@php
    use App\Support\BlogImage;
@endphp

@section('content')
    <section class="fade-in-up mb-12 md:mb-14">
        <p class="eyebrow mb-3">Biblioteca de bienestar</p>
        <h1 class="title-serif text-4xl font-semibold tracking-tight text-brown md:text-5xl">{{ $title ?? 'Blog' }}</h1>
        <p class="mt-4 max-w-3xl text-lg text-brown-soft">{{ $content ?? 'Articulos y guias para fortalecer tu bienestar emocional.' }}</p>
    </section>

    @if ($posts->isEmpty())
        <p class="rounded-2xl bg-[#fbf3e8]/80 p-5 text-brown-soft shadow-[0_4px_24px_rgba(90,63,42,0.05)]">Aun no hay articulos. Publica el primero desde el panel de Statamic.</p>
    @else
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                @php
                    $imgUrl = BlogImage::cardUrl($post);
                    $published = $post->date();
                @endphp
                <article class="card-warm group flex flex-col overflow-hidden rounded-2xl fade-in-up">
                    <a href="{{ $post->url() }}" class="relative block aspect-[16/10] overflow-hidden bg-gradient-to-br from-[#e9d2b3]/35 to-[#d8b389]/25">
                        @if ($imgUrl)
                            <img src="{{ $imgUrl }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy" decoding="async">
                        @endif
                    </a>
                    <div class="flex flex-1 flex-col p-6 pt-5">
                        @if ($published)
                            <p class="mb-2 text-xs font-medium uppercase tracking-wider text-brown-soft/80">{{ $published->format('d M Y') }}</p>
                        @endif
                        <h2 class="title-serif text-xl font-semibold leading-snug text-brown md:text-2xl">
                            <a class="link-warm" href="{{ $post->url() }}">{{ $post->get('title') }}</a>
                        </h2>
                        <p class="content-prose mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-brown-soft">{{ $post->get('excerpt') }}</p>
                        <a href="{{ $post->url() }}" class="mt-4 inline-flex text-sm font-semibold text-ochre transition group-hover:text-ochre-dark">Leer articulo</a>
                    </div>
                </article>
            @endforeach
        </div>

        {{ $posts->links('vendor.pagination.warm') }}
    @endif
@endsection
