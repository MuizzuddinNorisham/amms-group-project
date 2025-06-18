<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrator Menu</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    :root {
        --pink: #e84393;   
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        outline: none;
        border: none;
        text-decoration: none;
        text-transform: capitalize;
        transition: .2s linear;
    }

    html {
        font-size: 62.5%;
        scroll-behavior: smooth;
        scroll-padding-top: 6rem;
        overflow-x: hidden;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #fff;
        padding: 2rem 9%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 1000;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
    }

    header .logo {
        font-size: 3rem;
        color: #333;
        font-weight: bolder;
    }

    header .logo span {
        color: var(--pink);
    }

    header .navbar {
        font-size: 2rem;
        padding: 0 1.5rem;
        color: #666;
    }

    header .navbar a {
        margin-left: 2rem;
        color: #666;
    }

    header .navbar a:hover {
        color: var(--pink);
    }

    main {
        padding: 10rem 9% 4rem;
        display: grid;
        gap: 2rem;
        grid-template-columns: 1fr;
    }

    @media (min-width: 768px) {
        main {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        main {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .card {
        background-color: #f9fafb;
        border-radius: 1rem;
        box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .1);
        padding: 2rem;
        text-align: center;
        transition: box-shadow .2s ease, transform .2s ease;
        cursor: pointer;
    }

    .card:hover {
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, .2);
        transform: translateY(-0.5rem);
    }

    .card-icon {
        font-family: 'Material Icons';
        font-size: 4rem;
        color: var(--pink);
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .card-description {
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .card-metric {
        font-size: 2rem;
        color: var(--pink);
        font-weight: bold;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="logo">Admin<span>Panel</span></div>
    <nav class="navbar">
      <a href="main-page.php">🏠 Home</a>
      <a href="#">👤 Profile</a>
    </nav>
  </header>

  <!-- Main content -->
  <main>
    <!-- Order Management -->
    <div class="card">
      <div class="card-icon">receipt_long</div>
      <div class="card-title">Order Management</div>
      <div class="card-description">Manage all customer orders and track statuses.</div>
      <div class="card-metric">124 Orders</div>
    </div>

    <!-- User Management -->
    <div class="card">
      <div class="card-icon">group</div>
      <div class="card-title">User Management</div>
      <div class="card-description">View, edit and manage user roles and accounts.</div>
      <div class="card-metric">58 Users</div>
    </div>

    <!-- Product Management -->
    <div class="card">
      <div class="card-icon">inventory_2</div>
      <div class="card-title">Product Management</div>
      <div class="card-description">Update products, descriptions and stock levels.</div>
      <div class="card-metric"><a href="product-management-admin.php">231 Products</a></div>
    </div>
  </main>

</body>
</html>