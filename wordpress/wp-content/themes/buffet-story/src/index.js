window.addEventListener('DOMContentLoaded', function() {
    openMenu();
    advantagesGallery();
    platformsGallery();
    headerScroll();
    galleryBanner();
    galleryReviews();
    reviewsToggle();
    initAirPickers();
    initAirPickers2();
    eventSelect();
    productGallery();
    productTab();
    stagesGallery();
    countAnimation();
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

const stagesGallery = () => {
    if (window.innerWidth > 1024) return;

    if (document.querySelector('#stages-gallery')) {
        const swiper = new Swiper("#stages-gallery", {
            slidesPerView: 1.3,
            spaceBetween: DomUtils.remToPx(.4),
            speed: 500,
            loop: true,
            navigation: {
                prevEl: '.stages ._prev',
                nextEl: '.stages ._next',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2.9,
                    spaceBetween: DomUtils.remToPx(1),
                },
            }
        });
    }
}

const galleryReviews = () => {
    if (document.querySelector('#reviews-gallery')) {
        const swiper = new Swiper("#reviews-gallery", {
            slidesPerView: 1.2,
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
    document.querySelectorAll('input.date1').forEach((el) => {
        if (el.dataset.apInited === '1') return;

        new AirDatepicker(el, {
            autoClose: true,
            dateFormat: 'dd.MM.yyyy',
            locale: AirDatepicker.locales?.ru || {},
            minDate: new Date(),
        });

        el.dataset.apInited = '1';
    });
}

function initAirPickers2() {
    document.querySelectorAll('input.date2').forEach((el) => {
        if (el.dataset.apInited === '1') return;

        new AirDatepicker(el, {
            autoClose: true,
            dateFormat: 'dd.MM.yyyy',
            locale: AirDatepicker.locales?.ru || {},
            minDate: new Date(),
        });

        el.dataset.apInited = '1';
    });
}

function eventSelect() {
    document.querySelectorAll('.select-event').forEach((selectEl) => {
        if (selectEl.dataset.choicesInited === '1') return;

        new Choices(selectEl, {
            searchEnabled: false,
            itemSelectText: '',
        });

        selectEl.dataset.choicesInited = '1';
    });
}

const productGallery = () => {
    if (document.querySelector('.product__information__gallery__thumbs')) {

        const thumbs = new Swiper('.product__information__gallery__thumbs .swiper', {
            direction: 'horizontal',
            slidesPerView: 4,
            spaceBetween: DomUtils.remToPx(.4),
            watchSlidesProgress: true,
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    direction: 'vertical',
                    spaceBetween: DomUtils.remToPx(1.2),
                },
            }
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

// Инициализация для всех форм CF7 на странице
(function formCheckInit() {
    // --- helpers --------------------------------------------------------------
    const findGroupCheckboxes = (toggleBtn, form) => {
        const scope = toggleBtn.closest('.form__check_wrap') || toggleBtn;
        let boxes = scope.querySelectorAll('.form__check_hide input[type="checkbox"]');
        if (!boxes.length) {
            // фоллбэк: вся форма (если .form__check_hide размещён отдельно)
            boxes = form.querySelectorAll('.form__check_hide input[type="checkbox"]');
        }
        return Array.from(boxes);
    };

    const allChecked = (boxes) => boxes.length > 0 && boxes.every(cb => cb.checked);

    const syncToggleUi = (toggleBtn, boxes) => {
        const ok = allChecked(boxes);
        toggleBtn.classList.toggle('active', ok);
        if (ok) toggleBtn.classList.remove('error');
    };

    const attachToForm = (form) => {
        if (form.dataset.formCheckBound === '1') return;
        form.dataset.formCheckBound = '1';

        const toggles = Array.from(form.querySelectorAll('.form__check'));
        const submit  = form.querySelector('.form__button');

        // первичная синхронизация и обработчики
        toggles.forEach(toggleBtn => {
            const boxes = findGroupCheckboxes(toggleBtn, form);
            if (!boxes.length) return;

            // синк UI на старте
            syncToggleUi(toggleBtn, boxes);

            // клик по визуальной «галке»
            toggleBtn.addEventListener('click', () => {
                const nextState = !allChecked(boxes);
                boxes.forEach(cb => { cb.checked = nextState; });
                syncToggleUi(toggleBtn, boxes);
            });

            // пользователь кликает по самим чекбоксам — поддержим
            boxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    syncToggleUi(toggleBtn, boxes);
                });
            });
        });

        // валидация перед отправкой
        if (submit) {
            submit.addEventListener('click', (e) => {
                let valid = true;

                toggles.forEach(toggleBtn => {
                    const boxes = findGroupCheckboxes(toggleBtn, form);
                    if (!boxes.length) return;

                    const ok = allChecked(boxes);
                    toggleBtn.classList.toggle('error', !ok);
                    if (!ok) valid = false;
                });

                if (!valid) {
                    e.preventDefault();
                    const firstError = form.querySelector('.form__check.error');
                    firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    };

    // --- init на уже отрендеренные формы --------------------------------------
    const mountAll = () => {
        document.querySelectorAll('.wpcf7 form').forEach(attachToForm);
    };

    // DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mountAll);
    } else {
        mountAll();
    }

    // --- поддержка динамического появления/перерисовки (CF7, ajax, SPA) -------
    const mo = new MutationObserver((mutations) => {
        for (const m of mutations) {
            if (m.type === 'childList' && (m.addedNodes?.length || m.removedNodes?.length)) {
                document.querySelectorAll('.wpcf7 form').forEach(attachToForm);
            }
        }
    });
    mo.observe(document.documentElement, { childList: true, subtree: true });

    document.addEventListener('wpcf7init', mountAll);
    document.addEventListener('wpcf7reset', mountAll);
})();


const countAnimation = () => {
    const counters = document.querySelectorAll(".about__number__count");
    let started = false;

    function animateCounter(el) {
        const target = parseInt(el.textContent, 10);
        let current = 0;
        const duration = 2000; // 2 секунды
        const step = Math.ceil(target / (duration / 16));

        el.textContent = "1";

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                el.textContent = target;
                clearInterval(timer);
            } else {
                el.textContent = current;
            }
        }, 16);
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !started) {
                counters.forEach(counter => animateCounter(counter));
                started = true;
            }
        });
    }, { threshold: 0.3 });

    const aboutBlock = document.querySelector(".about");
    if (aboutBlock) {
        observer.observe(aboutBlock);
    }

}

