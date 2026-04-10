@extends('layout')

@php
    use App\Support\BlogImage;
    use Statamic\Facades\Entry;
    use Statamic\Facades\Site;

    $blogEntry = isset($id) ? Entry::find($id) : Entry::findByUri('/'.request()->path(), Site::current()->handle());
    $heroImageUrl = BlogImage::heroUrl($blogEntry);
@endphp

@section('content')
    @php
        $publishedAt = ! empty($date) ? \Illuminate\Support\Carbon::parse($date)->format('d M Y') : null;
    @endphp

    <article class="card-warm fade-in-up overflow-hidden rounded-3xl">
        @if ($heroImageUrl)
            <div class="aspect-[21/9] max-h-[min(22rem,50vw)] w-full overflow-hidden bg-gradient-to-br from-[#e9d2b3]/35 to-[#d8b389]/25">
                <img src="{{ $heroImageUrl }}" alt="" class="h-full w-full object-cover" fetchpriority="high">
            </div>
        @endif
        <div class="p-8 md:p-12">
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
        </div>
    </article>

    <p class="mt-8">
        <a href="/blog" class="link-warm text-sm font-semibold">← Volver a recursos</a>
    </p>
@endsection
