window.addEventListener('DOMContentLoaded', function() {
    galleryBanner();
    galleryReviews();
});

const galleryReviews = () => {
    if (document.querySelector('#reviews-gallery')) {
        const swiper = new Swiper("#reviews-gallery", {
            slidesPerView: 4,
            speed: 500,
            loop: true,
            navigation: {
                prevEl: '.reviews__gallery ._prev',
                nextEl: '.reviews__gallery ._next',
            },
        });
    }
}

const galleryBanner = () => {
    if (document.querySelector('#banner-gallery')) {
        const swiper = new Swiper("#banner-gallery", {
            slidesPerView: 1,
            speed: 500,
            loop: false,
            effect: 'fade',
            autoplay: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    }
}
