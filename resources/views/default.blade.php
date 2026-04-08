@extends('layout')

@section('content')
    <article class="card-warm fade-in-up rounded-3xl p-8 md:p-12">
        <p class="eyebrow mb-3">Acompanamiento profesional</p>
        <h1 class="title-serif mb-5 text-5xl font-semibold tracking-tight text-brown">{{ $title }}</h1>

        @if (!empty($hero_subtitle))
            <p class="mb-6 max-w-3xl text-lg leading-relaxed text-brown-soft">{{ $hero_subtitle }}</p>
        @endif

        @if (!empty($content))
            <div class="prose content-prose max-w-none prose-p:text-brown-soft prose-headings:text-brown prose-strong:text-brown prose-a:text-ochre">
                {!! \Statamic\Statamic::modify($content)->markdown() !!}
            </div>
        @endif
    </article>
@endsection
