@extends('layout')

@push('styles')
    @vite(['resources/css/cuestionario.css'])
@endpush

@push('scripts')
    @vite(['resources/js/cuestionario-checkin.js'])
@endpush

@section('meta_title')
    {{ ($meta_title ?? $title ?? 'Bienestar').' | '.config('app.name') }}
@endsection

@section('meta_description')
    {{ strip_tags($meta_description ?? '') }}
@endsection

@section('content')
    @php
        $secondaryAnchor = data_get($page, 'hero_secondary_cta_anchor') ?? '#checkin';
        $secondaryHref =
            is_string($secondaryAnchor) && str_starts_with($secondaryAnchor, '#')
                ? url('/bienestar').$secondaryAnchor
                : (is_string($secondaryAnchor) && $secondaryAnchor !== ''
                    ? $secondaryAnchor
                    : url('/bienestar').'#checkin');
        $checkinEnabled = data_get($page, 'checkin_enabled');
        $questions = data_get($page, 'checkin_questions');
        if (! is_array($questions)) {
            $questions = [];
        }
        $focusAreas = data_get($page, 'focus_areas');
        if (! is_array($focusAreas)) {
            $focusAreas = [];
        }
    @endphp

    <div class="cuestionario-page">
        <!--<section class="hero hero--layout-split bienestar-hero">
            <div class="container hero-grid">
                <article class="hero-card">
                    @if (filled(data_get($page, 'hero_eyebrow')))
                        <div class="eyebrow">{{ data_get($page, 'hero_eyebrow') }}</div>
                    @endif

                    <h1 class="hero-heading">{{ data_get($page, 'hero_title') }}</h1>
                    <p>{{ data_get($page, 'hero_subtitle') }}</p>

                    <div class="hero-actions">
                        <a class="btn" href="{{ data_get($page, 'hero_primary_cta_url') }}">{{ data_get($page, 'hero_primary_cta_label') }}</a>
                        <a class="btn secondary" href="{{ $secondaryHref }}">{{ data_get($page, 'hero_secondary_cta_label') }}</a>
                    </div>
                </article>-->

                <!--<aside class="visual-card bienestar-hero-aside" aria-label="Presentación breve">
                    @if ($hero_image_url ?? null)
                        <img src="{{ $hero_image_url }}" alt="{{ data_get($page, 'title') }}" class="bienestar-hero-photo" loading="lazy" decoding="async">
                    @else
                        <div class="bienestar-hero-photo bienestar-hero-photo--placeholder" role="presentation"></div>
                    @endif

                    <div class="bienestar-quote">
                        @if (filled(data_get($page, 'hero_quote_label')))
                            <p class="eyebrow bienestar-quote-label">{{ data_get($page, 'hero_quote_label') }}</p>
                        @endif
                        @if (filled(data_get($page, 'hero_quote_text')))
                            <p class="bienestar-quote-text">{{ data_get($page, 'hero_quote_text') }}</p>
                        @endif
                        @if (filled(data_get($page, 'hero_quote_support')))
                            <p class="bienestar-quote-support">{{ data_get($page, 'hero_quote_support') }}</p>
                        @endif
                    </div>
                </aside>
            </div>
        </section>
        

        <section class="home-block-spaced bienestar-about">
            <div class="container split">
                <article class="about-card bienestar-about-intro">
                    @if (filled(data_get($page, 'about_kicker')))
                        <div class="eyebrow">{{ data_get($page, 'about_kicker') }}</div>
                    @endif
                    <h3 class="section-title">{{ data_get($page, 'about_title') }}</h3>
                    <p class="section-copy">{{ data_get($page, 'about_intro') }}</p>
                </article>

                <div class="content-block bienestar-about-body-wrap">
                    @if (filled($about_body_html ?? ''))
                        <div class="bard-body bienestar-bard">{!! $about_body_html !!}</div>
                    @endif
                    @if (count($focusAreas) > 0)
                        <div class="service-grid bienestar-focus-grid">
                            @foreach ($focusAreas as $row)
                                @php $label = is_array($row) ? ($row['label'] ?? '') : ''; @endphp
                                @if (filled($label))
                                    <div class="pill">{{ $label }}</div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    -->

        @if ($checkinEnabled)
            <section class="home-block-spaced bienestar-checkin" id="checkin">
                <div class="container">
                    <article class="service-list bienestar-checkin-card">
                        <div class="checkin-header">
                            <div>
                                <div class="eyebrow">Check-in emocional</div>
                                <h3 class="section-title">{{ data_get($page, 'checkin_title') }}</h3>
                                <p class="section-copy">{{ data_get($page, 'checkin_intro') }}</p>
                            </div>
                            <div class="bienestar-instructions">
                                <strong>Instrucciones:</strong> responde pensando en las últimas dos semanas.<br>
                                0 = Nunca · 1 = Algunos días · 2 = Más de la mitad · 3 = Casi todos los días
                            </div>
                        </div>

                        <form class="checkin-form" data-checkin-form>
                            @foreach ($questions as $index => $row)
                                @php
                                    $q = is_array($row) ? ($row['question'] ?? '') : '';
                                    $cat = is_array($row) ? ($row['category'] ?? 'mood') : 'mood';
                                @endphp
                                @if (filled($q))
                                    <fieldset class="question-card bienestar-question" data-category="{{ $cat }}">
                                        <legend>{{ $index + 1 }}. {{ $q }}</legend>
                                        <div class="bienestar-option-grid">
                                            <label><input type="radio" name="q{{ $index }}" value="0" required> Nunca</label>
                                            <label><input type="radio" name="q{{ $index }}" value="1"> Algunos días</label>
                                            <label><input type="radio" name="q{{ $index }}" value="2"> Más de la mitad</label>
                                            <label><input type="radio" name="q{{ $index }}" value="3"> Casi todos los días</label>
                                        </div>
                                    </fieldset>
                                @endif
                            @endforeach

                            <div class="hero-actions bienestar-form-actions">
                                <button type="submit" class="btn">Ver orientación</button>
                                <button type="reset" class="btn secondary">Limpiar respuestas</button>
                            </div>
                        </form>

                        <div class="bienestar-result" data-checkin-result hidden></div>

                        <p class="bienestar-disclaimer">{{ data_get($page, 'checkin_disclaimer') }}</p>
                    </article>
                </div>
            </section>
        @endif

        <section class="home-block-spaced bienestar-final">
            <div class="container">
                <article class="cta-card bienestar-final-inner">
                    <div class="eyebrow">{{ data_get($page, 'final_cta_eyebrow') ?? 'Siguiente paso' }}</div>
                    <h3 class="section-title">{{ data_get($page, 'final_cta_heading') }}</h3>
                    <p>{{ data_get($page, 'final_cta_body') }}</p>
                    <a class="btn" href="{{ $final_cta_url }}">{{ data_get($page, 'checkin_cta_label') }}</a>
                </article>
            </div>
        </section>
    </div>
@endsection
