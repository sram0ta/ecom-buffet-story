function getWrappersByProductId(productId) {
    return Array.from(
        document.querySelectorAll(`.product-item__content__buttons__inner[data-product-id="${productId}"]`)
    );
}

// --- Минимум из data-min-value (поддержка строк вида "от 10 шт.")
function parseMinFrom(raw) {
    if (!raw) return 1;
    const m = String(raw).match(/\d+/);
    return m ? Math.max(1, parseInt(m[0], 10)) : 1;
}
function getMinFromWrap(wrap) {
    return parseMinFrom(wrap?.dataset?.minValue || '1');
}
function getMinForProduct(productId) {
    const wrap = getWrappersByProductId(productId)[0];
    if (wrap) return getMinFromWrap(wrap);

    const row = document.querySelector(`.popup__content__products__item[data-product-id="${productId}"]`);
    if (!row) return 1;
    const inner = row.querySelector('.product-item__content__buttons__inner[data-min-value]');
    return inner ? getMinFromWrap(inner) : 1;
}

function setMinusDisabled(wrap, disabled) {
    const minusBtn = Array.from(wrap.querySelectorAll('.product-item__content__buttons__count-value'))
        .find(b => b.textContent.trim() === '-');
    if (!minusBtn) return;

    minusBtn.disabled = !!disabled;
    minusBtn.setAttribute('aria-disabled', disabled ? 'true' : 'false');
    minusBtn.classList.toggle('is-disabled', !!disabled);
    minusBtn.tabIndex = disabled ? -1 : 0;
    minusBtn.style.pointerEvents = disabled ? 'none' : '';
}

// --- Привести показанное кол-во к минимуму (для первичного добавления)
function ensureMinOnAdd(wrap, shownQty) {
    const min = getMinFromWrap(wrap);
    return Math.max(min, shownQty || 0);
}

// --- Посчитать безопасный delta для +/- так, чтобы не уйти ниже минимума
// Возвращает число (в т.ч. 0), или null — если менять нельзя.
function computeSafeDelta(wrap, currentQty, isPlus) {
    const min = getMinFromWrap(wrap);

    if (isPlus) {
        // плюс всегда можно (минимум нарушить нельзя, он "снизу")
        return +1;
    }

    // минус: уже на минимуме — ничего не делаем
    if (currentQty <= min) return null;

    const next = currentQty - 1;
    if (next < min) {
        return -(currentQty - min);
    }
    return -1;
}

function refreshMinusUIForProduct(productId, qty) {
    const wraps = getWrappersByProductId(productId);
    wraps.forEach(wrap => {
        const min = getMinFromWrap(wrap);
        setMinusDisabled(wrap, !(qty > min));
    });
}

const CART_REQ_VER = new Map();
function bumpVer(productId) {
    const v = (CART_REQ_VER.get(productId) || 0) + 1;
    CART_REQ_VER.set(productId, v);
    return v;
}
function isLatest(productId, v) {
    return CART_REQ_VER.get(productId) === v;
}

function forceShowButton(btn) {
    btn.style.removeProperty('display');
    const cs = getComputedStyle(btn);
    if (cs.display === 'none') btn.style.display = 'inline-block';
    btn.style.visibility = 'visible';
}

function updateUIForProduct(productId, qty) {
    const wraps = getWrappersByProductId(productId);
    const showCounter = qty > 0;
    const displayedQty = showCounter ? qty : 1;

    wraps.forEach(wrap => {
        const addBtn = wrap.querySelector('.product-item__content__buttons__add');
        const counter = wrap.querySelector('.product-item__content__buttons__wrapper');
        const number = wrap.querySelector('.product-item__content__buttons__count-number');

        if (number) number.textContent = displayedQty;

        if (addBtn) {
            if (showCounter) {
                addBtn.style.display = 'none';
                addBtn.classList.add('active');
            } else {
                addBtn.style.display = 'inline-block';
                addBtn.classList.remove('active');
            }
        }

        if (counter) counter.style.display = showCounter ? '' : 'none';
    });

    refreshMinusUIForProduct(productId, qty);
}

document.addEventListener('DOMContentLoaded', async () => {
    await syncCartStateFromServer();
    recalcAllPopupTotals();
});

