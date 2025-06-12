window.onload = function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cart-items');
    if (cart.length === 0) {
        container.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }
    container.innerHTML = cart.map(item => `
        <div class="product-card">
            <img src="./asset/prod1.jpg" alt="Dark Gold Mirror Black" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
            <h2>Dark Gold Mirror Black</h2>
            <div>60.00</div>
        </div>
    `).join('');
};