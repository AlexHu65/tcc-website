import './bootstrap';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { initGalleryCarousels } from './modules/gallery-carousel';

const desktopStickyCta = document.getElementById('desktopStickyCta');
if (desktopStickyCta) {
    const toggleDesktopCta = () => {
        if (window.innerWidth < 768) {
            desktopStickyCta.classList.remove('is-visible');
            return;
        }

        const shouldShow = window.scrollY > 480;
        desktopStickyCta.classList.toggle('is-visible', shouldShow);
    };

    window.addEventListener('scroll', toggleDesktopCta, { passive: true });
    window.addEventListener('resize', toggleDesktopCta);
    toggleDesktopCta();
}

const contactForm = document.querySelector('[data-contact-form]');
if (contactForm) {
    const stepOne = contactForm.querySelector('[data-step="1"]');
    const stepTwo = contactForm.querySelector('[data-step="2"]');
    const nextButton = contactForm.querySelector('[data-next-step]');
    const prevButton = contactForm.querySelector('[data-prev-step]');

    const showStep = (step) => {
        const isStepOne = step === 1;
        stepOne?.classList.toggle('hidden', !isStepOne);
        stepTwo?.classList.toggle('hidden', isStepOne);
    };

    nextButton?.addEventListener('click', () => {
        const nameInput = contactForm.querySelector('#nombre');
        const phoneInput = contactForm.querySelector('#telefono');
        const emailInput = contactForm.querySelector('#email');

        const isNameValid = nameInput && nameInput.value.trim().length > 0;
        const isPhoneValid = phoneInput && phoneInput.value.trim().length > 0;
        const isEmailValid = emailInput && emailInput.checkValidity();

        if (!isNameValid) {
            nameInput?.focus();
            return;
        }

        if (!isEmailValid) {
            emailInput?.focus();
            emailInput?.reportValidity();
            return;
        }

        if (!isPhoneValid) {
            phoneInput?.focus();
            return;
        }

        showStep(2);
    });

    prevButton?.addEventListener('click', () => showStep(1));

    contactForm.addEventListener('submit', (event) => {
        if (contactForm.dataset.submitting === 'true') {
            event.preventDefault();
            return;
        }

        contactForm.dataset.submitting = 'true';

        const submitButton = contactForm.querySelector('button[type="submit"], button:not([type])');
        if (submitButton) {
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.classList.add('opacity-60', 'cursor-not-allowed');
            submitButton.textContent = 'Enviando...';
        }
    });
}

const ilseToast = document.querySelector('[data-ilse-toast]');
if (ilseToast) {
    const closeToast = () => {
        ilseToast.classList.add('is-hiding');
        window.setTimeout(() => ilseToast.remove(), 220);
    };

    ilseToast.querySelector('[data-ilse-toast-close]')?.addEventListener('click', closeToast);
    window.setTimeout(closeToast, 6500);
}

initGalleryCarousels();

const navToggle = document.querySelector('.nav-toggle');
const mainNav = document.getElementById('main-nav');
if (navToggle && mainNav) {
    const setNavOpen = (open) => {
        mainNav.classList.toggle('is-open', open);
        navToggle.setAttribute('aria-expanded', open);
        navToggle.setAttribute(
            'aria-label',
            open ? 'Cerrar menú de navegación' : 'Abrir menú de navegación'
        );
    };

    navToggle.addEventListener('click', () => {
        setNavOpen(!mainNav.classList.contains('is-open'));
    });

    mainNav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setNavOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setNavOpen(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            setNavOpen(false);
        }
    });

    document.addEventListener('click', (event) => {
        if (window.innerWidth > 1024 || !mainNav.classList.contains('is-open')) {
            return;
        }
        const target = event.target;
        if (
            target instanceof Node &&
            (navToggle.contains(target) || mainNav.contains(target))
        ) {
            return;
        }
        setNavOpen(false);
    });
}

const ilseSocialFloat = document.querySelector('[data-ilse-social-float]');
if (ilseSocialFloat) {
    const toggle = ilseSocialFloat.querySelector('[data-ilse-social-float-toggle]');
    const menu = ilseSocialFloat.querySelector('#ilse-social-float-menu');
    const links = menu?.querySelectorAll('a') ?? [];

    const setOpen = (open) => {
        ilseSocialFloat.classList.toggle('is-open', open);
        if (toggle) {
            toggle.setAttribute('aria-expanded', open);
            toggle.setAttribute(
                'aria-label',
                open ? 'Cerrar enlaces a redes sociales' : 'Abrir enlaces a redes sociales'
            );
        }
        if (menu) {
            menu.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
        links.forEach((a) => {
            if (open) {
                a.removeAttribute('tabindex');
            } else {
                a.setAttribute('tabindex', '-1');
            }
        });
    };

    toggle?.addEventListener('click', () => {
        setOpen(!ilseSocialFloat.classList.contains('is-open'));
    });

    document.addEventListener('click', (event) => {
        if (!ilseSocialFloat.classList.contains('is-open')) {
            return;
        }
        const target = event.target;
        if (target instanceof Node && ilseSocialFloat.contains(target)) {
            return;
        }
        setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && ilseSocialFloat.classList.contains('is-open')) {
            setOpen(false);
            toggle?.focus();
        }
    });
}
