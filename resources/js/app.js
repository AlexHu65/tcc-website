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

initGalleryCarousels();
