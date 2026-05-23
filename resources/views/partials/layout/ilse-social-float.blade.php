@php
    $pick = static function (?string $entry, ?string $fallback): ?string {
        $e = trim((string) ($entry ?? ''));
        if ($e !== '') {
            return $e;
        }
        $f = trim((string) ($fallback ?? ''));

        return $f !== '' ? $f : null;
    };

    $items = array_values(array_filter([
        [
            'url' => $pick($ilse_social_whatsapp ?? null, config('ilse.social.whatsapp')),
            'label' => 'WhatsApp',
            'class' => 'ilse-social-float__link--whatsapp',
            'icon' => 'whatsapp',
            'external' => true,
        ],
        [
            'url' => $pick($ilse_social_instagram ?? null, config('ilse.social.instagram')),
            'label' => 'Instagram',
            'class' => 'ilse-social-float__link--instagram',
            'icon' => 'instagram',
            'external' => true,
        ],
        [
            'url' => $pick($ilse_social_facebook ?? null, config('ilse.social.facebook')),
            'label' => 'Facebook',
            'class' => 'ilse-social-float__link--facebook',
            'icon' => 'facebook',
            'external' => true,
        ],
        [
            'url' => $pick($ilse_social_linkedin ?? null, config('ilse.social.linkedin')),
            'label' => 'LinkedIn',
            'class' => 'ilse-social-float__link--linkedin',
            'icon' => 'linkedin',
            'external' => true,
        ],
        [
            'url' => '#ilse-contact-modal',
            'label' => 'Abrir formulario de contacto',
            'class' => 'ilse-social-float__link--contact',
            'icon' => 'contact',
            'external' => false,
        ],
        [
            'url' => '/bienestar',
            'label' => 'Ir a bienestar',
            'class' => 'ilse-social-float__link--bienestar',
            'icon' => 'bienestar',
            'external' => false,
        ],
    ], fn ($row) => $row['url']));

    $contactSuccessMessage = session('contact_success') ?? session('success');
@endphp

<style>
    .ilse-social-float__link--contact:hover,
    .ilse-social-float__link--contact:focus-visible {
        color: var(--accent);
    }

    .ilse-social-float__link--bienestar:hover,
    .ilse-social-float__link--bienestar:focus-visible {
        color: var(--olive);
    }

    .ilse-contact-modal {
        position: fixed;
        inset: 0;
        z-index: 90;
        display: grid;
        place-items: center;
        padding: 24px;
        background: rgba(44, 36, 31, 0.5);
        opacity: 0;
        pointer-events: none;
        visibility: hidden;
        transition: opacity 0.22s ease, visibility 0.22s ease;
    }

    .ilse-contact-modal:target {
        opacity: 1;
        pointer-events: auto;
        visibility: visible;
    }

    .ilse-contact-modal__dialog {
        position: relative;
        width: min(560px, 100%);
        max-height: calc(100vh - 48px);
        overflow: auto;
        padding: clamp(24px, 5vw, 38px);
        border: 1px solid rgba(216, 204, 192, 0.9);
        border-radius: 28px;
        background: var(--surface);
        box-shadow: 0 24px 70px rgba(44, 36, 31, 0.22);
        color: var(--text);
    }

    .ilse-contact-modal__close {
        position: absolute;
        top: 16px;
        right: 16px;
        display: grid;
        place-items: center;
        width: 36px;
        height: 36px;
        border: 1px solid var(--line);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.72);
        color: var(--muted);
        font-size: 1.5rem;
        line-height: 1;
    }

    .ilse-contact-modal__title {
        margin: 0 44px 10px 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 500;
        line-height: 1;
        color: var(--text);
    }

    .ilse-contact-modal__intro {
        margin: 0 0 22px;
        color: var(--muted);
        line-height: 1.7;
    }

    .ilse-contact-modal .ilse-field label {
        color: var(--text);
    }

    .ilse-contact-modal .ilse-input,
    .ilse-contact-modal .ilse-textarea {
        border-color: var(--line);
        background: rgba(255, 255, 255, 0.74);
        color: var(--text);
    }

    .ilse-contact-modal .ilse-input::placeholder,
    .ilse-contact-modal .ilse-textarea::placeholder {
        color: rgba(110, 101, 93, 0.55);
    }

    .ilse-contact-modal__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin-top: 8px;
    }

    .ilse-contact-modal__backdrop {
        position: absolute;
        inset: 0;
        cursor: default;
    }