async function syncCartStateFromServer() {
    if (!window.MYCART) window.MYCART = {};
    const fd = new FormData();
    fd.append('action', 'my_cart_get_state');
    fd.append('nonce', MYCART.nonce);

    try {
        const res = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
        const data = await res.json();
        if (data?.success) {
            MYCART.cart = data.data.cart || {};
            MYCART.cart_unique_count = data.data.cart_unique_count || 0;
            updateCartCountUI(MYCART.cart_unique_count);
        } else {
            console.warn('Не удалось получить состояние корзины — используется локальное.');
            if (!MYCART.cart) MYCART.cart = {};
        }
    } catch (err) {
        console.error('Ошибка синхронизации корзины:', err);
        if (!MYCART.cart) MYCART.cart = {};
    }
}

function applyCartStateToUI() {
    const allProducts = document.querySelectorAll('.product-item__content__buttons__inner[data-product-id]');
    allProducts.forEach(wrap => {
        const productId = wrap.dataset.productId;
        const qty = MYCART.cart?.[productId] ? parseInt(MYCART.cart[productId], 10) : 0;
        updateUIForProduct(productId, qty);
    });
    document.body.classList.add('cart-ready');
}

function updateCartCountUI(uniqueCount) {
    const cartEl = document.querySelector('.header__cart');
    const badge = document.querySelector('.header__cart__count');
    if (!cartEl || !badge) return;

    cartEl.dataset.count = String(uniqueCount);
    badge.textContent = uniqueCount > 0 ? uniqueCount : '';
    badge.style.display = uniqueCount > 0 ? '' : 'none';
}


document.addEventListener('DOMContentLoaded', () => {
    const initCount =
        (window.MYCART && typeof MYCART.cart_unique_count !== 'undefined')
            ? (parseInt(MYCART.cart_unique_count, 10) || 0)
            : 0;
    updateCartCountUI(initCount);
});



function ensurePopupEmptyState() {
    const container = document.querySelector('.popup__content__products');
    if (!container) return;
    const hasItems = !!container.querySelector('.popup__content__products__item');
    if (!hasItems) {
        container.innerHTML = `<div class="popup__empty">Ваша корзина пуста</div>`;
    }
}

document.addEventListener('DOMContentLoaded', applyCartStateToUI);

document.addEventListener('click', async (e) => {
    const addBtn = e.target.closest('.product-item__content__buttons__add');
    if (addBtn) {
        const wrap = addBtn.closest('.product-item__content__buttons__inner[data-product-id]');
        if (!wrap) return;

        const productId = wrap.dataset.productId;
        const numberEl  = wrap.querySelector('.product-item__content__buttons__count-number');
        const shown     = numberEl ? parseInt(numberEl.textContent.trim() || '1', 10) : 1;

        // <<< NEW: не меньше минимума
        const qtyToAdd = ensureMinOnAdd(wrap, shown);

        const v = bumpVer(productId);
        const fd = new FormData();
        fd.append('action', 'my_cart_add');
        fd.append('nonce',  MYCART.nonce);
        fd.append('product_id', productId);
        fd.append('qty', qtyToAdd);

        try {
            const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();
            if (data?.success && isLatest(productId, v)) {
                const newQty = parseInt(data.data.qty, 10) || 0;
                const serverQtyRaw = parseInt(data.data.qty, 10);
                const serverQty = Number.isFinite(serverQtyRaw) ? serverQtyRaw : qtyToAdd;
                updateUIForProduct(productId, serverQty);
                if (window.MYCART && MYCART.cart) MYCART.cart[productId] = serverQty;
                updateCartCountUI(data.data.cart_unique_count);
                await refreshCartPopup();
                recalcPopupProductTotal(productId, newQty);
            }
        } catch (err) {
            console.error('Ошибка добавления:', err);
        }
        return;
    }

    const cntBtn = e.target.closest('.product-item__content__buttons__count-value');
    if (cntBtn) {
        const wrap = cntBtn.closest('.product-item__content__buttons__inner[data-product-id]');
        if (!wrap) return;

        const productId = wrap.dataset.productId;
        const isPlus    = cntBtn.textContent.trim() === '+';

        const numberEl  = wrap.querySelector('.product-item__content__buttons__count-number');
        const curQty    = numberEl ? parseInt(numberEl.textContent.trim() || '1', 10) : 1;

        // <<< NEW: безопасный delta
        const safeDelta = computeSafeDelta(wrap, curQty, isPlus);
        if (safeDelta === null || safeDelta === 0) {
            // Покажем лёгкую вибрацию на числе — ниже минимума нельзя
            numberEl?.classList.add('shake');
            setTimeout(() => numberEl?.classList.remove('shake'), 300);
            return;
        }

        const v = bumpVer(productId);
        const fd = new FormData();
        fd.append('action', 'my_cart_change');
        fd.append('nonce',  MYCART.nonce);
        fd.append('product_id', productId);
        fd.append('delta', safeDelta);

        try {
            const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();
            if (data?.success && isLatest(productId, v)) {
                const newQty = parseInt(data.data.qty, 10) || 0;
                updateUIForProduct(productId, newQty);
                if (window.MYCART && MYCART.cart) MYCART.cart[productId] = newQty;
                updateCartCountUI(data.data.cart_unique_count);
                await refreshCartPopup();
                recalcPopupProductTotal(productId, newQty);
            }
        } catch (err) {
            console.error('Ошибка изменения количества:', err);
        }
    }
});

