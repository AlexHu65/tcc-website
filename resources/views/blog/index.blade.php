@extends('layout')

@section('content')
    <section class="mb-10">
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $title ?? 'Blog' }}</h1>
        <p class="mt-2 text-slate-600">{{ $content ?? 'Latest articles and updates.' }}</p>
    </section>

    @php
        $posts = \Statamic\Facades\Entry::query()
            ->where('collection', 'blog')
            ->orderByDesc('date')
            ->get();
    @endphp

    <section class="space-y-5">
        @forelse ($posts as $post)
            <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="mb-2 text-sm text-slate-500">{{ optional($post->date())->format('M d, Y') }}</p>
                <h2 class="text-2xl font-semibold text-slate-900">
                    <a class="hover:underline" href="{{ $post->url() }}">{{ $post->get('title') }}</a>
                </h2>
                <p class="mt-3 text-slate-600">{{ $post->get('excerpt') }}</p>
            </article>
        @empty
            <p class="rounded-lg bg-amber-50 p-4 text-amber-800">No posts yet. Create one in the Statamic Control Panel.</p>
        @endforelse
    </section>
@endsection
