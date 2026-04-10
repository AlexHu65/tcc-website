@extends('layout')

@section('content')
    @php
        $blocks = $sections ?? [];
    @endphp

    @foreach ($blocks as $block)
        @php
            $type = $block['type'] ?? null;
            $enabled = $block['enabled'] ?? true;
        @endphp

        @if (!$enabled)
            @continue
        @endif

        @if ($type === 'hero')
            <section class="card-editorial fade-in-up relative mb-16 overflow-hidden rounded-3xl p-10 md:p-16">
                <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[#e9d2b3]/30 blur-2xl"></div>
                <div class="absolute -bottom-24 -left-12 h-56 w-56 rounded-full bg-[#d8b389]/20 blur-2xl"></div>
                <p class="eyebrow mb-4">{{ $block['eyebrow'] ?? 'Psicologia contemporanea' }}</p>
                <h1 class="title-serif mb-6 max-w-3xl text-5xl font-semibold leading-tight text-brown md:text-6xl">
                    {{ $block['title'] ?? $title ?? '' }}
                </h1>
                <p class="max-w-3xl text-xl leading-relaxed text-brown-soft">
                    {{ $block['subtitle'] ?? '' }}
                </p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="{{ $block['primary_cta_url'] ?? '/blog' }}" class="btn-warm rounded-2xl px-6 py-3 text-sm font-semibold">
                        {{ $block['primary_cta_label'] ?? 'Explorar recursos' }}
                    </a>
                    <a href="{{ $block['secondary_cta_url'] ?? '/#contacto' }}" class="btn-ghost rounded-2xl px-6 py-3 text-sm font-semibold">
                        {{ $block['secondary_cta_label'] ?? 'Solicitar primera sesion' }}
                    </a>
                </div>
            </section>
        @endif

        @if ($type === 'servicios')
            <section class="fade-in-up-delay mb-14">
                @if (!empty($block['heading']))
                    <h2 class="title-serif mb-6 text-3xl font-semibold text-brown">{{ $block['heading'] }}</h2>
                @endif
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach (($block['items'] ?? []) as $item)
                        <article class="card-warm rounded-2xl p-6">
                            <h3 class="mb-2 text-xl font-semibold text-brown">{{ $item['title'] ?? '' }}</h3>
                            <p class="content-prose text-sm leading-relaxed text-brown-soft">{{ $item['description'] ?? '' }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($type === 'rich_text')
            <section class="card-warm section-divider mb-14 rounded-3xl p-8 md:p-12">
                <p class="eyebrow mb-3">{{ $block['eyebrow'] ?? 'Metodo de trabajo' }}</p>
                <h3 class="title-serif mb-6 text-4xl font-semibold text-brown">{{ $block['heading'] ?? '' }}</h3>
                <div class="prose content-prose max-w-none prose-p:text-brown-soft prose-headings:text-brown">
                    {!! \Statamic\Statamic::modify($block['body'] ?? '')->markdown() !!}
                </div>
            </section>
        @endif

        @if ($type === 'pasos')
            <section class="mt-14 mb-14 grid gap-6 md:grid-cols-3">
                @foreach (($block['items'] ?? []) as $step)
                    <article class="card-warm rounded-2xl p-6">
                        <p class="eyebrow mb-2">{{ $step['step'] ?? 'Paso' }}</p>
                        <h4 class="mb-2 text-xl font-semibold text-brown">{{ $step['title'] ?? '' }}</h4>
                        <p class="text-brown-soft">{{ $step['description'] ?? '' }}</p>
                    </article>
                @endforeach
            </section>
        @endif

        @if ($type === 'testimonios')
            <section class="mt-14 mb-10">
                <article class="card-warm rounded-3xl p-8">
                    <p class="eyebrow mb-3">Testimonios</p>
                    <h4 class="title-serif mb-5 text-3xl text-brown">{{ $block['heading'] ?? 'Historias de cambio real' }}</h4>
                    <div class="space-y-5 text-brown-soft">
                        @foreach (($block['items'] ?? []) as $testimonial)
                            <blockquote class="quote-soft">
                                "{{ $testimonial['quote'] ?? '' }}"
                                <footer class="mt-2 text-sm font-semibold text-brown">- {{ $testimonial['author'] ?? '' }}</footer>
                            </blockquote>
                        @endforeach
                    </div>
                </article>
            </section>
        @endif

        @if ($type === 'contact_form')
            <section class="mt-10 mb-10">
                <article id="contacto" class="card-editorial rounded-3xl p-8 md:max-w-2xl">
                    <p class="eyebrow mb-3">{{ $block['eyebrow'] ?? 'Contacto' }}</p>
                    <h4 class="title-serif mb-5 text-3xl text-brown">{{ $block['heading'] ?? 'Agenda tu primera sesion' }}</h4>

                    @if (session('success'))
                        <p class="alert-banner alert-banner--success mb-4">
                            {{ session('success') }}
                        </p>
                    @endif

                    @if ($errors->any())
                        <p class="alert-banner alert-banner--error mb-4">
                            Revisa los datos del formulario e intenta de nuevo.
                        </p>
                    @endif

                    @php
                        $showStepTwo = $errors->any() || old('mensaje');
                        $formHandle = $block['form_handle'] ?? 'contacto';
                    @endphp

                    <form action="{{ route('statamic.forms.submit', ['form' => $formHandle]) }}" method="POST" class="space-y-4" data-contact-form>
                        @csrf
                        <input type="hidden" name="_redirect" value="{{ url('/#contacto') }}">
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                        <div class="{{ $showStepTwo ? 'hidden' : '' }}" data-step="1">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-brown-soft">Paso 1 de 2</p>
                            <div class="space-y-4">
                                <div>
                                    <label for="nombre" class="mb-1 block text-sm font-medium text-brown">Nombre</label>
                                    <input id="nombre" name="nombre" value="{{ old('nombre') }}" class="input-surface" required>
                                </div>
                                <div>
                                    <label for="telefono" class="mb-1 block text-sm font-medium text-brown">Telefono</label>
                                    <input id="telefono" name="telefono" value="{{ old('telefono') }}" class="input-surface" required>
                                </div>
                                <div>
                                    <label for="email" class="mb-1 block text-sm font-medium text-brown">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="input-surface" required>
                                </div>
                            </div>
                            <button type="button" class="btn-warm mt-4 w-full rounded-2xl px-5 py-3 text-sm font-semibold" data-next-step>
                                Continuar
                            </button>
                        </div>

                        <div class="{{ $showStepTwo ? '' : 'hidden' }}" data-step="2">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-brown-soft">Paso 2 de 2</p>
                            <div>
                                <label for="mensaje" class="mb-1 block text-sm font-medium text-brown">Mensaje</label>
                                <textarea id="mensaje" name="mensaje" rows="4" class="input-surface" required>{{ old('mensaje') }}</textarea>
                            </div>
                            <div class="mt-4 flex gap-3">
                                <button type="button" class="btn-ghost w-1/2 rounded-2xl px-5 py-3 text-sm font-semibold" data-prev-step>
                                    Volver
                                </button>
                                <button class="btn-warm w-1/2 rounded-2xl px-5 py-3 text-sm font-semibold">{{ $block['submit_label'] ?? 'Enviar solicitud' }}</button>
                            </div>
                        </div>
                    </form>
                </article>
            </section>
        @endif

        @if ($type === 'social_proof')
            <section class="mt-10 mb-10 grid gap-6 md:grid-cols-3">
                @foreach (($block['metrics'] ?? []) as $metric)
                    <article class="card-warm rounded-2xl p-6 text-center">
                        <p class="title-serif text-4xl font-semibold text-brown">{{ $metric['value'] ?? '' }}</p>
                        <p class="mt-1 text-sm text-brown-soft">{{ $metric['label'] ?? '' }}</p>
                    </article>
                @endforeach
            </section>
        @endif

        @if ($type === 'faq')
            <section class="mt-10 mb-10">
                <article class="card-warm rounded-3xl p-8">
                    <p class="eyebrow mb-3">Preguntas frecuentes</p>
                    <h4 class="title-serif mb-5 text-3xl text-brown">{{ $block['heading'] ?? 'Resolvemos tus dudas' }}</h4>
                    <div class="space-y-3">
                        @foreach (($block['items'] ?? []) as $faq)
                            <details class="faq-item">
                                <summary class="cursor-pointer font-semibold text-brown">{{ $faq['question'] ?? '' }}</summary>
                                <p class="mt-2 text-sm text-brown-soft">{{ $faq['answer'] ?? '' }}</p>
                            </details>
                        @endforeach
                    </div>
                </article>
            </section>
        @endif

        @if ($type === 'cta')
            <section class="mt-10 mb-10">
                <article class="card-editorial rounded-3xl p-8">
                    <p class="eyebrow mb-3">{{ $block['eyebrow'] ?? 'Siguiente paso' }}</p>
                    <h4 class="title-serif mb-4 text-3xl text-brown">{{ $block['heading'] ?? '' }}</h4>
                    <p class="mb-5 text-brown-soft">{{ $block['description'] ?? '' }}</p>
                    <a href="{{ $block['cta_url'] ?? '/#contacto' }}" class="btn-warm inline-block rounded-2xl px-6 py-3 text-sm font-semibold">{{ $block['cta_label'] ?? 'Ir al formulario' }}</a>
                </article>
            </section>
        @endif
    @endforeach
@endsection
