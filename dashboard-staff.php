<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();

// Check if the user is logged in and has staff privileges
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'staff') {
    header("Location: login-administrator.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="dashboard-staff.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Staff</title>
    </head>
    <body>
        <div class="content">
            <h1 class="page-title">Dashboard</h1>
        </div>

        <!--sidebar section start-->
        
        <div class="sidebar" >
            <ul>
                <li>
                    <a href="#" class="logo">
                        <span class="icon"><i class="fa-solid fa-users"></i></i></span>
                        <span class="text">Staff</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-staff.php">
                        <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-profile-staff.php" >
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="text">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-product-staff.php">
                        <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span class="text">Products</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-order-staff.php">
                        <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span class="text">Order</span>
                    </a>
                </li>
                <li>
                    <a href="login-administrator.php" class="logout">
                        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></i></span>
                        <span class="text">Log out</span>
                    </a>
                </li>
            </ul>  
        </div>
        <section class="dashboard" aria-label="Dashboard overview">

    <div class="stats" role="list" aria-label="Key statistics">
      <div class="stat-item" role="listitem" aria-label="Users count">
        <label for="usersCount">Users</label>
        <div id="usersCount" class="value" aria-live="polite" aria-atomic="true">000</div>
      </div>
      <div class="stat-item" role="listitem" aria-label="Total product sold">
        <label for="productsSold">Total Product Sold</label>
        <div id="productsSold" class="value" aria-live="polite" aria-atomic="true">000</div>
      </div>
      <div class="stat-item" role="listitem" aria-label="Total revenue">
        <label for="totalRevenue">Total Revenue</label>
        <div id="totalRevenue" class="value" aria-live="polite" aria-atomic="true">RM000</div>
      </div>
    </div>

    <div class="recent-orders" tabindex="0" aria-label="Recent Orders">
      Recent Orders
    </div>

    <div class="orders-table" role="table" aria-label="Recent orders details">
      <div class="order-column" role="columnheader">
        <div class="order-column-header">Customer Name</div>
        <div class="order-column-content" aria-readonly="true"></div>
      </div>
      <div class="order-column" role="columnheader">
        <div class="order-column-header">Product Name</div>
        <div class="order-column-content" aria-readonly="true"></div>
      </div>
      <div class="order-column" role="columnheader">
        <div class="order-column-header">Quantity</div>
        <div class="order-column-content" aria-readonly="true"></div>
      </div>
      <div class="order-column" role="columnheader">
        <div class="order-column-header">Price (RM)</div>
        <div class="order-column-content" aria-readonly="true"></div>
      </div>
      <div class="order-column" role="columnheader">
        <div class="order-column-header">Type</div>
        <div class="order-column-content" aria-readonly="true"></div>
      </div>
      <div class="order-column" role="columnheader">
        <div class="order-column-header">Status</div>
        <div class="order-column-content" aria-readonly="true"></div>
      </div>
    </div>

  </section>
    </body>
</html>