document.addEventListener('DOMContentLoaded', () => {
    const CF = window.CATFILTER || {};
    const baseURL = ensureSlash(CF.base_url || (location.origin + '/catalog/'));
    const list = document.querySelector('.catalog__list');
    if (!list) return;

    let currentTopCatTermId = 0;
    let activeProductTypeTermId = 0;

    primeOriginalHrefs('.catalog__category__button, .catalog__filters__individual');
    initFromURL(true);

    document.addEventListener('click', async (e) => {
        const link = e.target.closest('.catalog__category__button, .catalog__filters__individual');
        if (!link || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        e.preventDefault();

        const targetHref = link.getAttribute('href') || link.dataset.hrefOriginal || '';
        const targetURL = new URL(targetHref, location.origin);
        const isResetToCatalog = normalizePath(targetURL.pathname) === normalizePath(new URL(baseURL).pathname);

        const termId = isResetToCatalog ? 0 : termIdFromLink(link, targetURL.pathname);

        currentTopCatTermId = termId;
        activeProductTypeTermId = 0;

        document.querySelectorAll('.catalog__filters__category__item').forEach(b => {
            b.classList.remove('active', 'is-zero');
            b.setAttribute('aria-pressed', 'false');
            b.removeAttribute('aria-disabled');
            b.removeAttribute('hidden');
        });

        await loadAndRender(
            { catTermId: currentTopCatTermId, productTypeTermId: 0 },
            targetURL.toString(),
            true
        );
    });

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.catalog__filters__category__item');
        if (!btn || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        e.preventDefault();

        const termId = parseInt(btn.dataset.productCategory || '0', 10) || 0;

        // Тоггл: повторный клик по активной — снимаем фильтр
        const willActivate = activeProductTypeTermId !== termId;
        activeProductTypeTermId = willActivate ? termId : 0;

        // Подсветка/ARIA
        document.querySelectorAll('.catalog__filters__category__item').forEach(el => {
            const id = parseInt(el.dataset.productCategory || '0', 10) || 0;
            const isActive = activeProductTypeTermId > 0 && id === activeProductTypeTermId;
            el.classList.toggle('active', isActive);
            el.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        // Загружаем с учетом ОБОИХ фильтров (product_cat + product-type)
        await loadAndRender(
            { catTermId: currentTopCatTermId, productTypeTermId: activeProductTypeTermId },
            /*urlToShow*/ null,
            /*push*/ false
        );
    });

    // ===== Назад/вперёд — из URL восстанавливаем только product_cat; product-type сбрасываем =====
    window.addEventListener('popstate', () => initFromURL(/*replaceState=*/false));

    // ================= helpers =================

    function primeOriginalHrefs(selector) {
        document.querySelectorAll(selector).forEach(a => {
            if (!a.dataset.hrefOriginal) a.dataset.hrefOriginal = a.getAttribute('href') || '';
        });
    }

    function ensureSlash(u) {
        try {
            const url = new URL(u, location.origin);
            if (!url.pathname.endsWith('/')) url.pathname += '/';
            return url.toString();
        } catch { return u; }
    }

    function normalizePath(p) { return p.endsWith('/') ? p : (p + '/'); }
    function safePath(href) { try { return new URL(href, location.origin).pathname; } catch { return ''; } }

    function termIdFromLink(link, pathMaybe) {
        if (pathMaybe) {
            const targetPath = normalizePath(pathMaybe);
            const candidate = Array.from(document.querySelectorAll('.catalog__category__button'))
                .find(a => normalizePath(safePath(a.dataset.hrefOriginal || a.getAttribute('href') || '')) === targetPath);
            const byPathId = parseInt(candidate?.dataset?.categoryId || '0', 10) || 0;
            if (byPathId) return byPathId;
        }
        return parseInt(link?.dataset?.categoryId || '0', 10) || 0;
    }

    async function fetchProductsCombined(catTermId, productTypeTermId) {
        const fd = new FormData();
        fd.append('action', 'buffet_filter_products');
        fd.append('nonce', CF.nonce || '');

        if (catTermId > 0)         fd.append('cat_term_id', catTermId);
        if (productTypeTermId > 0) fd.append('product_type_term_id', productTypeTermId);

        const res = await fetch(CF.ajax_url, { method: 'POST', body: fd });
        const data = await res.json();
        if (!data?.success) throw new Error('AJAX error');
        return data.data;
    }

    async function loadAndRender({ catTermId, productTypeTermId }, urlToShow, push) {
        setLoading(true);
        try {
            const data = await fetchProductsCombined(catTermId, productTypeTermId);
            list.innerHTML = data.html || '<div class="catalog__empty">Товары не найдены</div>';

            if (push) {
                const target = new URL(urlToShow || baseURL, location.origin).toString();
                history.pushState({ catTermId, productTypeTermId }, '', target);
            }

            applyActiveStatesForTop(catTermId);

            // SEO по желанию
            if (data.seo) applySEO(data.seo);

            updateProductTypeCountsUI(
                data.product_type_counts || {},
                productTypeTermId || 0,
                typeof data.count === 'number' ? data.count : undefined
            );
        } catch (e) {
            console.error(e);
            list.innerHTML = '<div class="catalog__empty">Ошибка загрузки</div>';
        } finally {
            setLoading(false);
        }
    }

    function updateProductTypeCountsUI(countMap, activeProductTypeTermId, currentResultCount) {
        document.querySelectorAll('.catalog__filters__category__item').forEach(btn => {
            const id = parseInt(btn.dataset.productCategory || '0', 10) || 0;
            const counter = btn.querySelector('.catalog__filters__category__item__count');
            if (!counter) return;

            const isActive = activeProductTypeTermId > 0 && id === activeProductTypeTermId;
            const count = isActive
                ? (typeof currentResultCount === 'number' ? currentResultCount : (countMap?.[id] || 0))
                : (countMap && typeof countMap[id] !== 'undefined' ? countMap[id] : 0);

            counter.textContent = String(count);

            const zero = count === 0;
            const shouldHide = zero && !isActive;

            btn.classList.toggle('is-zero', shouldHide);
            btn.setAttribute('aria-disabled', shouldHide ? 'true' : 'false');

            if (shouldHide) btn.setAttribute('hidden', '');
            else btn.removeAttribute('hidden');
        });
    }

    function setLoading(on) {
        if (on) {
            const h = list.offsetHeight;
            list.style.minHeight = h + 'px';
            list.innerHTML = '<div class="catalog__loading">Загрузка…</div>';
        } else {
            list.style.minHeight = '';
        }
    }

    function applySEO(seo) {
        if (seo.title) document.title = seo.title;
        const sel = 'meta[name="description"]';
        let md = document.querySelector(sel);
        if (!md) {
            md = document.createElement('meta');
            md.setAttribute('name', 'description');
            document.head.appendChild(md);
        }
        if (typeof seo.description === 'string') md.setAttribute('content', seo.description);
        let canon = document.querySelector('link[rel="canonical"]');
        if (!canon) {
            canon = document.createElement('link');
            canon.setAttribute('rel', 'canonical');
            document.head.appendChild(canon);
        }
        if (seo.url) canon.setAttribute('href', seo.url);
    }

    function applyActiveStatesForTop(catTermId) {
        document.querySelectorAll('.catalog__category__button').forEach(link => {
            if (!link.dataset.hrefOriginal) link.dataset.hrefOriginal = link.getAttribute('href') || '';
            const id = parseInt(link.dataset.categoryId || '0', 10) || 0;
            const same = id === (catTermId || 0);
            link.classList.toggle('active', same);
            link.setAttribute('href', same ? baseURL : link.dataset.hrefOriginal);
        });
    }

    async function initFromURL(replaceState) {
        const path = normalizePath(location.pathname);
        const link = Array.from(document.querySelectorAll('.catalog__category__button'))
            .find(a => normalizePath(safePath(a.dataset.hrefOriginal || a.getAttribute('href') || '')) === path);

        currentTopCatTermId = termIdFromLink(link, path);

        activeProductTypeTermId = 0;
        document.querySelectorAll('.catalog__filters__category__item').forEach(b => {
            b.classList.remove('active', 'is-zero');
            b.setAttribute('aria-pressed', 'false');
            b.removeAttribute('aria-disabled');
            b.removeAttribute('hidden');
        });

        const urlToShow = link ? (link.dataset.hrefOriginal || link.getAttribute('href')) : baseURL;

        await loadAndRender(
            { catTermId: currentTopCatTermId, productTypeTermId: 0 },
            urlToShow,
            !replaceState
        );
    }

    function ensureSlash(u) {
        try {
            const url = new URL(u, location.origin);
            if (!url.pathname.endsWith('/')) url.pathname += '/';
            return url.toString();
        } catch { return u; }
    }
});

function initPhoneMasks(context = document) {
    const inputs = context.querySelectorAll('input.wpcf7-tel');
    if (!inputs.length) return;

    inputs.forEach(input => {
        if (input.dataset.maskInited === '1') return;

        input.addEventListener('input', onPhoneInput, false);
        input.addEventListener('focus', onPhoneInput, false);
        input.addEventListener('blur', onPhoneBlur, false);
        input.addEventListener('keydown', onPhoneKeyDown, false);

        input.dataset.maskInited = '1';
    });

    function onPhoneKeyDown(e) {
        const keyCode = e.keyCode;
        if (keyCode === 8 && input.selectionStart <= 3) {
            e.preventDefault();
        }
    }

    function onPhoneInput(e) {
        let matrix = '+7 (___) ___-__-__',
            i = 0,
            def = matrix.replace(/\D/g, ''),
            val = e.target.value.replace(/\D/g, '');
        if (def.length >= val.length) val = def;
        e.target.value = matrix.replace(/./g, function (a) {
            return /[_\d]/.test(a) && i < val.length
                ? val.charAt(i++)
                : i >= val.length
                    ? ''
                    : a;
        });
    }

    function onPhoneBlur(e) {
        if (e.target.value.replace(/\D/g, '').length <= 2) {
            e.target.value = '';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => initPhoneMasks());

document.addEventListener('wpcf7submit', () => initPhoneMasks());
document.addEventListener('wpcf7mailsent', () => initPhoneMasks());
document.addEventListener('wpcf7invalid', () => initPhoneMasks());
