@php
    $ilse_logo_asset = $ilse_logo_asset ?? asset('images/ilse/a_minimalist_elegant_logo_brand_mark_on_a_pale_be.png');
@endphp
<header>
    <div class="container nav">
        <a class="brand" href="/#inicio">
            <img src="{{ $ilse_logo_asset }}" alt="{{ $ilse_logo_alt ?? 'Logo de Ilse Méndez' }}" width="74" height="74" />
            <div class="brand-copy">
                <h1>{{ $ilse_brand_title ?? 'Ilse Méndez' }}</h1>
                <p>{{ $ilse_brand_tagline ?? 'Psicoterapia Cognitivo Conductual' }}</p>
            </div>
        </a>

        <button
            type="button"
            class="nav-toggle"
            aria-expanded="false"
            aria-controls="main-nav"
            aria-label="Abrir menú de navegación"
        >
            <span class="nav-toggle-bars" aria-hidden="true"></span>
        </button>

        <nav id="main-nav" class="menu" aria-label="Principal">
            <a class="menu-link--home" href="/#inicio">{{ $ilse_nav_inicio ?? 'Inicio' }}</a>
            <a href="/#enfoque">{{ $ilse_nav_enfoque ?? 'Enfoque' }}</a>
            <a href="/#servicios">{{ $ilse_nav_servicios ?? 'Servicios' }}</a>
            <a href="/#sobre-mi">{{ $ilse_nav_sobre ?? 'Sobre mí' }}</a>
            <a href="/blog">{{ $ilse_nav_blog ?? 'Recursos' }}</a>
            <a href="{{ route('bienestar') }}">{{ $ilse_nav_bienestar ?? 'Bienestar' }}</a>
            <!--<a href="/#contacto">{{ $ilse_nav_contacto ?? 'Contacto' }}</a>-->
        </nav>

        <a class="btn nav-cta" href="/#contacto">{{ $ilse_header_cta_label ?? 'Agenda tu cita' }}</a>
    </div>
</header>
