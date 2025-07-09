<?php
session_start();

// Debug message
if (isset($_SESSION['cart_debug'])) {
    echo "<div style='color:red; margin:20px;'>".$_SESSION['cart_debug']."</div>";
    unset($_SESSION['cart_debug']);
}

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

<<<<<<< HEAD
        // ✅ Always insert a new cart item — no check for existing entries
        $insert_sql = "INSERT INTO cart (cart_status, cart_quantity, cart_created, cart_total, cust_id, product_id)
                       VALUES (?, ?, NOW(), ?, ?, ?)";
        $insert_stmt = $dbc->prepare($insert_sql);
        $status = "Pending";
        $insert_stmt->bind_param("sidii", $status, $quantity, $total, $cust_id, $product_id);
=======
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
>>>>>>> adf4cc2eba50dff20cc99d5bfb8bf00c888a4630

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
<<<<<<< HEAD

    // Redirect to prevent form resubmission
    header("Location: dashboard-product-customer.php");
    exit();
=======
>>>>>>> adf4cc2eba50dff20cc99d5bfb8bf00c888a4630
}

// Fetch all products again
$dbc = new mysqli("localhost", "root", "", "acrylic");
$sql = "SELECT product_id, product_name, product_price, product_image, product_quantity FROM product";
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
    <link rel="stylesheet" href="main-page.css">
    <style>
    .products-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.product-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.06);
    height: 100%;
    width: 100%;
    max-width: 300px;
}


.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.product-name { 
    font-size: 1.7rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.product-price {
    color: #10b981;
    font-size: 1.5rem;
    font-weight: 500;
}

.product-quantity {
    color: rgb(106, 106, 106);
    font-size: 1.3rem;
    font-weight: 500;
}


    .product-info {
    margin-bottom: 1rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

    .add-to-cart-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.6rem;
    cursor: pointer;
    border-radius: 8px;
    font-weight: 600;
    transition: background-color 0.3s ease;
    margin-top: auto; /* Push to bottom */
    width: 100%; /* Optional: make button full width */
}

    .add-to-cart-btn:hover {
        background-color: #0056b3;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        text-align: left;
        margin-bottom: 1rem;
        color: #1e293b;
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
    <div class="products-container">
    
    
</div>

    <?php if ($result->num_rows > 0): ?>
        <div class="products-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                        </div>
                        <div class="product-info">
                            <span class="product-name"><?= htmlspecialchars($row['product_name']) ?></span>
                            <span class="product-price">RM <?= number_format($row['product_price'], 2) ?></span>
                            <span class="product-quantity"><?= htmlspecialchars($row['product_quantity']) ?> / pack</span>
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