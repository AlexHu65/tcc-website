@extends('layout')

@section('content')
    <article class="prose prose-slate max-w-none">
        <h1>{{ $title }}</h1>

        @if (!empty($hero_subtitle))
            <p class="lead">{{ $hero_subtitle }}</p>
        @endif

        @if (!empty($content))
            {!! \Statamic\Statamic::modify($content)->markdown() !!}
        @endif
    </article>
@endsection
