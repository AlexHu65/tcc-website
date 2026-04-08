<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle submission</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cream text-brown antialiased">
    <main class="mx-auto max-w-4xl px-6 py-10">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="title-serif text-4xl">Detalle submission</h1>
            <a href="{{ url(config('statamic.cp.route', 'cp').'/forms/contacto/sensitive-submissions') }}" class="link-warm text-sm font-semibold">Volver al listado</a>
        </div>

        <article class="card-editorial rounded-3xl p-8">
            <p class="mb-2"><strong>ID:</strong> {{ $submission->id }}</p>
            <p class="mb-2"><strong>Fecha:</strong> {{ $submission->submitted_at }}</p>
            <p class="mb-2"><strong>IP:</strong> {{ $submission->ip_address }}</p>
            <p class="mb-2"><strong>Nombre:</strong> {{ $payload['nombre'] ?? '-' }}</p>
            <p class="mb-2"><strong>Email:</strong> {{ $payload['email'] ?? '-' }}</p>
            <p class="mb-2"><strong>Telefono:</strong> {{ $payload['telefono'] ?? '-' }}</p>
            <p class="mb-2"><strong>Mensaje:</strong></p>
            <p class="rounded-xl bg-[#fff8f0] p-4 text-brown-soft">{{ $payload['mensaje'] ?? '-' }}</p>
        </article>
    </main>
</body>
</html>
