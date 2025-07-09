<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php");
    exit();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $cust_id = $_SESSION['cust_id'];

    // Connect to database
    $dbc = new mysqli("localhost", "root", "", "acrylic");

    if ($dbc->connect_error) {
        die("Connection failed: " . $dbc->connect_error);
    }

    // Get product price
    $sql = "SELECT product_price FROM product WHERE product_id = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Product not found.');</script>";
    } else {
        $product = $result->fetch_assoc();
        $price = $product['product_price'];
        $quantity = 1;
        $total = $price * $quantity;

        // Check if product is already in cart
        $check_sql = "SELECT cart_id, cart_quantity FROM cart WHERE cust_id = ? AND product_id = ?";
        $check_stmt = $dbc->prepare($check_sql);
        $check_stmt->bind_param("ii", $cust_id, $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Product exists, update quantity
            $row = $check_result->fetch_assoc();
            $new_quantity = $row['cart_quantity'] + 1;
            $new_total = $price * $new_quantity;

            $update_sql = "UPDATE cart SET cart_quantity = ?, cart_total = ? WHERE cart_id = ?";
            $update_stmt = $dbc->prepare($update_sql);
            $update_stmt->bind_param("idi", $new_quantity, $new_total, $row['cart_id']);

            if ($update_stmt->execute()) {
                echo "<script>alert('Cart updated successfully!');</script>";
            } else {
                echo "<script>alert('Error updating cart. Please try again.');</script>";
            }

            $update_stmt->close();
        } else {
            // Product not in cart, insert new
            $insert_sql = "INSERT INTO cart (cart_status, cart_quantity, cart_created, cart_total, cust_id, product_id)
                           VALUES (?, ?, NOW(), ?, ?, ?)";
            $insert_stmt = $dbc->prepare($insert_sql);
            $status = "Pending";

            $insert_stmt->bind_param("sidii", $status, $quantity, $total, $cust_id, $product_id);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Product added to cart successfully!');</script>";
            } else {
                echo "<script>alert('Error adding to cart. Please try again.');</script>";
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
    }
    
    $stmt->close();
    $dbc->close();
}

// Fetch all products again
$dbc = new mysqli("localhost", "root", "", "acrylic");
$sql = "SELECT product_id, product_name, product_price FROM product";
$result = $dbc->query($sql);
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="dashboard-customer.css">
  <link rel="stylesheet" href="main-page.css">
  <title>Product Display</title>
  <style>
    /* Reset and base styling */
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9fafb;
      color: #1f2937;
    }
    h1 {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .container {
  margin-left: 200px; /* space for sidebar */
  max-width: calc(100% - 200px); /* optional but helps with responsiveness */
  margin-top: 2rem;
  margin-bottom: 3rem;
  padding: 0 1rem;
}

    /* Tag banner */
    .tag-banner {
      background-color: #ffd6e8;
      color: #db2777;
      font-weight: 700;
      font-size: 2rem;
      padding: 0.65rem 1rem;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 2rem;
      user-select: none;
    }
    .tag-banner strong {
      color: #1e293b;
      font-weight: 600;
      margin-right: 0.3rem;
    }

    /* Product grid layout */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
      gap: 1.6rem;
    }

    .product-card {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgb(0 0 0 / 0.07);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: box-shadow 0.3s ease, transform 0.3s ease;
      cursor: pointer;
    }
    .product-card:hover {
      box-shadow: 0 8px 18px rgb(219 39 119 / 0.3);
      transform: translateY(-4px);
    }

    /* Image container */
    .product-image {
      width: 100%;
      aspect-ratio: 1 / 1;
      overflow: hidden;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.4s ease;
    }
    .product-card:hover .product-image img {
      transform: scale(1.05);
    }

    /* Product details */
    .product-details {
      padding: 1rem;
      flex: 1 0 auto;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .product-name {
      font-weight: 700;
      font-size: 1rem;
      color: #374151;
      margin-bottom: 0.5rem;
      text-align: center;
      min-height: 52px; /* ensuring consistent height for 2 lines */
    }

    .product-price {
      font-weight: 700;
      color: #db2777;
      text-align: center;
      font-size: 1.1rem;
      margin-bottom: 0.25rem;
    }

    .product-bulk {
      font-weight: 500;
      font-size: 0.8rem;
      color: #9ca3af;
      text-align: center;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
      .container {
        padding: 0 0.75rem;
      }
      h1 {
        font-size: 1.6rem;
      }
      .tag-banner {
        font-size: 1rem;
        padding: 0.5rem;
      }
      .product-name {
        font-size: 0.95rem;
        min-height: auto;
      }
    }
  </style>
</head>
<body>
    <div class="sidebar">
    <ul>
        <li>
            <a href="#" class="logo">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
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
            <a href="dashboard-product-customer.php" class="active">
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
  <main class="container" role="main" aria-labelledby="products-title">
    <h1 id="products-title">Products</h1>
    <div class="tag-banner" aria-label="Product category tag">
      <strong>Acrylic</strong><span>Tag</span>
    </div>

    <section class="products-grid" aria-label="Product listing">

      <article class="product-card" tabindex="0">
        <div class="product-image">
          <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/8dbe1f7a-3df6-4d9f-89ae-38b40009c6e4.png" alt="Rose Gold Mirror Black rectangular acrylic badges with elegant script black letterings piled up on a white background"/>
          <div class="icons">
                            <a href="#" 
                            class="cart-btn" 
                            data-name="rose gold mirror black design" 
                            data-price="RM60.00" 
                            data-image="prod2.jpg">
                            Add to cart
                            </a>
                        </div>
        </div>
        <div class="product-details">
          <h2 class="product-name">Rose Gold Mirror Black Design</h2>
          <div class="product-price">RM60.00</div>
          <div class="product-bulk">50pcs/1pack</div>
        </div>
      </article>

      <article class="product-card" tabindex="0">
        <div class="product-image">
          <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/67237696-1bc2-4553-a9bb-19c6d1cc7819.png" alt="Clear acrylic rectangular badges with rose gold mirror backing and black custom letterings laid on a pale surface"/>
        </div>
        <div class="product-details">
          <h2 class="product-name">Rose Gold Mirror Black Design</h2>
          <div class="product-price">RM60.00</div>
          <div class="product-bulk">50pcs/1pack</div>
        </div>
      </article>

      <article class="product-card" tabindex="0">
        <div class="product-image">
          <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/0383f008-c5f3-4f4a-a0bb-9d1afddb970d.png" alt="Gold-colored line engraved rectangular acrylic badges with delicate script text on a white background"/>
        </div>
        <div class="product-details">
          <h2 class="product-name">Gold Line Engraved Design</h2>
          <div class="product-price">RM60.00</div>
          <div class="product-bulk">50pcs/1pack</div>
        </div>
      </article>

      <article class="product-card" tabindex="0">
        <div class="product-image">
          <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/37c36012-46b8-4ee8-978c-9b2a1d7b3fba.png" alt="Round acrylic badges with clear lettering and black background stacked in a pile on light surface"/>
        </div>
        <div class="product-details">
          <h2 class="product-name">Clear Lettering Black Background</h2>
          <div class="product-price">RM45.00</div>
          <div class="product-bulk">50pcs/1pack</div>
        </div>
      </article>

    </section>
  </main>
</body>
</html>

