<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('meta_title')@yield('meta_title')@else{{ $ilse_meta_title ?? $title ?? config('app.name', 'Psicologia Integral') }}@endif</title>
    <meta name="description" content="@yield('meta_description', $ilse_meta_description ?? '')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/ilse.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="ilse-shell">
    @include('partials.layout.ilse-header')

    <main class="{{ request()->is('/') ? 'ilse-main' : 'inner-page-main' }}">
        @yield('content')
    </main>

    @include('partials.layout.ilse-footer')
    @include('partials.layout.ilse-social-float')

    @stack('scripts')
</body>
</html>
