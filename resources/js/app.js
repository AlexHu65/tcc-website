import './bootstrap';

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
        const emailInput = contactForm.querySelector('#email');

        const isNameValid = nameInput && nameInput.value.trim().length > 0;
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

        showStep(2);
    });

    prevButton?.addEventListener('click', () => showStep(1));
}