function getRowElements(elInsideRow) {
    const row = elInsideRow.closest('.popup__content__products__item[data-product-id]');
    if (!row) return {};
    const priceEl = row.querySelector('.popup__content__products__item__content__control__price');
    const qtyEl   = row.querySelector('.product-item__content__buttons__count-number');
    return { row, priceEl, qtyEl };
}

function parseLocaleNumberLike(text) {
    // Универсальная попытка превратить "12 345,67" / "12,345.67" в 12345.67
    if (!text) return NaN;
    const s = String(text).trim()
        .replace(/\s/g, '')
        .replace(/&nbsp;/g, '')
        .replace(/[^\d.,-]/g, '');
    const comma = s.lastIndexOf(',');
    const dot   = s.lastIndexOf('.');
    let normalized = s;

    if (comma > -1 && dot > -1) {
        if (comma > dot) {
            normalized = s.replace(/\./g, '').replace(',', '.');
        } else {
            normalized = s.replace(/,/g, '');
        }
    } else if (comma > -1) {
        normalized = s.replace(',', '.');
    } else {
        const parts = s.split('.');
        if (parts.length > 2) {
            const last = parts.pop();
            normalized = parts.join('') + '.' + last;
        }
    }
    const n = Number(normalized);
    return isNaN(n) ? NaN : n;
}

function formatMoney(amount, currency, decimals) {
    try {
        if (currency) {
            return new Intl.NumberFormat(undefined, {
                style: 'currency',
                currency,
                minimumFractionDigits: typeof decimals === 'number' ? decimals : undefined,
                maximumFractionDigits: typeof decimals === 'number' ? decimals : undefined,
            }).format(amount);
        }
    } catch (e) {}
    const d = (typeof decimals === 'number') ? decimals : 2;
    return String(amount.toFixed(d));
}

function ensureUnitPriceStored(priceEl, qtyNow) {
    if (!priceEl) return null;
    // уже есть
    if (priceEl.dataset.unitPrice) {
        const p = parseFloat(priceEl.dataset.unitPrice);
        if (!isNaN(p) && p >= 0) return p;
    }
    const displayed = priceEl.textContent || '';
    const total = parseLocaleNumberLike(displayed);
    const q = Math.max(1, parseInt(qtyNow, 10) || 1);
    const unit = (!isNaN(total) && q > 0) ? (total / q) : NaN;
    if (!isNaN(unit) && isFinite(unit)) {
        priceEl.dataset.unitPrice = String(unit);
        return unit;
    }
    return null;
}

function updatePopupRowSubtotal(elInsideRow, qtyNew) {
    const { priceEl, qtyEl } = getRowElements(elInsideRow);
    if (!priceEl) return;

    const currency = priceEl.dataset.currency || '';
    const decimals = parseInt(priceEl.dataset.decimals || '2', 10);
    const unit = ensureUnitPriceStored(priceEl, qtyEl?.textContent || qtyNew);
    if (unit == null) return;

    const subtotal = unit * (parseInt(qtyNew, 10) || 0);
    priceEl.textContent = formatMoney(subtotal, currency, decimals);
}

