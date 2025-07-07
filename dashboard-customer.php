<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="dashboard-customer.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Customer Dashboard</title>
    </head>
    <body>
        <!--sidebar section start-->
        
        <div class="sidebar">
            <ul>
                <li>
                    <a href="#" class="logo">
                        <span class="icon"><i class="fa-solid fa-users"></i></span>
                        <span class="text">Customer</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-customer.php">
                        <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-profile-customer.php">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="text">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-product-customer.php">
                        <span class="icon"><i class="fa-solid fa-bag-shopping"></i></span>
                        <span class="text">Product</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-cart-customer.php">
                        <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                        <span class="text">Cart</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-feedback-customer.php">
                        <span class="icon"><i class="fa-solid fa-comments"></i></span>
                        <span class="text">Feedback</span>
                    </a>
                </li>
                <li>
                    <a href="login-customer.php" class="logout">
                        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
                        <span class="text">Log out</span>
                    </a>
                </li>
            </ul>  
        </div>
        <div class="content">
            <h1 class="page-title">Customer Dashboard</h1>
        </div>
    
    </body>
</html>
