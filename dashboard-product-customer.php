<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php");
    exit();
}

// Connect to database
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Fetch all products
$sql = "SELECT product_id, product_name, product_price, product_quantity, product_type, product_design, product_font FROM product";
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
            gap: 2rem;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .product-table th,
        .product-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .product-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .product-table tr:last-child td {
            border-bottom: none;
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

<!-- Main Content -->
<div class="content">
    <h1 class="page-title">Available Products</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="products-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <table class="product-table">
                    <tr>
                        <th>Product Name</th>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>RM <?= number_format($row['product_price'], 2) ?></td>
                    </tr>
                    <tr>
                        <th>Available Quantity</th>
                        <td><?= htmlspecialchars($row['product_quantity']) ?></td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td><?= htmlspecialchars($row['product_type']) ?></td>
                    </tr>
                    <tr>
                        <th>Design</th>
                        <td><?= htmlspecialchars($row['product_design']) ?></td>
                    </tr>
                    <tr>
                        <th>Font</th>
                        <td><?= htmlspecialchars($row['product_font']) ?></td>
                    </tr>
                </table>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No products available at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>