function initPopupCartHandlers() {
    const popupProducts = document.querySelector('.popup__content__products');
    if (!popupProducts) return;

    popupProducts.addEventListener('click', async (e) => {
        const row = e.target.closest('.popup__content__products__item[data-product-id]');
        if (!row) return;

        const productId = row.dataset.productId;

        // 🗑️ Удаление товара
        if (e.target.closest('.popup__content__products__item__content__delete')) {
            const fd = new FormData();
            fd.append('action', 'my_cart_remove');
            fd.append('nonce', MYCART.nonce);
            fd.append('product_id', productId);

            try {
                const res = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
                const data = await res.json();
                if (data?.success) {
                    row.remove();
                    const hr = row.nextElementSibling;
                    if (hr && hr.classList.contains('popup__content__products__hr')) hr.remove();

                    MYCART.cart[productId] = 0;
                    updateUIForProduct(productId, 0);
                    updateCartCountUI(data.data.cart_unique_count);
                    ensurePopupEmptyState();
                    await refreshCartPopup();
                }
            } catch (err) {
                console.error('Ошибка удаления:', err);
            }
        }
    });
}
document.addEventListener('DOMContentLoaded', initPopupCartHandlers);

const cartPopupToggle = () => {
    const cartBtn = document.querySelector('.header__cart');
    const popup   = document.querySelector('.popup');
    const exitBtn = document.querySelector('.popup__exit');
    const body    = document.body;

    if (!cartBtn || !popup) return;

    cartBtn.addEventListener('click', (e) => {
        e.preventDefault();
        popup.classList.add('active');
        body.classList.add('fixed');
    });

    if (exitBtn) {
        exitBtn.addEventListener('click', (e) => {
            e.preventDefault();
            popup.classList.remove('active');
            body.classList.remove('fixed');
        });
    }

    popup.addEventListener('click', (e) => {
        if (e.target.classList.contains('popup')) {
            popup.classList.remove('active');
            body.classList.remove('fixed');
        }
    });
};
document.addEventListener('DOMContentLoaded', cartPopupToggle);

/**
 * Получить цену за 1 штуку из data-unit-price
 */
function getUnitPriceForProduct(productId) {
    const row = document.querySelector(`.popup__content__products__item[data-product-id="${productId}"]`);
    if (!row) return null;

    const priceEl = row.querySelector('.popup__content__products__item__content__control__price');
    if (!priceEl) return null;

    const unit = parseFloat(priceEl.dataset.unitPrice || '0');
    return isNaN(unit) ? 0 : unit;
}

function getQtyForRow(row) {
    const qtyEl = row.querySelector('.product-item__content__buttons__count-number');
    return Math.max(1, parseInt(qtyEl?.textContent.trim() || '1', 10) || 1);
}

function formatMoneyRU(amount, currency, decimals) {
    const n = Number(amount);
    const d = Number.isFinite(decimals) ? decimals : 2;
    let formatted = new Intl.NumberFormat('ru-RU', {
        minimumFractionDigits: d,
        maximumFractionDigits: d,
    }).format(n);
    if (currency) formatted += ' ' + currency;
    return formatted;
}

function recalcPopupRowTotal(row) {
    if (!row) return;
    const priceEl = row.querySelector('.popup__content__products__item__content__control__price');
    if (!priceEl) return;

    const unit = parseFloat(priceEl.dataset.unitPrice || '0'); // число, без форматирования
    if (!Number.isFinite(unit) || unit <= 0) return;

    const qty = getQtyForRow(row);
    const currency = priceEl.dataset.currency || '₽';
    const decimals = parseInt(priceEl.dataset.decimals || '2', 10);

    const total = unit * qty;
    priceEl.textContent = formatMoneyRU(total, currency, decimals);
}

function recalcAllPopupTotals() {
    document
        .querySelectorAll('.popup__content__products__item[data-product-id]')
        .forEach(row => recalcPopupRowTotal(row));

    recalcPopupGrandTotal();
}

