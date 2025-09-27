window.addEventListener('DOMContentLoaded', function() {
    openMenu();
    advantagesGallery();
    platformsGallery();
    headerScroll();
    galleryBanner();
    galleryReviews();
    catalogCategoryToggle();
    reviewsToggle();
    initAirPickers();
    eventSelect();
    productGallery();
    productTab();
    formCheck();
});

document.addEventListener('wpcf7mailsent', initAirPickers);
document.addEventListener('wpcf7invalid', initAirPickers);

class DomUtils {
    static remToPx(rem) {
        return parseFloat(getComputedStyle(document.documentElement).fontSize) * rem;
    }
}

const headerScroll = () => {
    const header = document.getElementById('header');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            header.classList.add('scroll');
        } else {
            header.classList.remove('scroll');
        }
    });
}

const openMenu = () => {
    if (document.querySelector('.header__menu')) {
        document.querySelector('.header__menu').addEventListener('click', () => {
            document.querySelector('.header__menu').classList.toggle('active');
            document.querySelector('.menu-navigation').classList.toggle('active');
            document.querySelector('body').classList.toggle('fixed');
        })
    }
}

const advantagesGallery = () => {
    if (window.innerWidth > 1024) return;

    if (document.querySelector('#advantages-gallery')) {
        const swiper = new Swiper("#advantages-gallery", {
            slidesPerView: 2,
            spaceBetween: DomUtils.remToPx(.4),
            speed: 500,
            loop: true,
            navigation: {
                prevEl: '.advantages ._prev',
                nextEl: '.advantages ._next',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2.5,
                },
            }
        });
    }
}

const platformsGallery = () => {
    if (window.innerWidth > 1024) return;

    if (document.querySelector('#platforms-gallery')) {
        const swiper = new Swiper("#platforms-gallery", {
            slidesPerView: 1,
            spaceBetween: DomUtils.remToPx(.8),
            speed: 500,
            loop: true,
            navigation: {
                prevEl: '.platforms ._prev',
                nextEl: '.platforms ._next',
            },
            breakpoints: {
                576: {
                    slidesPerView: 1.7,
                },
            }
        });
    }
}

const galleryReviews = () => {
    if (document.querySelector('#reviews-gallery')) {
        const swiper = new Swiper("#reviews-gallery", {
            slidesPerView: 1.2, // по умолчанию (мобильная версия)
            spaceBetween: DomUtils.remToPx(.4),
            speed: 500,
            loop: true,
            navigation: {
                prevEl: '.reviews__gallery ._prev',
                nextEl: '.reviews__gallery ._next',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2.2,
                    spaceBetween: DomUtils.remToPx(1.2),
                },
                1024: {
                    slidesPerView: 4,
                }
            }
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

const eventSelect = () => {
    if (document.querySelector('.select-event')) {
        new Choices('.select-event', {
            searchEnabled: false,
            itemSelectText: '',
        });
    }
}



const productGallery = () => {
    if (document.querySelector('.product__information__gallery__thumbs')) {

        const thumbs = new Swiper('.product__information__gallery__thumbs .swiper', {
            direction: 'vertical',
            slidesPerView: 2,
            spaceBetween: DomUtils.remToPx(1.2),
            watchSlidesProgress: true,
        });

        const main = new Swiper('.product__information__gallery__main .swiper', {
            slidesPerView: 1,
            effect: 'fade',
            thumbs: { swiper: thumbs },
            navigation: {
                nextEl: '.product__information__gallery ._next',
                prevEl: '.product__information__gallery ._prev',
            },
        });
    }
};

const productTab = () => {
    if (document.querySelector('.tabs__item')) {
        const tabs = document.querySelectorAll('.tabs__item');
        const contents = document.querySelectorAll('.tabs__content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.tab;

                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                const content = document.querySelector(`.tabs__content[data-content="${target}"]`);
                if (content) content.classList.add('active');
            });
        });
    }
}

const formCheck = () => {
    const form = document.querySelector('.wpcf7 form');
    if (!form) return;

    const toggleBtn = form.querySelector('.form__check');
    const hiddenCheckbox = form.querySelector('.form__check_hide input[type="checkbox"]');
    const submitBtn = form.querySelector('.form__button');

    if (toggleBtn && hiddenCheckbox) {
        toggleBtn.addEventListener('click', () => {
            hiddenCheckbox.checked = !hiddenCheckbox.checked;
            toggleBtn.classList.toggle('active', hiddenCheckbox.checked);
            toggleBtn.classList.remove('error');
        });
    }

    if (submitBtn && hiddenCheckbox && toggleBtn) {
        submitBtn.addEventListener('click', (e) => {
            if (!hiddenCheckbox.checked) {
                toggleBtn.classList.add('error');
            }
        });
    }
}

class CatalogFilter {
    constructor(listSelector = '.catalog__list') {
        this.list = document.querySelector(listSelector);
        if (!this.list) return;

        this.slugToId = new Map();
        this.bindEvents();
        this.initFromURL();
    }

    qsa(sel){ return Array.from(document.querySelectorAll(sel)); }
    qs(sel){ return document.querySelector(sel); }

    clearActives() {
        this.qsa(
            '.catalog__category__button.active, ' +
            '.catalog__filters__individual.active, ' +
            '.catalog__filters__category__item.active'
        ).forEach(el => el.classList.remove('active'));
    }

