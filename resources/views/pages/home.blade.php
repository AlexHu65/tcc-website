@extends('layout')

@section('meta_title')
    {{ $ilse_meta_title ?? $title ?? config('app.name') }}
@endsection

@section('meta_description')
    {{ $ilse_meta_description ?? '' }}
@endsection

@section('content')
    @php
        $blocks = $sections ?? [];
        $ilseAsset = fn (?string $file, string $default) => asset('images/ilse/' . (($file ?? '') !== '' ? $file : $default));
        $contactSuccessMessage = session('contact_success') ?? session('success');
    @endphp

    @if ($contactSuccessMessage)
        <style>
            .ilse-simple-toast {
                position: fixed;
                top: 110px;
                right: max(18px, calc((100vw - var(--max)) / 2));
                z-index: 120;
                display: flex;
                align-items: flex-start;
                gap: 14px;
                width: min(420px, calc(100vw - 32px));
                padding: 18px;
                border: 1px solid rgba(111, 113, 87, 0.28);
                border-left: 5px solid var(--olive);
                border-radius: 20px;
                background: #fbf7f3;
                box-shadow: 0 18px 55px rgba(44, 36, 31, 0.16);
                color: var(--text);
            }

            .ilse-simple-toast strong {
                display: block;
                margin-bottom: 4px;
                font-size: 0.86rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--olive-dark);
            }

            .ilse-simple-toast p {
                margin: 0;
                color: var(--muted);
                line-height: 1.5;
            }

            .ilse-simple-toast__close {
                flex: 0 0 auto;
                width: 30px;
                height: 30px;
                margin-left: auto;
                border: 1px solid var(--line);
                border-radius: 50%;
                background: #fff;
                color: var(--muted);
                cursor: pointer;
                font-size: 1.25rem;
                line-height: 1;
            }

            .ilse-simple-toast.is-hidden {
                display: none;
            }
        </style>

        <div class="ilse-simple-toast" role="status" aria-live="polite" data-simple-toast>
            <div>
                <strong>Mensaje enviado</strong>
                <p>{{ $contactSuccessMessage }}</p>
            </div>
            <button type="button" class="ilse-simple-toast__close" aria-label="Cerrar notificación" data-simple-toast-close>&times;</button>
        </div>

        <script>
            document.querySelector('[data-simple-toast-close]')?.addEventListener('click', function () {
                document.querySelector('[data-simple-toast]')?.classList.add('is-hidden');
            });
        </script>
    @endif

    @foreach ($blocks as $block)
        @php
            $type = $block['type'] ?? null;
            $enabled = $block['enabled'] ?? true;
        @endphp

        @if (!$enabled)
            @continue
        @endif

        @if ($type === 'hero')
            @php
                    $defaultHeroFile = 'logo_design_on_a_clean_pale_beige_cream_backgroun.png';
                    $resolvedHeroUrl = \App\Support\IlseHeroBlock::imageUrl(
                        $block['hero_image'] ?? null,
                        fn (string $file) => $ilseAsset($file, $defaultHeroFile)
                    );
                    $includeImageToggle = filter_var(
                        $block['hero_include_image'] ?? true,
                        FILTER_VALIDATE_BOOLEAN,
                        FILTER_NULL_ON_FAILURE
                    );
                    if ($includeImageToggle === null) {
                        $includeImageToggle = true;
                    }
                    $showHeroImage = $includeImageToggle && $resolvedHeroUrl !== null;
                    $colsRaw = $block['hero_columns_desktop'] ?? '2';
                    $cols = match (true) {
                        in_array($colsRaw, [1, '1'], true) => '1',
                        in_array($colsRaw, [2, '2'], true) => '2',
                        default => '2',
                    };
                    $imageSide = ($block['hero_image_side'] ?? 'right') === 'left' ? 'left' : 'right';
                    $titleSize = $block['hero_title_size'] ?? 'default';
                    $titleSizeClass = in_array($titleSize, ['large', 'xl'], true) ? ' hero-heading--'.$titleSize : '';
                    $stacked = $cols === '1' && $showHeroImage;
                    $splitTwoCols = $cols === '2' && $showHeroImage;
                    $heroSectionClass = 'hero';
                    if ($stacked) {
                        $heroSectionClass .= ' hero--layout-stack';
                    } elseif ($splitTwoCols) {
                        $heroSectionClass .= ' hero--layout-split';
                    } else {
                        $heroSectionClass .= ' hero--layout-text-only';
                    }
                    $emphasis = $block['title_emphasis'] ?? 'transformar tu vida.';
                    $heroContentFullWidth = ! $showHeroImage;
            @endphp
            <section class="{{ $heroSectionClass }}" id="inicio">
                    <div class="container {{ $stacked ? 'hero-stack' : 'hero-grid'.(!$showHeroImage ? ' hero-grid--single hero-inner--full' : '') }}">
                        @if ($stacked && $showHeroImage)
                            <aside class="visual-card visual-card--stack-top">
                                <img src="{{ $resolvedHeroUrl }}" alt="{{ $block['image_alt'] ?? 'Identidad visual' }}" loading="eager" decoding="async" />
                            </aside>
                        @endif

                        @if ($splitTwoCols && $showHeroImage && $imageSide === 'left')
                            <aside class="visual-card">
                                <img src="{{ $resolvedHeroUrl }}" alt="{{ $block['image_alt'] ?? 'Identidad visual' }}" loading="eager" decoding="async" />
                            </aside>
                        @endif

                        <article class="hero-card{{ $heroContentFullWidth ? ' hero-card--full' : '' }}">
                            <div class="eyebrow">{{ $block['eyebrow'] ?? 'Psicoterapia cognitivo conductual' }}</div>
                            <h2 class="hero-heading{{ $titleSizeClass }}">
                                {{ $block['title_before'] ?? '' }}@if(($block['title_emphasis'] ?? '') !== '')<span>{{ $block['title_emphasis'] }}</span>@elseif(($block['title'] ?? '') !== '')<span>{{ $block['title'] }}</span>@else<span>{{ $emphasis }}</span>@endif
                            </h2>
                            <p>{{ $block['subtitle'] ?? '' }}</p>
                            <div class="hero-actions">
                                <a class="btn" href="{{ $block['primary_cta_url'] ?? '#contacto' }}">{{ $block['primary_cta_label'] ?? 'Agenda tu cita' }}</a>
                                <a class="btn secondary" href="{{ $block['secondary_cta_url'] ?? '#sobre-mi' }}">{{ $block['secondary_cta_label'] ?? 'Conoce más' }}</a>
                            </div>
                        </article>

                        @if ($splitTwoCols && $showHeroImage && $imageSide === 'right')
                            <aside class="visual-card">
                                <img src="{{ $resolvedHeroUrl }}" alt="{{ $block['image_alt'] ?? 'Identidad visual' }}" loading="eager" decoding="async" />
                            </aside>
                        @endif
                    </div>
            </section>
        @endif

        @if ($type === 'ilse_enfoque')
            <section id="enfoque">
                <div class="container">
                    <div class="section-head">
                        <div class="eyebrow">{{ $block['eyebrow'] ?? '' }}</div>
                        <h3 class="section-title">{{ $block['heading'] ?? '' }}</h3>
                        <p class="section-copy">{{ $block['copy'] ?? '' }}</p>
                    </div>

                    <div class="grid-4">
                        @foreach (($block['items'] ?? []) as $benefit)
                            <article class="content-block">
                                <div class="icon">{{ $benefit['icon'] ?? '✨' }}</div>
                                <h4>{{ $benefit['title'] ?? '' }}</h4>
                                <p>{{ $benefit['description'] ?? '' }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($type === 'servicios_ilse')
            @php
                $serviciosResolved = \App\Support\IlseHeroBlock::imageUrl(
                    $block['image'] ?? null,
                    fn (string $file) => $ilseAsset($file, 'a_clean_logo_branding_design_on_a_soft_beige_cream.png')
                );
                $serviciosImg = $serviciosResolved ?? $ilseAsset(null, 'a_clean_logo_branding_design_on_a_soft_beige_cream.png');
            @endphp
            <section id="servicios">
                <div class="container split">
                    <div class="photo-frame">
                        <img src="{{ $serviciosImg }}" alt="{{ $block['image_alt'] ?? '' }}" />
                    </div>

                    <article class="service-list">
                        <div class="eyebrow">{{ $block['eyebrow'] ?? '' }}</div>
                        <h3 class="section-title">{{ $block['heading'] ?? '' }}</h3>
                        <p class="section-copy">{{ $block['copy'] ?? '' }}</p>
                        <div class="service-grid">
                            @foreach (($block['pills'] ?? []) as $pill)
                                <div class="pill">{{ is_array($pill) ? ($pill['label'] ?? '') : $pill }}</div>
                            @endforeach
                        </div>
                        <a class="btn" href="{{ $block['cta_url'] ?? '#contacto' }}">{{ $block['cta_label'] ?? 'Ver disponibilidad' }}</a>
                    </article>
                </div>
            </section>
        @endif

        @if ($type === 'sobre_mi')
            @php
                $sobreResolved = \App\Support\IlseHeroBlock::imageUrl(
                    $block['image'] ?? null,
                    fn (string $file) => $ilseAsset($file, 'a_clean_minimalist_logo_branding_design_on_a_pale.png')
                );
                $sobreImg = $sobreResolved ?? $ilseAsset(null, 'a_clean_minimalist_logo_branding_design_on_a_pale.png');
                $paras = $block['paragraphs'] ?? [];
            @endphp
            <section id="sobre-mi">
                <div class="container">
                    <article class="about-card">
                        <div class="about-inner">
                            <div>
                                <div class="eyebrow">{{ $block['eyebrow'] ?? '' }}</div>
                                <h3>{{ $block['heading'] ?? '' }}</h3>
                                @foreach ($paras as $para)
                                    @if (is_array($para) && ! empty($para['text']))
                                        <p>{{ $para['text'] }}</p>
                                    @elseif (is_string($para) && $para !== '')
                                        <p>{{ $para }}</p>
                                    @endif
                                @endforeach
                                <a class="btn secondary" href="{{ $block['cta_url'] ?? '#contacto' }}">{{ $block['cta_label'] ?? 'Quiero comenzar' }}</a>
                            </div>
                            <div class="photo-frame">
                                <img src="{{ $sobreImg }}" alt="{{ $block['image_alt'] ?? '' }}" />
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        @endif

        @if ($type === 'servicios')
            <section class="home-block-spaced">
                <div class="container">
                    @if (!empty($block['heading']))
                        <div class="section-head">
                            <h3 class="section-title">{{ $block['heading'] }}</h3>
                        </div>
                    @endif
                    <div class="grid-4">
                        @foreach (($block['items'] ?? []) as $item)
                            <article class="content-block">
                                <h4>{{ $item['title'] ?? '' }}</h4>
                                <p>{{ $item['description'] ?? '' }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($type === 'gallery')
            <section class="home-block-spaced">
                <div class="container">
                    @include('partials.home.gallery', ['block' => $block])
                </div>
            </section>
        @endif

        @if ($type === 'rich_text')
            <section class="home-block-spaced">
                <div class="container">
                    <article class="about-card">
                        <div class="eyebrow">{{ $block['eyebrow'] ?? 'Metodo de trabajo' }}</div>
                        <h3 class="section-title">{{ $block['heading'] ?? '' }}</h3>
                        <div class="section-copy prose prose-p:text-[var(--muted)] max-w-none">
                            {!! \Statamic\Statamic::modify($block['body'] ?? '')->markdown() !!}
                        </div>
                    </article>
                </div>
            </section>
        @endif

        @if ($type === 'pasos')
            <section class="home-block-spaced">
                <div class="container">
                    <div class="grid-4">
                        @foreach (($block['items'] ?? []) as $step)
                            <article class="content-block">
                                <p class="eyebrow">{{ $step['step'] ?? 'Paso' }}</p>
                                <h4>{{ $step['title'] ?? '' }}</h4>
                                <p>{{ $step['description'] ?? '' }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($type === 'testimonios')
            <section class="home-block-spaced">
                <div class="container">
                    <article class="cta-card">
                        <div class="eyebrow">Testimonios</div>
                        <h3 class="section-title">{{ $block['heading'] ?? 'Historias de cambio real' }}</h3>
                        <div class="space-y-5" style="color: var(--muted);">
                            @foreach (($block['items'] ?? []) as $testimonial)
                                <blockquote style="margin: 0; font-style: italic;">
                                    "{{ $testimonial['quote'] ?? '' }}"
                                    <footer style="margin-top: 8px; font-style: normal; font-weight: 600; color: var(--text);">— {{ $testimonial['author'] ?? '' }}</footer>
                                </blockquote>
                            @endforeach
                        </div>
                    </article>
                </div>
            </section>
        @endif

        @if ($type === 'contact_form')
            @php
                $showForm = filter_var($block['show_contact_form'] ?? true, FILTER_VALIDATE_BOOLEAN);
                $formHandle = $block['form_handle'] ?? 'contacto';
                $showStepTwo = $errors->any() || old('mensaje');
            @endphp
            <section id="contacto">
                <div class="container">
                    @if ($showForm)
                        <article class="cta-card cta-card--form">
                            <div class="eyebrow">{{ $block['eyebrow'] ?? 'Contacto' }}</div>
                            <h3 class="ilse-form-title">{{ $block['heading'] ?? 'Agenda tu cita' }}</h3>
                            @if (!empty($block['intro_copy']))
                                <p style="text-align:center;margin-bottom:24px;">{{ $block['intro_copy'] }}</p>
                            @endif

                            @if ($contactSuccessMessage)
                                <p class="ilse-alert ilse-alert--success">{{ $contactSuccessMessage }}</p>
                            @endif

                            @if ($errors->any())
                                <p class="ilse-alert ilse-alert--error">Revisa los datos del formulario e intenta de nuevo.</p>
                            @endif

                            <form action="{{ route('statamic.forms.submit', ['form' => $formHandle]) }}" method="POST" class="ilse-contact-form" data-contact-form>
                                @csrf
                                <input type="hidden" name="_redirect" value="{{ url('/#contacto') }}">
                                <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                                <div class="{{ $showStepTwo ? 'hidden' : '' }}" data-step="1">
                                    <div class="ilse-field">
                                        <label for="nombre">Nombre</label>
                                        <input id="nombre" name="nombre" value="{{ old('nombre') }}" class="ilse-input" required autocomplete="name">
                                    </div>
                                    <div class="ilse-field">
                                        <label for="telefono">Teléfono</label>
                                        <input id="telefono" name="telefono" value="{{ old('telefono') }}" class="ilse-input" required autocomplete="tel">
                                    </div>
                                    <div class="ilse-field">
                                        <label for="email">Email</label>
                                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="ilse-input" required autocomplete="email">
                                    </div>
                                    <div class="ilse-form-actions">
                                        <button type="button" class="btn" data-next-step>{{ $block['step1_label'] ?? 'Continuar' }}</button>
                                    </div>
                                </div>

                                <div class="{{ $showStepTwo ? '' : 'hidden' }}" data-step="2">
                                    <div class="ilse-field">
                                        <label for="mensaje">Mensaje</label>
                                        <textarea id="mensaje" name="mensaje" rows="4" class="ilse-textarea" required>{{ old('mensaje') }}</textarea>
                                    </div>
                                    <div class="ilse-form-actions">
                                        <button type="button" class="btn secondary" data-prev-step>{{ $block['back_label'] ?? 'Volver' }}</button>
                                        <button type="submit" class="btn">{{ $block['submit_label'] ?? 'Enviar solicitud' }}</button>
                                    </div>
                                </div>
                            </form>
                        </article>
                    @else
                        <article class="cta-card">
                            <div class="eyebrow">{{ $block['eyebrow'] ?? '' }}</div>
                            <h3>{{ $block['heading'] ?? '' }}</h3>
                            <p>{{ $block['intro_copy'] ?? '' }}</p>
                            <a class="btn" href="{{ $block['fallback_button_url'] ?? 'mailto:hola@ilsemendezpsico.com' }}">{{ $block['fallback_button_label'] ?? 'Escríbeme' }}</a>
                        </article>
                    @endif
                </div>
            </section>
        @endif

        @if ($type === 'social_proof')
            <section class="home-block-spaced">
                <div class="container">
                    <div class="grid-4">
                        @foreach (($block['metrics'] ?? []) as $metric)
                            <article class="content-block" style="text-align: center;">
                                <p class="section-title" style="font-size: 2.5rem; margin-bottom: 8px;">{{ $metric['value'] ?? '' }}</p>
                                <p style="color: var(--muted); font-size: 0.9rem;">{{ $metric['label'] ?? '' }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($type === 'faq')
            <section class="home-block-spaced">
                <div class="container">
                    <article class="cta-card">
                        <div class="eyebrow">Preguntas frecuentes</div>
                        <h3 class="section-title">{{ $block['heading'] ?? 'Resolvemos tus dudas' }}</h3>
                        <div class="space-y-3">
                            @foreach (($block['items'] ?? []) as $faq)
                                <details style="border-bottom: 1px solid var(--line); padding-bottom: 12px;">
                                    <summary style="cursor: pointer; font-weight: 600; color: var(--text);">{{ $faq['question'] ?? '' }}</summary>
                                    <p style="margin-top: 8px; color: var(--muted); font-size: 0.95rem;">{{ $faq['answer'] ?? '' }}</p>
                                </details>
                            @endforeach
                        </div>
                    </article>
                </div>
            </section>
        @endif

        @if ($type === 'cta')
            <section class="home-block-spaced">
                <div class="container">
                    <article class="cta-card">
                        <div class="eyebrow">{{ $block['eyebrow'] ?? 'Siguiente paso' }}</div>
                        <h3 class="section-title">{{ $block['heading'] ?? '' }}</h3>
                        <p style="color: var(--muted);">{{ $block['description'] ?? '' }}</p>
                        <a class="btn" href="{{ $block['cta_url'] ?? '/#contacto' }}">{{ $block['cta_label'] ?? 'Ir al formulario' }}</a>
                    </article>
                </div>
            </section>
        @endif
    @endforeach
@endsection