function recalcPopupGrandTotal() {
    const rows = document.querySelectorAll('.popup__content__products__item[data-product-id]');
    const out  = document.querySelector('.popup__content__all-price__coast');
    if (!out) return;

    let sum = 0;
    let currency = '₽';
    let decimals = 2;

    rows.forEach((row, i) => {
        const priceEl = row.querySelector('.popup__content__products__item__content__control__price');
        if (!priceEl) return;

        const unit = parseFloat(priceEl.dataset.unitPrice || '0');
        const qty  = getQtyForRow(row);
        if (i === 0) {
            currency = priceEl.dataset.currency || currency;
            decimals = parseInt(priceEl.dataset.decimals || '2', 10);
        }
        if (Number.isFinite(unit) && qty > 0) {
            sum += unit * qty;
        }
    });

    out.textContent = formatMoneyRU(sum, currency, decimals);
}

/**
 * Пересчитать и обновить общую сумму (кол-во × цена за 1 шт.)
 */
function recalcPopupProductTotal(productId, qtyMaybe) {
    const row = document.querySelector(`.popup__content__products__item[data-product-id="${productId}"]`);
    if (!row) return;
    if (typeof qtyMaybe !== 'number') {
        qtyMaybe = getQtyForRow(row);
    } else {
        const qtyEl = row.querySelector('.product-item__content__buttons__count-number');
        if (qtyEl) qtyEl.textContent = qtyMaybe;
    }
    recalcPopupRowTotal(row);
}



async function refreshCartPopup() {
    const container = document.querySelector('.popup__content__products');
    if (!container) return;

    const fd = new FormData();
    fd.append('action', 'my_cart_popup');
    fd.append('nonce', MYCART.nonce);

    try {
        const res = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
        const data = await res.json();
        if (data?.success) {
            container.innerHTML = data.data.html;
            recalcAllPopupTotals();
            updateCartCountUI(data.data.cart_unique_count);
            applyCartStateToUI();
        }
    } catch (err) {
        console.error('Ошибка обновления попапа корзины:', err);
    }
}

document.addEventListener('wpcf7mailsent', async (e) => {
    try {
        const formId = parseInt(e?.detail?.contactFormId || '0', 10);
        const targetIds = [1011];
        if (!targetIds.includes(formId)) return;

        const fd = new FormData();
        fd.append('action', 'my_cart_clear');
        fd.append('nonce',  MYCART.nonce);

        const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
        const data = await res.json();

        if (data?.success) {
            if (!window.MYCART) window.MYCART = {};
            MYCART.cart = {};
            MYCART.cart_unique_count = 0;

            updateCartCountUI(0);

            await refreshCartPopup();

            const out = document.querySelector('.popup__content__all-price__coast');
            if (out) out.textContent = '';
        }
    } catch (err) {
        console.error('Ошибка очистки корзины после отправки формы:', err);
    }
}, false);

// +700 руб. к общей сумме при выборе доставки
(function () {
    const DELIVERY_TEXT = 'Доставка 700 рублей по Ростову-на-Дону';
    const DELIVERY_COST = 700;

    function getSelectedDeliveryValue() {
        const sel = document.querySelector('select[name="select-713"]');
        if (!sel) return null;

        const val = (sel.value || '').trim();
        return val || null;
    }

    function isDeliverySelected() {
        const v = getSelectedDeliveryValue();
        if (!v) return false;
        return v === DELIVERY_TEXT;
    }

    function applyDeliveryToGrandTotal() {
        const out = document.querySelector('.popup__content__all-price__coast');
        if (!out) return;

        const base = parseLocaleNumberLike(out.textContent);
        if (!Number.isFinite(base)) return;

        const add = isDeliverySelected() ? DELIVERY_COST : 0;

        const currency = '₽';
        out.textContent = formatMoneyRU(base + add, currency, 0);
    }

    function hookDeliveryChange() {
        const sel = document.querySelector('select[name="select-713"]');
        if (!sel) return;

        sel.addEventListener('change', () => {
            recalcPopupGrandTotal();
            applyDeliveryToGrandTotal();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        hookDeliveryChange();

        recalcPopupGrandTotal();
        applyDeliveryToGrandTotal();
    });

    const _refreshCartPopup = window.refreshCartPopup;
    if (typeof _refreshCartPopup === 'function') {
        window.refreshCartPopup = async function () {
            const res = await _refreshCartPopup.apply(this, arguments);
            hookDeliveryChange();
            recalcPopupGrandTotal();
            applyDeliveryToGrandTotal();
            return res;
        };
    }
})();
