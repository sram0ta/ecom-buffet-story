window.addEventListener('DOMContentLoaded', function() {
    galleryBanner();
    galleryReviews();
    catalogCategoryToggle();
    reviewsToggle();
    initAirPickers();
});

document.addEventListener('wpcf7mailsent', initAirPickers);
document.addEventListener('wpcf7invalid', initAirPickers);

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

function catalogCategoryToggle() {
    if (document.querySelector('.catalog__filters__category__title')) {
        document.addEventListener("click", (e) => {
            const title = e.target.closest(".catalog__filters__category__title");
            if (!title) return;

            const category = title.closest(".catalog__filters__category");
            const list = category.querySelector(".catalog__filters__category__list");
            if (!list) return;

            const isOpen = category.classList.contains("active");

            if (isOpen) {
                list.style.maxHeight = list.scrollHeight + "px";
                requestAnimationFrame(() => {
                    list.style.maxHeight = "0px";
                });
                category.classList.remove("active");
            } else {
                // открыть
                list.style.maxHeight = list.scrollHeight + "px";
                category.classList.add("active");

                list.addEventListener(
                    "transitionend",
                    function handler() {
                        if (category.classList.contains("active")) {
                            list.style.maxHeight = "none";
                        }
                        list.removeEventListener("transitionend", handler);
                    }
                );
            }
        });
    }
}

const reviewsToggle = () => {
    if (document.querySelector('.questions__item')) {
        const items = document.querySelectorAll(".questions__item");
        const contents = document.querySelectorAll(".questions__content__item");

        if (!items.length || !contents.length) return;

        function clearActive() {
            items.forEach((el) => el.classList.remove("active"));
            contents.forEach((el) => el.classList.remove("active"));
        }

        function setActive(index) {
            clearActive();
            if (items[index]) items[index].classList.add("active");
            if (contents[index]) contents[index].classList.add("active");
        }

        items.forEach((item, index) => {
            item.addEventListener("mouseenter", () => {
                setActive(index);
            });
        });

        setActive(0);
    }
}

function initAirPickers() {
    document.querySelectorAll('input.date').forEach(function (el) {
        if (el.dataset.apInited) return;

        new AirDatepicker(el, {
            autoClose: true,
            dateFormat: 'dd.MM.yyyy',
            locale: AirDatepicker.locales?.ru || {},
            minDate: new Date()
        });

        el.dataset.apInited = '1';
    });
}