</style>

@if (count($items))
    <div class="ilse-social-float is-open" data-ilse-social-float aria-live="polite">
        <ul class="ilse-social-float__menu" id="ilse-social-float-menu" role="list" aria-hidden="false">
            @foreach ($items as $index => $row)
                <li class="ilse-social-float__item" style="--i: {{ $index }}">
                    <a
                        class="ilse-social-float__link {{ $row['class'] }}"
                        href="{{ $row['url'] }}"
                        @if ($row['external'])
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="{{ $row['label'] }} (se abre en una pestaña nueva)"
                        @else
                            aria-label="{{ $row['label'] }}"
                        @endif
                    >
                        <span class="ilse-social-float__link-icon" aria-hidden="true">
                            @if ($row['icon'] === 'whatsapp')
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            @elseif ($row['icon'] === 'instagram')
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="currentColor" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            @elseif ($row['icon'] === 'facebook')
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="currentColor" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            @elseif ($row['icon'] === 'linkedin')
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="currentColor" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            @elseif ($row['icon'] === 'contact')
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="currentColor" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="currentColor" d="M12 21.35 10.55 20.03C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z"/>
                                </svg>
                            @endif
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
        <button
            type="button"
            class="ilse-social-float__toggle"
            data-ilse-social-float-toggle
            aria-expanded="true"
            aria-controls="ilse-social-float-menu"
            aria-label="Cerrar enlaces a redes sociales"
        >
            <svg class="ilse-social-float__toggle-icon" viewBox="0 0 24 24" width="26" height="26" aria-hidden="true" focusable="false">
                <path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
        </button>
    </div>
@endif

<section id="ilse-contact-modal" class="ilse-contact-modal" aria-labelledby="ilse-contact-modal-title" role="dialog" aria-modal="true">
    <a class="ilse-contact-modal__backdrop" href="#" aria-label="Cerrar formulario de contacto"></a>

    <article class="ilse-contact-modal__dialog">
        <a class="ilse-contact-modal__close" href="#" aria-label="Cerrar formulario de contacto">&times;</a>

        <p class="eyebrow">Contacto</p>
        <h2 id="ilse-contact-modal-title" class="ilse-contact-modal__title">Agenda tu cita</h2>
        <p class="ilse-contact-modal__intro">Compártenos tus datos y nos pondremos en contacto contigo para acompañarte en el siguiente paso.</p>

        @if ($contactSuccessMessage)
            <p class="ilse-alert ilse-alert--success" style="color: var(--success);">{{ $contactSuccessMessage }}</p>
        @endif

        @if ($errors->any())
            <p class="ilse-alert ilse-alert--error">Revisa los datos del formulario e intenta de nuevo.</p>
        @endif

        <form action="{{ route('statamic.forms.submit', ['form' => 'contacto']) }}" method="POST">
            @csrf
            <input type="hidden" name="_redirect" value="{{ url('/#ilse-contact-modal') }}">
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

            <div class="ilse-field">
                <label for="ilse-float-nombre">Nombre</label>
                <input id="ilse-float-nombre" name="nombre" value="{{ old('nombre') }}" class="ilse-input" required autocomplete="name">
            </div>

            <div class="ilse-field">
                <label for="ilse-float-telefono">Teléfono</label>
                <input id="ilse-float-telefono" name="telefono" value="{{ old('telefono') }}" class="ilse-input" required autocomplete="tel">
            </div>

            <div class="ilse-field">
                <label for="ilse-float-email">Email</label>
                <input id="ilse-float-email" name="email" type="email" value="{{ old('email') }}" class="ilse-input" required autocomplete="email">
            </div>

            <div class="ilse-field">
                <label for="ilse-float-mensaje">Mensaje</label>
                <textarea id="ilse-float-mensaje" name="mensaje" rows="4" class="ilse-textarea" required>{{ old('mensaje') }}</textarea>
            </div>

            <div class="ilse-contact-modal__actions">
                <button type="submit" class="btn">Enviar solicitud</button>
                <a class="btn secondary" href="#">Cancelar</a>
            </div>
        </form>
    </article>
</section>
