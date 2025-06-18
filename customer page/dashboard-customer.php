<?php
// Start session to check if user is logged in
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="dashboard-customer.css">
  <title>Customer Dashboard</title>

  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- CSS File -->
  <link rel="stylesheet" href="main-page.css">

  <!-- Inline Styling for Dashboard Cards (Optional) -->
  <style>
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
    <div class="logo">Customer<span>Panel</span></div>
    <nav class="navbar">
      <a href="main-page.php">🏠 Home</a>
      <a href="#">👤 Profile</a>
    </nav>
  </header>

  <!-- Main Content -->
  <main>
    <!-- Choose Design Card -->
    <div class="card">
      <div class="card-icon">edit_square</div>
      <div class="card-title">Choose Design</div>
      <div class="card-description">Customize and select your preferred product design.</div>
      <div class="card-metric"><a href="productlist.html">Go to Designs</a></div>
    </div>

    <!-- Add Feedback Card -->
    <div class="card">
      <div class="card-icon">rate_review</div>
      <div class="card-title">Add Feedback</div>
      <div class="card-description">Share your thoughts and experiences about our products.</div>
      <div class="card-metric"><a href="feedback-customer.php">Submit Feedback</a></div>
    </div>
  </main>

</body>
</html>