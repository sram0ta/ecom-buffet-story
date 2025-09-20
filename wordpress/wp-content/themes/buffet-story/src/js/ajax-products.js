(function () {
    const list = document.querySelector('.catalog__list');
    if (!list) return;

    // текущее выбранное term_id (0 = все)
    let currentTerm = 0;

    // если при загрузке уже есть активная кнопка — подхватим
    const initialActive = document.querySelector(
        '.catalog__category__button.active, .catalog__filters__individual.active, .catalog__filters__category__item.active'
    );
    if (initialActive) {
        currentTerm = parseInt(initialActive.dataset.category || '0', 10) || 0;
    }

    document.addEventListener('click', async (e) => {
        const btn =
            e.target.closest('.catalog__category__button') ||
            e.target.closest('.catalog__filters__individual') ||
            e.target.closest('.catalog__filters__category__item');

        if (!btn) return;

        const clickedTerm = parseInt(btn.dataset.category || '0', 10) || 0;

        // если кликнули по уже активной категории (или по другой кнопке с тем же term_id) — сбрасываем фильтр
        const isSameFilter = clickedTerm === currentTerm;
        const nextTerm = isSameFilter ? 0 : clickedTerm;

        // подсветка
        document
            .querySelectorAll('.catalog__category__button, .catalog__filters__individual, .catalog__filters__category__item')
            .forEach(el => el.classList.remove('active'));
        if (nextTerm !== 0) {
            // подсветим ВСЕ кнопки с тем же term_id (вдруг их несколько в разных блоках)
            document
                .querySelectorAll(
                    `.catalog__category__button[data-category="${nextTerm}"], 
           .catalog__filters__individual[data-category="${nextTerm}"], 
           .catalog__filters__category__item[data-category="${nextTerm}"]`
                )
                .forEach(el => el.classList.add('active'));
        }

        // лоадер
        const prevHeight = list.offsetHeight;
        list.style.minHeight = prevHeight + 'px';
        list.classList.add('is-loading');
        list.innerHTML = '<div class="catalog__loading">Загрузка…</div>';

        // запрос
        const fd = new FormData();
        fd.append('action', 'buffet_filter_products');
        fd.append('nonce', CATFILTER.nonce);
        fd.append('term_id', nextTerm);

        try {
            const res = await fetch(CATFILTER.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();

            if (data && data.success) {
                list.innerHTML = data.data.html || '<div class="catalog__empty">Товары не найдены</div>';
                currentTerm = nextTerm; // фиксируем новое состояние
            } else {
                list.innerHTML = '<div class="catalog__empty">Ошибка загрузки</div>';
            }
        } catch (err) {
            console.error(err);
            list.innerHTML = '<div class="catalog__empty">Ошибка сети</div>';
        } finally {
            list.classList.remove('is-loading');
            list.style.minHeight = '';
            // тут можно ре-инициализировать ваши хендлеры: initCartBindings(); clampDescriptions(); ...
        }
    });
})();
