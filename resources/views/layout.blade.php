<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Psicologia Integral' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream font-sans text-brown antialiased">
    <header class="sticky top-0 z-50 bg-cream/80 shadow-[0_1px_0_rgba(90,63,42,0.04)] backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6 md:py-7">
            <a href="/" class="title-serif text-xl tracking-tight text-brown md:text-2xl">{{ config('app.name', 'Psicologia Integral') }}</a>
            <nav class="flex flex-wrap items-center gap-6 text-sm font-medium tracking-tight md:gap-8">
                @if (isset($mainNavPages) && $mainNavPages->isNotEmpty())
                    @foreach ($mainNavPages as $navPage)
                        <a href="{{ $navPage->url() }}" class="link-warm">{{ $navPage->title() }}</a>
                    @endforeach
                @else
                    <a href="/" class="link-warm">Inicio</a>
                    <a href="/blog" class="link-warm">Recursos</a>
                @endif
                <a href="/#contacto" class="btn-warm rounded-2xl px-5 py-2.5 text-xs font-semibold">Agenda hoy</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-20 md:py-24">
        @yield('content')
    </main>

    <footer class="mt-8 bg-cream-soft/50">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-6 py-12 text-sm text-brown-soft md:flex-row md:items-center md:justify-between">
            <p>Atencion psicologica con enfoque humano y profesional.</p>
            <p>Espacio de confianza, escucha y acompanamiento.</p>
        </div>
    </footer>

    <div class="mobile-cta fixed bottom-0 left-0 right-0 z-50 bg-cream/95 p-4 backdrop-blur-md md:hidden">
        <a href="/#contacto" class="btn-warm block rounded-2xl px-5 py-3.5 text-center text-sm font-semibold">
            Agenda tu primera sesion
        </a>
    </div>

    <div id="desktopStickyCta" class="desktop-float-cta pointer-events-none fixed bottom-6 right-6 z-50 hidden translate-y-4 opacity-0 transition duration-300 md:block">
        <a href="/#contacto" class="btn-warm pointer-events-auto inline-block rounded-2xl px-6 py-3 text-sm font-semibold">
            Agenda tu sesion
        </a>
    </div>
</body>
</html>
