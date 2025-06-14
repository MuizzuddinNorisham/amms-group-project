window.onload = function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cart-items');
    if (cart.length === 0) {
        container.innerHTML = '<p>Your cart is empty.</p>';
        document.getElementById('proceed-payment').style.display = 'none';
        return;
    }

    // Default options
    const shapes = ['Rectangle', 'Circle', 'Oval', 'Custom'];
    const fonts = ['Arial', 'Verdana', 'Times New Roman', 'Comic Sans MS'];

    // Render cart items
    container.innerHTML = cart.map((item, idx) => `
        <div class="product-card" data-idx="${idx}">
            <button class="delete-btn" title="Remove item">&times;</button>
            <img src="${item.image}" alt="${item.name}" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
            <h2>${item.name}</h2>
            <div>${item.price}</div>
            <label>Quantity:
                <input type="number" min="1" value="${item.quantity || 1}" class="quantity-input">
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
        </div>
    `).join('');

    // Delete item
    container.querySelectorAll('.delete-btn').forEach((btn, idx) => {
        btn.onclick = function() {
            cart.splice(idx, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.reload();
        };
    });

    // Update quantity, shape, font
    container.querySelectorAll('.product-card').forEach((card, idx) => {
        const qtyInput = card.querySelector('.quantity-input');
        const shapeSelect = card.querySelector('.shape-select');
        const fontSelect = card.querySelector('.font-select');

        qtyInput.onchange = function() {
            cart[idx].quantity = parseInt(qtyInput.value) || 1;
            localStorage.setItem('cart', JSON.stringify(cart));
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