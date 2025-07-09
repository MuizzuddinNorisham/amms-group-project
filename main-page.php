<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acrylic Manufacturer Management System</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> 
  <script src="https://kit.fontawesome.com/780dcb86c4.js"  crossorigin="anonymous"></script>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="main-page.css">
</head>
<body>

  <!-- Header Section -->
  <header>
    <input type="checkbox" name="" id="toggler">
    <label for="toggler" class="fas fa-bars"></label>
    <a href="#" class="logo">Acrylic Tag<span>.</span></a>
    <nav class="navbar">
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#products">Products</a>
      <a href="#review">Review</a>
      <a href="#contact">Contact</a>
    </nav>
    <div class="icons">
      <a href="#" class="fas fa-heart"></a>
      <a href="cart-page.php" class="fas fa-shopping-cart"></a>
      <div class="dropdown">
        <a href="#" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user" style="font-size: 2.5rem; color: #333;"></i>
        </a>
        <div class="content">
          <a href="login-customer.php" class="fas fa-user">
            <h6 style="font-family: sans-serif;">Customer</h6>
          </a>
          <a href="login-administrator.php" class="fas fa-user">
            <h6 style="font-family: sans-serif;">Admin/Staff</h6>
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Home Section -->
  <section class="home" id="home">
    <div class="content">
      <h3>Acrylic Tags</h3>
      <span>Stand out High Quality Acrylic Tags & Labels</span>
      <p>Perfect gift or branding your business, our acrylic tags are designed to impress. Fully customize your tag by choosing the shape, color, font, and design.</p>
      <a href="productlist.html" class="btn">Shop now</a>
    </div>
  </section>

  <!-- About Section -->
  <section class="about" id="about">
    <h1 class="heading">About <span>Us</span></h1>
    <div class="row">
      <div class="video-container">
        <video src="vid2.mp4" loop autoplay muted></video>
        <h3>Best acrylic tags seller</h3>
      </div>
      <div class="content">
        <h3>Why choose us?</h3>
        <p>At Bezora Smart Service, we believe every detail matters. We’re passionate about turning your ideas into beautifully crafted acrylic tags that leave a lasting impression. Whether it’s for personal gifts or branding, we bring creativity and quality together just for you.</p>
        <a href="aboutpage.html" class="btn">Learn more</a>
      </div>
    </div>
  </section>

  <!-- Icons Section -->
  <section class="icons-container">
    <div class="icons">
      <img src="truck.png" alt="">
      <div class="info">
        <h3>Free delivery</h3>
        <span>On all orders</span>
      </div>
    </div>
    <div class="icons">
      <img src="money.png" alt="">
      <div class="info">
        <h3>10 days returned</h3>
        <span>Moneyback guarantee</span>
      </div>
    </div>
    <div class="icons">
      <img src="gift.png" alt="">
      <div class="info">
        <h3>Offers & Gifts</h3>
        <span>On all orders</span>
      </div>
    </div>
    <div class="icons">
      <img src="bank.png" alt="">
      <div class="info">
        <h3>Secure payment</h3>
        <span>Protected by Maybank</span>
      </div>
    </div>
  </section>
        <!--products section start-->

        <section class="products" id="products">

            <h1 class="heading" > Hot selling <span>products</span></h1>
            <div class="box-container">
            
                <div class="box">
                    <div class="image">
                        <img src="prod1.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>rose gold mirror black design</h3>
                        <div class="price">RM60.00</div>
                    </div>
                </div>

                <div class="box">
                    <div class="image">
                        <img src="prod2.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>rose gold mirror black design</h3>
                        <div class="price">RM60.00</div>
                    </div>
                </div>

                <div class="box">
                    <div class="image">
                        <img src="prod3.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>gold line engraved design</h3>
                        <div class="price">RM60.00</div>
                    </div>
                </div>

                <div class="box">
                    <div class="image">
                        <img src="prod4.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>clear lettering black background</h3>
                        <div class="price">RM45.00</div>
                    </div>
                </div>

                <div class="box">
                    <div class="image">
                        <img src="prod5.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>clear color infill</h3>
                        <div class="price">RM60.00</div>
                    </div>
                </div>

                <div class="box">
                    <div class="image">
                        <img src="prod6.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>rose gold mirror clear design</h3>
                        <div class="price">RM60.00</div>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                <a href="productlist.html" class="btn">More</a>
            </div>     
        </section>

        <!--products section ends-->

        <!--review section start-->

        <!--review section ends-->

        <!-- Contact Section -->
  <section class="contact" id="contact">
    <h1 class="heading">Contact <span>Us</span></h1>
    <div class="row">
      <form action="">
        <input type="text" placeholder="Name" class="box">
        <input type="email" placeholder="Email" class="box">
        <input type="number" placeholder="Number" class="box">
        <textarea name="" class="box" placeholder="Message" cols="30" rows="10"></textarea>
        <input type="submit" value="Send Message" class="btn">
      </form>
    </div>
  </section>

  <!-- JavaScript for Dropdown Menu -->
  <script>
    document.querySelectorAll('.dropdown').forEach(dropdown => {
      dropdown.addEventListener('click', () => {
        dropdown.querySelector('.content').classList.toggle('show');
      });
    });

    // Add to Cart functionality
    document.querySelectorAll('.cart-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const name = btn.getAttribute('data-name');
        const price = btn.getAttribute('data-price');
        const image = btn.getAttribute('data-image');
        const item = {
          name,
          price,
          image,
          quantity: 50, // Default to 50
          shape: 'Rectangle',
          font: 'Arial'
        };
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push(item);
        localStorage.setItem('cart', JSON.stringify(cart));
        alert('Added to cart!');
      });
    });
  </script>
</body>
</html>