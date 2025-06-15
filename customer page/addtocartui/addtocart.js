window.onload = function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cart-items');
    const totalContainer = document.getElementById('cart-total');
    if (cart.length === 0) {
        container.innerHTML = '<p>Your cart is empty.</p>';
        totalContainer.textContent = '';
        document.getElementById('proceed-payment').style.display = 'none';
        return;
    }

    // Default options
    const shapes = ['Rectangle', 'Circle', 'Oval', 'Custom'];
    const fonts = ['Arial', 'Verdana', 'Times New Roman', 'Comic Sans MS'];

    // Render cart items with per-item total
    container.innerHTML = cart.map((item, idx) => {
        // Extract numeric price (e.g., RM60.00 -> 60)
        const priceNum = parseFloat(item.price.replace(/[^\d.]/g, '')) || 0;
        const qty = item.quantity || 1;
        const itemTotal = priceNum * qty;
        return `
        <div class="product-card" data-idx="${idx}">
            <img src="${item.image}" alt="${item.name}">
            <div class="product-info">
                <h2>${item.name}</h2>
                <div>Price: ${item.price}</div>
                <label>Quantity:
                    <input type="number" min="1" value="${qty}" class="quantity-input">
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
            const priceNum = parseFloat(item.price.replace(/[^\d.]/g, '')) || 0;
            const qty = item.quantity || 1;
            overall += priceNum * qty;
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

    // Update quantity, shape, font and totals
    container.querySelectorAll('.product-card').forEach((card, idx) => {
        const qtyInput = card.querySelector('.quantity-input');
        const shapeSelect = card.querySelector('.shape-select');
        const fontSelect = card.querySelector('.font-select');
        const itemTotalDiv = card.querySelector('.item-total');

        qtyInput.onchange = function() {
            cart[idx].quantity = parseInt(qtyInput.value) || 1;
            localStorage.setItem('cart', JSON.stringify(cart));
            // Update item total
            const priceNum = parseFloat(cart[idx].price.replace(/[^\d.]/g, '')) || 0;
            itemTotalDiv.textContent = `Total: RM${(priceNum * cart[idx].quantity).toFixed(2)}`;
            updateOverallTotal();
        };
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
    document.getElementById('proceed-payment').onclick = function() {
        if (cart.length > 0) {
            window.location.href = 'payment.html'; // Redirect to payment page
        } else {
            alert('Your cart is empty. Please add items to your cart before proceeding to payment.');
        }
    };
};