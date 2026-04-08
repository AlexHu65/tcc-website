<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Psicologia Integral' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream text-brown antialiased">
    <header class="sticky top-0 z-50 border-b border-[#e6d7c4] bg-cream-soft/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
            <a href="/" class="title-serif text-2xl leading-none text-brown">{{ config('app.name', 'Psicologia Integral') }}</a>
            <nav class="flex items-center gap-6 text-sm font-medium">
                <a href="/" class="link-warm">Inicio</a>
                <a href="/blog" class="link-warm">Recursos</a>
                <a href="/#contacto" class="btn-warm rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-wide">Agenda hoy</a>
                <a href="/cp" class="rounded-full border border-[#d2b186] px-4 py-2 text-xs font-semibold uppercase tracking-wide text-brown-soft transition hover:bg-[#f2e5d6]">Admin</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-16">
        @yield('content')
    </main>

    <footer class="section-divider bg-cream-soft/70">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-6 py-8 text-sm text-brown-soft md:flex-row md:items-center md:justify-between">
            <p>Atencion psicologica con enfoque humano y profesional.</p>
            <p>Espacio de confianza, escucha y acompanamiento.</p>
        </div>
    </footer>

    <div class="mobile-cta fixed bottom-0 left-0 right-0 z-50 border-t border-[#e2c8a7] bg-cream-soft p-3 md:hidden">
        <a href="/#contacto" class="btn-warm block rounded-full px-5 py-3 text-center text-sm font-semibold">
            Agenda tu primera sesion
        </a>
    </div>

    <div id="desktopStickyCta" class="desktop-float-cta pointer-events-none fixed bottom-6 right-6 z-50 hidden translate-y-4 opacity-0 transition duration-300 md:block">
        <a href="/#contacto" class="btn-warm pointer-events-auto inline-block rounded-full px-6 py-3 text-sm font-semibold">
            Agenda tu sesion
        </a>
    </div>
</body>
</html>
