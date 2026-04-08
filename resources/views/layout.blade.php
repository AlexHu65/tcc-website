<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
            <a href="/" class="text-lg font-semibold">{{ config('app.name', 'MVP Site') }}</a>
            <nav class="flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="/" class="hover:text-slate-900">Home</a>
                <a href="/blog" class="hover:text-slate-900">Blog</a>
                <a href="/cp" class="rounded-md bg-slate-900 px-3 py-1.5 text-white hover:bg-slate-700">Admin</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-12">
        @yield('content')
    </main>
</body>
</html>
