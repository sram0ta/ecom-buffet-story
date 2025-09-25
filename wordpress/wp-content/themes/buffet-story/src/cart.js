function getWrappersByProductId(productId) {
    return Array.from(
        document.querySelectorAll(`.product-item__content__buttons__inner[data-product-id="${productId}"]`)
    );
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
    const hideAdd      = qty > 1;
    const showCounter  = qty > 0;
    const displayedQty = showCounter ? String(qty) : '1';

    getWrappersByProductId(productId).forEach(wrap => {
        const addBtn    = wrap.querySelector('.product-item__content__buttons__add');
        const numberEl  = wrap.querySelector('.product-item__content__buttons__count-number');
        const counterEl = wrap.querySelector('.product-item__content__buttons__wrapper');

        if (numberEl) numberEl.textContent = displayedQty;

        if (addBtn) {
            if (hideAdd) {
                addBtn.classList.add('active');
                addBtn.style.display = 'none';
            } else {
                addBtn.classList.toggle('active', showCounter);
                forceShowButton(addBtn);
            }
        }

        if (counterEl) {
            counterEl.style.display = showCounter ? '' : 'none';
        }
    });
}

function applyCartStateToUI() {
    document
        .querySelectorAll('.product-item__content__buttons__inner[data-product-id]')
        .forEach(wrap => updateUIForProduct(wrap.dataset.productId, 0));

    if (window.MYCART && MYCART.cart) {
        Object.entries(MYCART.cart).forEach(([id, qty]) => {
            updateUIForProduct(id, parseInt(qty, 10) || 0);
        });
    }

    document.body.classList.add('cart-ready');
}

function updateCartCountUI(uniqueCount) {
    const cartEl = document.querySelector('.header__cart');
    const badge  = document.querySelector('.header__cart__count');
    if (!badge || !cartEl) return;

    cartEl.dataset.count = String(uniqueCount);
    if (uniqueCount > 0) {
        badge.textContent = String(uniqueCount);
        badge.style.display = '';
    } else {
        badge.style.display = 'none';
    }
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
        const qtyNow    = numberEl ? parseInt(numberEl.textContent.trim() || '1', 10) : 1;

        const v = bumpVer(productId);

        const fd = new FormData();
        fd.append('action', 'my_cart_add');
        fd.append('nonce',  MYCART.nonce);
        fd.append('product_id', productId);
        fd.append('qty', qtyNow);

        try {
            const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();
            if (data?.success && isLatest(productId, v)) {
                const serverQty = parseInt(data.data.qty, 10) || qtyNow;
                updateUIForProduct(productId, serverQty);
                if (window.MYCART && MYCART.cart) MYCART.cart[productId] = serverQty;
                updateCartCountUI(data.data.cart_unique_count);
                await refreshCartPopup();
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
        const delta     = isPlus ? 1 : -1;

        const v = bumpVer(productId);

        const fd = new FormData();
        fd.append('action', 'my_cart_change');
        fd.append('nonce',  MYCART.nonce);
        fd.append('product_id', productId);
        fd.append('delta', delta);

        try {
            const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();
            if (data?.success && isLatest(productId, v)) {
                const newQty = parseInt(data.data.qty, 10) || 0;
                updateUIForProduct(productId, newQty);
                if (window.MYCART && MYCART.cart) MYCART.cart[productId] = newQty;
                updateCartCountUI(data.data.cart_unique_count);
                await refreshCartPopup();
            }
        } catch (err) {
            console.error('Ошибка изменения количества:', err);
        }
    }
});

function initPopupCartHandlers() {
    const popupProducts = document.querySelector('.popup__content__products');
    if (!popupProducts) return;

    popupProducts.addEventListener('click', async (e) => {
        const row = e.target.closest('.popup__content__products__item[data-product-id]');
        if (!row) return;

        const productId = row.dataset.productId;

        if (e.target.closest('.popup__content__products__item__content__delete')) {
            const fd = new FormData();
            fd.append('action', 'my_cart_remove');
            fd.append('nonce',  MYCART.nonce);
            fd.append('product_id', productId);

            try {
                const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
                const data = await res.json();
                if (data?.success) {
                    const hr = row.nextElementSibling;
                    row.remove();
                    if (hr && hr.classList.contains('popup__content__products__hr')) hr.remove();

                    if (typeof updateUIForProduct === 'function') updateUIForProduct(productId, 0);
                    if (window.MYCART && MYCART.cart) MYCART.cart[productId] = 0;
                    updateCartCountUI(data.data.cart_unique_count);
                    ensurePopupEmptyState();
                    await refreshCartPopup();
                }
            } catch (err) {
                console.error('Ошибка удаления:', err);
            }
            return;
        }

        const cntBtn = e.target.closest('.product-item__content__buttons__count-value');
        if (cntBtn) {
            const isPlus = cntBtn.textContent.trim() === '+';
            const delta  = isPlus ? 1 : -1;

            const fd = new FormData();
            fd.append('action', 'my_cart_change');
            fd.append('nonce',  MYCART.nonce);
            fd.append('product_id', productId);
            fd.append('delta', delta);

            try {
                const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
                const data = await res.json();
                if (data?.success) {
                    const newQty = parseInt(data.data.qty, 10) || 0;

                    const numberEl = row.querySelector('.product-item__content__buttons__count-number');
                    if (numberEl) numberEl.textContent = newQty > 0 ? newQty : 1;

                    if (newQty <= 0) {
                        const hr = row.nextElementSibling;
                        row.remove();
                        if (hr && hr.classList.contains('popup__content__products__hr')) hr.remove();
                    }

                    if (typeof updateUIForProduct === 'function') updateUIForProduct(productId, newQty);
                    if (window.MYCART && MYCART.cart) MYCART.cart[productId] = newQty;
                    updateCartCountUI(data.data.cart_unique_count);
                    ensurePopupEmptyState();
                    await refreshCartPopup();
                }
            } catch (err) {
                console.error('Ошибка изменения количества:', err);
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

async function refreshCartPopup() {
    const container = document.querySelector('.popup__content__products');
    if (!container) return;

    const fd = new FormData();
    fd.append('action', 'my_cart_popup');
    fd.append('nonce',  MYCART.nonce);

    try {
        const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
        const data = await res.json();
        if (data?.success) {
            container.innerHTML = data.data.html;

            updateCartCountUI(data.data.cart_unique_count);
        }
    } catch (err) {
        console.error('Не удалось обновить попап корзины:', err);
    }
}
