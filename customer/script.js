document.querySelectorAll('.cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const name = this.getAttribute('data-name');
        const price = this.getAttribute('data-price');
        const image = this.getAttribute('data-image');
        if (!name || !price || !image) return;

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push({ name, price, image });
        localStorage.setItem('cart', JSON.stringify(cart));
        window.location.href = 'addtocartui/addtocart.html';
    });
});