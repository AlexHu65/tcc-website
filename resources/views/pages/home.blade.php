@extends('layout')

@section('content')
    <section class="mb-14 rounded-2xl bg-white p-10 shadow-sm ring-1 ring-slate-200">
        <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-indigo-600">MVP Template</p>
        <h1 class="mb-4 text-4xl font-bold tracking-tight text-slate-900">
            {{ $hero_title ?? $title }}
        </h1>
        <p class="max-w-2xl text-lg text-slate-600">
            {{ $hero_subtitle ?? 'A fast baseline for marketing pages and publishing updates.' }}
        </p>
        <div class="mt-8 flex gap-3">
            <a href="/blog" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Read the blog</a>
            <a href="/cp" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Open Control Panel</a>
        </div>
    </section>

    <section class="prose prose-slate max-w-none">
        {!! \Statamic\Statamic::modify($content)->markdown() !!}
    </section>
@endsection
