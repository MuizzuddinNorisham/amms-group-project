<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charsets="UTF-8">
        <meta http-equive="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acrylic Manufacturer Management System</title>
        
        <!--font-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

        <!--css file link-->
        <link rel="stylesheet" href="../mainpage.css">
        <link rel="stylesheet" href="addtocart.css">
    </head>

    <body>

        <header>
            <a href="#" class="logo">Review Your <span>Items</span></a>
        </header>

        <main>
            <div id="cart-items"></div>
            <div id="cart-total" style="text-align:right; font-size:1.2rem; font-weight:600; margin-top:1.5rem;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
                <a href="main-page.php" class="proceed-btn back-btn">&#8592; Back</a>
                <button id="proceed-payment" class="proceed-btn">Proceed to Payment</button>
            </div>
        </main>

        
        <script src="addtocart.js"></script>
    </body>
</html>