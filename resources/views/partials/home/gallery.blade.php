@php
    $availableLayouts = ['carousel', 'grid', 'masonry'];
    $layout = in_array(($block['layout'] ?? ''), $availableLayouts, true) ? $block['layout'] : 'carousel';

    $columnsDesktop = (int) ($block['columns_desktop'] ?? 3);
    if ($columnsDesktop < 2 || $columnsDesktop > 4) {
        $columnsDesktop = 3;
    }

    $gridColumnsClass = [
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-4',
    ][$columnsDesktop];

    $masonryColumnsClass = [
        2 => 'sm:columns-2',
        3 => 'sm:columns-2 lg:columns-3',
        4 => 'sm:columns-2 lg:columns-4',
    ][$columnsDesktop];

    $items = [];

    foreach (($block['items'] ?? []) as $item) {
        $directImageUrl = trim((string) ($item['image_url'] ?? ''));

        if ($directImageUrl !== '') {
            $title = trim((string) ($item['title'] ?? ''));
            $overrideAlt = trim((string) ($item['alt_override'] ?? ''));
            $alt = $overrideAlt !== '' ? $overrideAlt : ($title !== '' ? $title : 'Imagen de galeria');

            $items[] = [
                'url' => $directImageUrl,
                'title' => $title,
                'alt' => $alt,
            ];

            continue;
        }

        $imageField = $item['image'] ?? null;
        $assetRef = is_array($imageField) ? ($imageField[0] ?? null) : $imageField;

        if (empty($assetRef)) {
            continue;
        }

        // Normalize: if no container prefix, assume default "assets" container
        if (is_string($assetRef) && !str_contains($assetRef, '::')) {
            $assetRef = 'assets::' . $assetRef;
        }

        $asset = \Statamic\Facades\Asset::find($assetRef);
        $imageUrl = $asset ? $asset->url() : null;

        if (empty($imageUrl)) {
            continue;
        }

        $title = trim((string) ($item['title'] ?? ''));
        $overrideAlt = trim((string) ($item['alt_override'] ?? ''));
        $assetAlt = $asset ? trim((string) ($asset->get('alt') ?? '')) : '';
        $alt = $overrideAlt !== '' ? $overrideAlt : ($assetAlt !== '' ? $assetAlt : ($title !== '' ? $title : 'Imagen de galeria'));

        $items[] = [
            'url' => $imageUrl,
            'title' => $title,
            'alt' => $alt,
        ];
    }

    $instanceId = uniqid('gallery-', false);
@endphp

@if (!empty($items))
    <section class="gallery-block mt-10 mb-14 fade-in-up" data-gallery-block data-gallery-layout="{{ $layout }}">
        @if (!empty($block['eyebrow']) || !empty($block['heading']) || !empty($block['description']))
            <header class="mb-6 md:mb-8">
                @if (!empty($block['eyebrow']))
                    <p class="eyebrow mb-3">{{ $block['eyebrow'] }}</p>
                @endif

                @if (!empty($block['heading']))
                    <h2 class="title-serif text-3xl font-semibold text-brown md:text-4xl">{{ $block['heading'] }}</h2>
                @endif

                @if (!empty($block['description']))
                    <p class="mt-3 max-w-3xl text-brown-soft">{{ $block['description'] }}</p>
                @endif
            </header>
        @endif

        @if ($layout === 'carousel')
            <div id="{{ $instanceId }}-carousel" class="gallery-carousel swiper" data-gallery-carousel aria-label="Galeria de imagenes">
                <div class="swiper-wrapper">
                    @foreach ($items as $index => $item)
                        <article class="swiper-slide gallery-card rounded-2xl bg-white/80 p-3" role="group" aria-label="Imagen {{ $index + 1 }} de {{ count($items) }}">
                            <figure>
                                <img src="{{ $item['url'] }}" alt="{{ $item['alt'] }}" class="gallery-image aspect-[4/3] w-full rounded-xl object-cover">
                                @if ($item['title'] !== '')
                                    <figcaption class="mt-3 px-1 text-sm font-medium text-brown">{{ $item['title'] }}</figcaption>
                                @endif
                            </figure>
                        </article>
                    @endforeach
                </div>

                <div class="gallery-controls mt-5 flex items-center justify-between gap-3">
                    <button class="swiper-button-prev !static !m-0 !h-11 !w-11 rounded-full" type="button" aria-label="Imagen anterior" aria-controls="{{ $instanceId }}-carousel"></button>
                    <div class="swiper-pagination !static !w-auto" aria-label="Paginacion de galeria"></div>
                    <button class="swiper-button-next !static !m-0 !h-11 !w-11 rounded-full" type="button" aria-label="Imagen siguiente" aria-controls="{{ $instanceId }}-carousel"></button>
                </div>
            </div>
        @elseif ($layout === 'masonry')
            <div class="gallery-masonry {{ $masonryColumnsClass }} gap-5">
                @foreach ($items as $item)
                    <figure class="gallery-masonry-item mb-5 break-inside-avoid rounded-2xl bg-white/80 p-3">
                        <img src="{{ $item['url'] }}" alt="{{ $item['alt'] }}" class="gallery-image h-auto w-full rounded-xl object-cover">
                        @if ($item['title'] !== '')
                            <figcaption class="mt-3 px-1 text-sm font-medium text-brown">{{ $item['title'] }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        @else
            <div class="gallery-grid grid {{ $gridColumnsClass }} gap-5">
                @foreach ($items as $item)
                    <figure class="gallery-grid-item rounded-2xl bg-white/80 p-3">
                        <img src="{{ $item['url'] }}" alt="{{ $item['alt'] }}" class="gallery-image aspect-[4/3] w-full rounded-xl object-cover">
                        @if ($item['title'] !== '')
                            <figcaption class="mt-3 px-1 text-sm font-medium text-brown">{{ $item['title'] }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        @endif
    </section>
@endif
