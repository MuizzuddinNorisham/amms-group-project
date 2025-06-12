window.onload = function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cart-items');
    if (cart.length === 0) {
        container.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }
    container.innerHTML = cart.map(item => `
        <div class="product-card">
            <img src="${item.image}" alt="${item.name}" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
            <h2>${item.name}</h2>
            <div>${item.price}</div>
        </div>
    `).join('');
};