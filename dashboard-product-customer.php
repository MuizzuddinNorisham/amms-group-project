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

        // ✅ Always insert a new cart item — no check for existing entries
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

    $stmt->close();
    $dbc->close();

    // Optional: Prevent form resubmission on refresh
    header("Location: dashboard-product-customer.php");
    exit();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Customer Dashboard</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="dashboard-customer.css">

    <style>
        .products-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .product-info {
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .product-price {
            color: green;
            font-size: 0.95rem;
        }

        .add-to-cart-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <li>
            <a href="#" class="logo">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <span class="text">Customer</span>
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

<!-- Main Content -->
<div class="content">
    <h1 class="page-title">Available Products</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="products-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                    <div class="product-card">
                        <div class="product-info">
                            <span class="product-name"><?= htmlspecialchars($row['product_name']) ?></span>
                            <span class="product-price">RM <?= number_format($row['product_price'], 2) ?></span>
                        </div>
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </form>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No products available at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>