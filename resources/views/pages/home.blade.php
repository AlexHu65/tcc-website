@extends('layout')

@section('content')
    <section class="card-editorial fade-in-up relative mb-16 overflow-hidden rounded-3xl p-10 md:p-16">
        <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[#e9d2b3]/30 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-12 h-56 w-56 rounded-full bg-[#d8b389]/20 blur-2xl"></div>
        <p class="eyebrow mb-4">Psicologia contemporanea</p>
        <h1 class="title-serif mb-6 max-w-3xl text-5xl font-semibold leading-tight text-brown md:text-6xl">
            {{ $hero_title ?? 'Un espacio seguro para reconectar contigo' }}
        </h1>
        <p class="max-w-3xl text-xl leading-relaxed text-brown-soft">
            {{ $hero_subtitle ?? 'Te acompanamos con un enfoque profesional y humano para ayudarte a recuperar calma, claridad y equilibrio en cada etapa de tu proceso.' }}
        </p>
        <div class="mt-9 flex flex-wrap gap-3">
            <a href="/blog" class="btn-warm rounded-full px-5 py-3 text-sm font-semibold">Explorar recursos</a>
            <a href="/cp" class="rounded-full border border-[#d2b186] bg-white px-5 py-3 text-sm font-semibold text-brown-soft transition hover:bg-[#f4e8da]">Solicitar primera sesion</a>
        </div>
    </section>

    <section class="fade-in-up-delay mb-14 grid gap-6 md:grid-cols-3">
        <article class="card-warm rounded-2xl p-6">
            <h2 class="mb-2 text-xl font-semibold text-brown">Terapia individual</h2>
            <p class="content-prose text-sm leading-relaxed text-brown-soft">Acompanamiento cercano para ansiedad, estres, duelos o transiciones personales.</p>
        </article>
        <article class="card-warm rounded-2xl p-6">
            <h2 class="mb-2 text-xl font-semibold text-brown">Terapia de pareja</h2>
            <p class="content-prose text-sm leading-relaxed text-brown-soft">Mejora la comunicacion, fortalece acuerdos y recupera el vinculo emocional.</p>
        </article>
        <article class="card-warm rounded-2xl p-6">
            <h2 class="mb-2 text-xl font-semibold text-brown">Orientacion familiar</h2>
            <p class="content-prose text-sm leading-relaxed text-brown-soft">Herramientas practicas para gestionar conflictos y crear dinamicas saludables.</p>
        </article>
    </section>

    <section class="card-warm section-divider rounded-3xl p-8 md:p-12">
        <p class="eyebrow mb-3">Metodo de trabajo</p>
        <h3 class="title-serif mb-6 text-4xl font-semibold text-brown">Un proceso claro, respetuoso y personalizado</h3>
        <div class="prose content-prose max-w-none prose-p:text-brown-soft prose-headings:text-brown">
            {!! \Statamic\Statamic::modify($content ?? '')->markdown() !!}
        </div>
    </section>

    <section class="mt-14 grid gap-6 md:grid-cols-3">
        <article class="card-warm rounded-2xl p-6">
            <p class="eyebrow mb-2">Paso 1</p>
            <h4 class="mb-2 text-xl font-semibold text-brown">Primera llamada</h4>
            <p class="text-brown-soft">Escuchamos tu contexto y definimos objetivos claros para iniciar el proceso con seguridad.</p>
        </article>
        <article class="card-warm rounded-2xl p-6">
            <p class="eyebrow mb-2">Paso 2</p>
            <h4 class="mb-2 text-xl font-semibold text-brown">Plan personalizado</h4>
            <p class="text-brown-soft">Disenamos un plan terapeutico adaptado a tus necesidades emocionales y ritmo de vida.</p>
        </article>
        <article class="card-warm rounded-2xl p-6">
            <p class="eyebrow mb-2">Paso 3</p>
            <h4 class="mb-2 text-xl font-semibold text-brown">Seguimiento continuo</h4>
            <p class="text-brown-soft">Ajustamos herramientas y sesiones para que avances con estabilidad y resultados sostenibles.</p>
        </article>
    </section>

    <section class="mt-14 grid gap-6 md:grid-cols-2">
        <article class="card-warm rounded-3xl p-8">
            <p class="eyebrow mb-3">Testimonios</p>
            <h4 class="title-serif mb-5 text-3xl text-brown">Historias de cambio real</h4>
            <div class="space-y-5 text-brown-soft">
                <blockquote class="rounded-xl bg-[#fff8f0] p-4">
                    "Volvi a dormir tranquila y a organizar mis emociones en pocas semanas."
                    <footer class="mt-2 text-sm font-semibold text-brown">- Laura, 34 anos</footer>
                </blockquote>
                <blockquote class="rounded-xl bg-[#fff8f0] p-4">
                    "Aprendimos a comunicarnos sin herirnos. La terapia nos devolvio claridad."
                    <footer class="mt-2 text-sm font-semibold text-brown">- Andrea y Miguel</footer>
                </blockquote>
            </div>
        </article>

        <article id="contacto" class="card-editorial rounded-3xl p-8">
            <p class="eyebrow mb-3">Contacto</p>
            <h4 class="title-serif mb-5 text-3xl text-brown">Agenda tu primera sesion</h4>

            @if (session('success'))
                <p class="mb-4 rounded-lg border border-[#c8ddb6] bg-[#edf7e4] px-4 py-3 text-sm text-[#36551f]">
                    {{ session('success') }}
                </p>
            @endif

            @if ($errors->any())
                <p class="mb-4 rounded-lg border border-[#e1c1b0] bg-[#fff1ea] px-4 py-3 text-sm text-[#7a3f22]">
                    Revisa los datos del formulario e intenta de nuevo.
                </p>
            @endif

            @php
                $showStepTwo = $errors->any() || old('mensaje');
            @endphp

            <form action="/contacto" method="POST" class="space-y-4" data-contact-form>
                @csrf
                <div class="{{ $showStepTwo ? 'hidden' : '' }}" data-step="1">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-brown-soft">Paso 1 de 2</p>
                    <div class="space-y-4">
                        <div>
                            <label for="nombre" class="mb-1 block text-sm font-medium text-brown">Nombre</label>
                            <input id="nombre" name="nombre" value="{{ old('nombre') }}" class="w-full rounded-xl border border-[#d9c2a3] bg-white px-4 py-3 text-sm text-brown outline-none transition focus:border-[#c18a3b]" required>
                        </div>
                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-brown">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-xl border border-[#d9c2a3] bg-white px-4 py-3 text-sm text-brown outline-none transition focus:border-[#c18a3b]" required>
                        </div>
                    </div>
                    <button type="button" class="btn-warm mt-4 w-full rounded-full px-5 py-3 text-sm font-semibold" data-next-step>
                        Continuar
                    </button>
                </div>

                <div class="{{ $showStepTwo ? '' : 'hidden' }}" data-step="2">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-brown-soft">Paso 2 de 2</p>
                    <div>
                        <label for="mensaje" class="mb-1 block text-sm font-medium text-brown">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="4" class="w-full rounded-xl border border-[#d9c2a3] bg-white px-4 py-3 text-sm text-brown outline-none transition focus:border-[#c18a3b]" required>{{ old('mensaje') }}</textarea>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="button" class="w-1/2 rounded-full border border-[#d2b186] bg-white px-5 py-3 text-sm font-semibold text-brown-soft transition hover:bg-[#f4e8da]" data-prev-step>
                            Volver
                        </button>
                        <button class="btn-warm w-1/2 rounded-full px-5 py-3 text-sm font-semibold">Enviar solicitud</button>
                    </div>
                </div>
            </form>
        </article>
    </section>

    <section class="mt-10 grid gap-6 md:grid-cols-3">
        <article class="card-warm rounded-2xl p-6 text-center">
            <p class="title-serif text-4xl font-semibold text-brown">+350</p>
            <p class="mt-1 text-sm text-brown-soft">Sesiones acompanadas</p>
        </article>
        <article class="card-warm rounded-2xl p-6 text-center">
            <p class="title-serif text-4xl font-semibold text-brown">92%</p>
            <p class="mt-1 text-sm text-brown-soft">Clientes que reportan mayor calma</p>
        </article>
        <article class="card-warm rounded-2xl p-6 text-center">
            <p class="title-serif text-4xl font-semibold text-brown">&lt;24h</p>
            <p class="mt-1 text-sm text-brown-soft">Tiempo promedio de respuesta</p>
        </article>
    </section>

    <section class="mt-10 grid gap-6 md:grid-cols-2">
        <article class="card-warm rounded-3xl p-8">
            <p class="eyebrow mb-3">Preguntas frecuentes</p>
            <h4 class="title-serif mb-5 text-3xl text-brown">Resolvemos tus dudas</h4>
            <div class="space-y-3">
                <details class="rounded-xl border border-[#e4cfb2] bg-[#fff8f0] p-4">
                    <summary class="cursor-pointer font-semibold text-brown">Cuanto dura una sesion?</summary>
                    <p class="mt-2 text-sm text-brown-soft">Las sesiones duran entre 50 y 60 minutos segun el plan terapeutico.</p>
                </details>
                <details class="rounded-xl border border-[#e4cfb2] bg-[#fff8f0] p-4">
                    <summary class="cursor-pointer font-semibold text-brown">La terapia puede ser online?</summary>
                    <p class="mt-2 text-sm text-brown-soft">Si. Trabajamos en modalidad presencial y online con la misma estructura profesional.</p>
                </details>
                <details class="rounded-xl border border-[#e4cfb2] bg-[#fff8f0] p-4">
                    <summary class="cursor-pointer font-semibold text-brown">En cuanto tiempo vere avances?</summary>
                    <p class="mt-2 text-sm text-brown-soft">Cada proceso es unico, pero generalmente se perciben cambios en las primeras semanas.</p>
                </details>
            </div>
        </article>
        <article class="card-editorial rounded-3xl p-8">
            <p class="eyebrow mb-3">Siguiente paso</p>
            <h4 class="title-serif mb-4 text-3xl text-brown">Empieza hoy tu proceso</h4>
            <p class="mb-5 text-brown-soft">
                Si ya identificas que necesitas apoyo, agenda una primera sesion y definimos juntas(os) la mejor ruta para ti.
            </p>
            <a href="/#contacto" class="btn-warm inline-block rounded-full px-6 py-3 text-sm font-semibold">Ir al formulario</a>
        </article>
    </section>
@endsection
