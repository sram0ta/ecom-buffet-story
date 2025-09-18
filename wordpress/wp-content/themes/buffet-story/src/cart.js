function applyCartStateToUI() {
    if (!window.MYCART || !MYCART.cart) return;

    document.querySelectorAll('.product-item').forEach(item => {
        const productId = parseInt(item.dataset.productId, 10);
        if (!productId) return;

        const qty = parseInt(MYCART.cart[productId] || 0, 10);
        const addBtn   = item.querySelector('.product-item__content__buttons__add');
        const numberEl = item.querySelector('.product-item__content__buttons__count-number');

        if (qty > 0) {
            if (addBtn) addBtn.classList.add('active');
            if (numberEl) numberEl.textContent = qty;
        } else {
            if (addBtn) addBtn.classList.remove('active');
            if (numberEl) numberEl.textContent = '1';
        }
    });
}

document.addEventListener('DOMContentLoaded', applyCartStateToUI);

document.addEventListener('click', async (e) => {
    // добавить в корзину
    if (e.target.closest('.product-item__content__buttons__add')) {
        const addBtn = e.target.closest('.product-item__content__buttons__add');
        const item   = e.target.closest('.product-item');
        if (!item) return;
        const productId = item.dataset.productId;
        const qtyEl     = item.querySelector('.product-item__content__buttons__count-number');
        const qty       = qtyEl ? parseInt(qtyEl.textContent.trim() || '1', 10) : 1;

        const fd = new FormData();
        fd.append('action', 'my_cart_add');
        fd.append('nonce',  MYCART.nonce);
        fd.append('product_id', productId);
        fd.append('qty', qty);

        try {
            const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                if (qtyEl) qtyEl.textContent = data.data.qty;
                if (addBtn) addBtn.classList.add('active');
            }
        } catch(err) {
            console.error('Ошибка добавления:', err);
        }
    }

    // плюс/минус
    const btn = e.target.closest('.product-item__content__buttons__count-value');
    if (btn) {
        const item      = btn.closest('.product-item');
        if (!item) return;
        const productId = item.dataset.productId;
        const numberEl  = item.querySelector('.product-item__content__buttons__count-number');
        const addBtn    = item.querySelector('.product-item__content__buttons__add');
        if (!numberEl) return;

        const isPlus = btn.textContent.trim() === '+';
        const delta  = isPlus ? 1 : -1;

        const fd = new FormData();
        fd.append('action', 'my_cart_change');
        fd.append('nonce',  MYCART.nonce);
        fd.append('product_id', productId);
        fd.append('delta', delta);

        try {
            const res  = await fetch(MYCART.ajax_url, { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                const newQty = parseInt(data.data.qty, 10) || 0;
                numberEl.textContent = newQty > 0 ? newQty : 1;

                if (newQty <= 0) {
                    if (addBtn) addBtn.classList.remove('active');
                }
            }
        } catch(err) {
            console.error('Ошибка изменения количества:', err);
        }
    }
});
