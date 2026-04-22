import Swiper from 'swiper';
import { A11y, Keyboard, Navigation, Pagination } from 'swiper/modules';

const INITIALIZED_ATTR = 'data-gallery-initialized';

const initCarousel = (carouselElement) => {
    if (carouselElement.getAttribute(INITIALIZED_ATTR) === 'true') {
        return;
    }

    new Swiper(carouselElement, {
        modules: [Navigation, Pagination, Keyboard, A11y],
        slidesPerView: 1.1,
        spaceBetween: 16,
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
        navigation: {
            nextEl: carouselElement.querySelector('.swiper-button-next'),
            prevEl: carouselElement.querySelector('.swiper-button-prev'),
        },
        pagination: {
            el: carouselElement.querySelector('.swiper-pagination'),
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 2.6,
                spaceBetween: 24,
            },
        },
    });

    carouselElement.setAttribute(INITIALIZED_ATTR, 'true');
};

export const initGalleryCarousels = (scope = document) => {
    const carousels = scope.querySelectorAll('[data-gallery-carousel]');
    carousels.forEach((carouselElement) => initCarousel(carouselElement));
};
