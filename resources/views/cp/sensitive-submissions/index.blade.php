<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datos sensibles</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cream text-brown antialiased">
    <main class="mx-auto max-w-6xl px-6 py-10">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="title-serif text-4xl">Submissions sensibles</h1>
            <a href="/cp/forms/contacto" class="link-warm text-sm font-semibold">Volver a Forms</a>
        </div>

        <div class="card-warm overflow-hidden rounded-2xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f2e4d2] text-brown">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Telefono</th>
                        <th class="px-4 py-3">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $item)
                        <tr class="border-t border-[#ead8c1]">
                            <td class="px-4 py-3">{{ $item->submitted_at }}</td>
                            <td class="px-4 py-3">{{ $item->nombre ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->email ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->telefono ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <a class="btn-warm rounded-full px-3 py-1 text-xs" href="{{ url(config('statamic.cp.route', 'cp').'/forms/contacto/sensitive-submissions/'.$item->id) }}">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-brown-soft">Aun no hay submissions cifrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $submissions->links() }}
        </div>
    </main>
</body>
</html>
