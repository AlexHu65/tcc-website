@php
    $footerContact = $ilse_footer_contact_lines ?? [];
    $ilse_logo_asset = $ilse_logo_asset ?? asset('images/ilse/a_minimalist_elegant_logo_brand_mark_on_a_pale_be.png');
@endphp
<footer>
    <div class="container">
        <div class="footer-card">
            <div>
                <div class="footer-brand">
                    <img src="{{ $ilse_logo_asset }}" alt="{{ $ilse_logo_alt ?? 'Logo de Ilse Méndez' }}" width="88" height="88" />
                    <div>
                        <h3 style="margin:0; font-family: 'Cormorant Garamond', serif; font-size: 2rem; font-weight: 500;">{{ $ilse_brand_title ?? 'Ilse Méndez' }}</h3>
                        <p style="margin:4px 0 0;">{{ $ilse_brand_tagline ?? 'Psicoterapia Cognitivo Conductual' }}</p>
                    </div>
                </div>
                <p style="margin-top:18px;">{{ $ilse_footer_blurb ?? 'Base inicial para comenzar el desarrollo de la landing page con una línea visual limpia, sofisticada y emocionalmente cálida.' }}</p>
            </div>

            <div class="footer-links">
                <div class="footer-title">{{ $ilse_footer_sections_title ?? 'Secciones' }}</div>
                <a href="/#inicio">{{ $ilse_nav_inicio ?? 'Inicio' }}</a>
                <a href="/#enfoque">{{ $ilse_nav_enfoque ?? 'Enfoque' }}</a>
                <a href="/#servicios">{{ $ilse_nav_servicios ?? 'Servicios' }}</a>
                <a href="/#sobre-mi">{{ $ilse_nav_sobre ?? 'Sobre mí' }}</a>
                <a href="/blog">{{ $ilse_nav_blog ?? 'Recursos' }}</a>
            </div>

            <div class="footer-contact">
                <div class="footer-title">{{ $ilse_footer_contact_title ?? 'Contacto' }}</div>
                @forelse ($footerContact as $line)
                    <span>{{ is_array($line) ? ($line['line'] ?? '') : $line }}</span>
                @empty
                    <span>{{ $ilse_footer_whatsapp_line ?? 'WhatsApp: pendiente de integrar' }}</span>
                    <span>{{ $ilse_footer_email_line ?? 'Email: hola@ilsemendezpsico.com' }}</span>
                    <span>{{ $ilse_footer_modalidad_line ?? 'Sesiones presenciales y en línea' }}</span>
                @endforelse
            </div>
        </div>
        <div class="copyright">{{ $ilse_copyright ?? '© 2026 Ilse Méndez. Archivo base para implementación visual.' }}</div>
    </div>
</footer>
