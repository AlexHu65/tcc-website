@extends('layout')

@section('content')
    @php
        $publishedAt = !empty($date) ? \Illuminate\Support\Carbon::parse($date)->format('M d, Y') : null;
    @endphp

    <article class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        @if ($publishedAt)
            <p class="mb-2 text-sm text-slate-500">{{ $publishedAt }}</p>
        @endif
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>

        @if (!empty($excerpt))
            <p class="mt-4 text-lg text-slate-600">{{ $excerpt }}</p>
        @endif

        <div class="prose prose-slate mt-8 max-w-none">
            {!! \Statamic\Statamic::modify($content)->markdown() !!}
        </div>
    </article>

    <p class="mt-8">
        <a href="/blog" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">← Back to blog</a>
    </p>
@endsection
