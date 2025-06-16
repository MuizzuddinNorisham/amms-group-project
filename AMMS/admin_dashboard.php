<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet" />
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

    header .navbar-left {
      font-size: 2rem;
      color: #666;
    }

    header .navbar-left a {
      display: inline-block;
      border-radius: 5rem;
      background: #333;
      color: #fff;
      padding: 0.9rem 3.5rem;
      cursor: pointer;
      font-size: 1.7rem;
      transition: background 0.2s linear;
      text-transform: capitalize;
      text-decoration: none;
    }

    header .navbar-left a:hover {
      background: var(--pink);
    }

    header .logo {
      font-size: 3rem;
      color: #333;
      font-weight: bolder;
    }

    header .logo span {
      color: var(--pink);
    }

    main {
      padding: 6rem 9% 2rem;
      display: grid;
      gap: 2rem;
      max-width: 1000px;
      margin-left: auto;
      margin-right: auto;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      justify-content: center;
      justify-items: center;
    }

    .card {
      background-color: #f9fafb;
      border-radius: 1rem;
      box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .1);
      padding: 2rem;
      text-align: center;
      transition: box-shadow .2s ease, transform .2s ease;
      cursor: pointer;
      max-width: 320px;
      width: 100%;
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
  <header>
    <nav class="navbar-left">
      <a href="#logout" class="btn logout-btn">Logout</a>
    </nav>
    <div class="logo">Admin <span>Dashboard</span></div>
  </header>

  <main>
    <div class="card" role="link" tabindex="0" aria-describedby="desc-orders metric-orders">
      <span class="card-icon" aria-hidden="true">assignment</span>
      <h2 class="card-title">Order Management</h2>
      <p class="card-description" id="desc-orders">View and manage customer orders efficiently.</p>
      <div id="metric-orders" class="card-metric" aria-label="Total pending orders count">24</div>
    </div>

    <div class="card" role="link" tabindex="0" aria-describedby="desc-users metric-users">
      <span class="card-icon" aria-hidden="true">people</span>
      <h2 class="card-title">User  Management</h2>
      <p class="card-description" id="desc-users">Manage user profiles, roles, and permissions.</p>
      <div id="metric-users" class="card-metric" aria-label="Active users count">512</div>
    </div>

    <div class="card" role="link" tabindex="0" aria-describedby="desc-products metric-products">
      <span class="card-icon" aria-hidden="true">inventory_2</span>
      <h2 class="card-title">Product Management</h2>
      <p class="card-description" id="desc-products">Add, update, and organize product listings.</p>
      <div id="metric-products" class="card-metric" aria-label="Total products count">87</div>
    </div>
  </main>
</body>
</html>
