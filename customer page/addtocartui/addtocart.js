window.onload = function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cart-items');
    let totalContainer = document.getElementById('cart-total');
    if (!totalContainer) {
        totalContainer = document.createElement('div');
        totalContainer.id = 'cart-total';
        totalContainer.style.textAlign = 'right';
        totalContainer.style.fontSize = '1.2rem';
        totalContainer.style.fontWeight = '600';
        totalContainer.style.marginTop = '1.5rem';
        container.parentNode.insertBefore(totalContainer, container.nextSibling);
    }
    if (cart.length === 0) {
        container.innerHTML = '<p>Your cart is empty.</p>';
        totalContainer.textContent = '';
        const proceedBtn = document.getElementById('proceed-payment');
        if (proceedBtn) proceedBtn.style.display = 'none';
        return;
    }

    // Default options
    const shapes = ['Rectangle', 'Circle', 'Oval', 'Custom'];
    const fonts = ['Arial', 'Verdana', 'Times New Roman', 'Comic Sans MS'];
    const pricePer50 = 60.00;

    // Render cart items with per-item total
    container.innerHTML = cart.map((item, idx) => {
        const qty = typeof item.quantity === 'number' ? item.quantity : 0;
        const itemTotal = (qty / 50) * pricePer50;

        return `
        <div class="product-card" data-idx="${idx}">
            <img src="${item.image}" alt="${item.name}">
            <div class="product-info">
                <h2>${item.name}</h2>
                <div>Price: RM60.00 / 50 pcs</div>
                <label>Quantity:
                    <div class="quantity-group">
                        <button type="button" class="qty-btn minus" data-idx="${idx}">-</button>
                        <input type="number" class="quantity-input" value="${qty}" min="0" step="50" readonly>
                        <button type="button" class="qty-btn plus" data-idx="${idx}">+</button>
                    </div>
                </label>
                <label>Shape:
                    <select class="shape-select">
                        ${shapes.map(shape => `<option${item.shape === shape ? ' selected' : ''}>${shape}</option>`).join('')}
                    </select>
                </label>
                <label>Font:
                    <select class="font-select">
                        ${fonts.map(font => `<option${item.font === font ? ' selected' : ''}>${font}</option>`).join('')}
                    </select>
                </label>
                <div class="item-total">Total: RM${itemTotal.toFixed(2)}</div>
            </div>
            <button class="delete-btn" title="Remove item">&times;</button>
        </div>
        `;
    }).join('');

    // Calculate and display overall total
    function updateOverallTotal() {
        let overall = 0;
        cart.forEach(item => {
            const qty = typeof item.quantity === 'number' ? item.quantity : 0;
            overall += (qty / 50) * pricePer50;
        });
        totalContainer.textContent = `Overall Total: RM${overall.toFixed(2)}`;
    }
    updateOverallTotal();

    // Delete item
    container.querySelectorAll('.delete-btn').forEach((btn, idx) => {
        btn.onclick = function() {
            cart.splice(idx, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.reload();
        };
    });

    // Quantity plus/minus logic (attach after rendering)
    container.querySelectorAll('.product-card').forEach((card, idx) => {
        const qtyInput = card.querySelector('.quantity-input');
        const minusBtn = card.querySelector('.qty-btn.minus');
        const plusBtn = card.querySelector('.qty-btn.plus');
        const itemTotalDiv = card.querySelector('.item-total');

        minusBtn.onclick = function() {
            let qty = parseInt(qtyInput.value, 10);
            if (qty >= 50) {
                qty -= 50;
                qtyInput.value = qty;
                cart[idx].quantity = qty;
                localStorage.setItem('cart', JSON.stringify(cart));
                const itemTotal = (qty / 50) * pricePer50;
                itemTotalDiv.textContent = `Total: RM${itemTotal.toFixed(2)}`;
                updateOverallTotal();
            }
        };
        plusBtn.onclick = function() {
            let qty = parseInt(qtyInput.value, 10);
            qty += 50;
            qtyInput.value = qty;
            cart[idx].quantity = qty;
            localStorage.setItem('cart', JSON.stringify(cart));
            const itemTotal = (qty / 50) * pricePer50;
            itemTotalDiv.textContent = `Total: RM${itemTotal.toFixed(2)}`;
            updateOverallTotal();
        };
    });

    // Update shape and font
    container.querySelectorAll('.product-card').forEach((card, idx) => {
        const shapeSelect = card.querySelector('.shape-select');
        const fontSelect = card.querySelector('.font-select');
        shapeSelect.onchange = function() {
            cart[idx].shape = shapeSelect.value;
            localStorage.setItem('cart', JSON.stringify(cart));
        };
        fontSelect.onchange = function() {
            cart[idx].font = fontSelect.value;
            localStorage.setItem('cart', JSON.stringify(cart));
        };
    });

    // Proceed to payment
    const proceedBtn = document.getElementById('proceed-payment');
    if (proceedBtn) {
        proceedBtn.onclick = function() {
            if (cart.length > 0) {
                window.location.href = 'payment.html'; // Redirect to payment page
            } else {
                alert('Your cart is empty. Please add items to your cart before proceeding to payment.');
            }
        };
    }
};