    getParentBtn(childBtn) {
        const pid = childBtn?.dataset?.parentId;
        if (pid) {
            const parentBtn = this.qs(`.catalog__category__button[data-category-id="${pid}"]`);
            if (parentBtn) return parentBtn;
        }
        const holder = childBtn?.closest('.catalog__filters__category[data-category]');
        if (holder) {
            const pid2 = holder.getAttribute('data-category');
            if (pid2) {
                const parentBtn = this.qs(`.catalog__category__button[data-category-id="${pid2}"]`);
                if (parentBtn) return parentBtn;
            }
        }
        return null;
    }

    applyActive(btn, alsoParent = false) {
        this.clearActives();
        if (btn) {
            btn.classList.add('active');
            if (alsoParent) {
                const parentBtn = this.getParentBtn(btn);
                if (parentBtn) parentBtn.classList.add('active');
            }
        }
    }

    setLoading(on) {
        if (on) {
            const prevHeight = this.list.offsetHeight;
            this.list.style.minHeight = prevHeight + 'px';
            this.list.classList.add('is-loading');
            this.list.innerHTML = '<div class="catalog__loading">Загрузка…</div>';
        } else {
            this.list.classList.remove('is-loading');
            this.list.style.minHeight = '';
        }
    }

    getTermFromURL() {
        const url = new URL(window.location.href);
        const qp  = url.searchParams.get('product_cat');
        if (qp) return qp;

        const parts = window.location.pathname.split('/').filter(Boolean);
        if (!parts.length) return null;

        const bases = new Set(['product-category', 'catalog']);
        for (let i = 0; i < parts.length; i++) {
            if (bases.has(parts[i])) {
                const tail = parts.slice(i + 1).filter(Boolean);
                if (tail.length) return tail[tail.length - 1];
                return null;
            }
        }
        const last = parts[parts.length - 1];
        if (!['product-category', 'catalog'].includes(last)) return last;
        return null;
    }

    pushCategoryURL(slug, urlOverride = null) {
        if (slug) {
            const finalUrl = urlOverride || (() => {
                const u = new URL(window.location.href);
                u.searchParams.set('product_cat', slug);
                return u.toString();
            })();
            history.pushState({ product_cat: slug }, '', finalUrl);
            return;
        }

        if (window.CATFILTER?.base_url) {
            history.pushState({ product_cat: null }, '', CATFILTER.base_url);
            return;
        }

        const u = new URL(window.location.href);
        u.searchParams.delete('product_cat');
        history.pushState({ product_cat: null }, '', u.toString());
    }

    async fetchProductsByTermId(termId) {
        const fd = new FormData();
        fd.append('action', 'buffet_filter_products');
        fd.append('nonce',  CATFILTER.nonce);
        fd.append('term_id', termId);

        const res  = await fetch(CATFILTER.ajax_url, { method: 'POST', body: fd });
        const data = await res.json();
        if (data?.success) {
            this.list.innerHTML = data.data.html || '<div class="catalog__empty">Товары не найдены</div>';
        } else {
            this.list.innerHTML = '<div class="catalog__empty">Ошибка загрузки</div>';
        }
    }

    async initFromURL() {
        const slug = this.getTermFromURL();

        if (!slug) {
            this.clearActives();
            return;
        }

        const btn = this.qs(
            `.catalog__category__button[data-category-slug="${slug}"],
       .catalog__filters__individual[data-category-slug="${slug}"],
       .catalog__filters__category__item[data-category-slug="${slug}"]`
        );

        let termId = 0;
        if (btn) {
            const alsoParent =
                btn.classList.contains('catalog__filters__individual') ||
                btn.classList.contains('catalog__filters__category__item');
            this.applyActive(btn, alsoParent);
            termId = parseInt(btn.dataset.categoryId || '0', 10) || 0;
        } else if (this.slugToId.has(slug)) {
            termId = this.slugToId.get(slug);
        }

        if (termId > 0) {
            this.setLoading(true);
            try {
                await this.fetchProductsByTermId(termId);
            } finally {
                this.setLoading(false);
                if (typeof applyCartStateToUI === 'function') applyCartStateToUI();
            }
        }
    }

    bindEvents() {
        document.addEventListener('click', (e) => this.onClick(e));
        window.addEventListener('popstate', () => this.initFromURL());
    }

    async onClick(e) {
        const btn =
            e.target.closest('.catalog__category__button') ||
            e.target.closest('.catalog__filters__individual') ||
            e.target.closest('.catalog__filters__category__item');

        if (!btn) return;

        if (btn.tagName === 'A') e.preventDefault();

        const termId = parseInt(btn.dataset.categoryId || '0', 10) || 0;
        const slug   = btn.dataset.categorySlug || '';
        const url    = btn.dataset.url || btn.getAttribute('href') || null;

        const isCategoryBtn = btn.classList.contains('catalog__category__button');
        const isIndividual  = btn.classList.contains('catalog__filters__individual');
        const isChild       = btn.classList.contains('catalog__filters__category__item');

        if (btn.classList.contains('active')) {
            this.clearActives();
            btn.blur();
            this.setLoading(true);
            try {
                await this.fetchProductsByTermId(0);
                this.pushCategoryURL(null);
            } finally {
                this.setLoading(false);
                if (typeof applyCartStateToUI === 'function') applyCartStateToUI();
            }
            return;
        }

        const alsoParent = isIndividual || isChild;
        this.applyActive(btn, alsoParent);

        if (slug && termId) this.slugToId.set(slug, termId);

        this.setLoading(true);
        try {
            await this.fetchProductsByTermId(termId);
            if (slug) this.pushCategoryURL(slug, url);
            else this.pushCategoryURL(null);
        } finally {
            this.setLoading(false);
            if (typeof applyCartStateToUI === 'function') applyCartStateToUI();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CatalogFilter();
});
