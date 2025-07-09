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

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$cust_id = $_SESSION['cust_id'];
$grand_total = 0;
$cart_items = [];

// Handle Update Quantity
if (isset($_POST['update_quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    // Get product price
    $sql = "
        SELECT p.product_price 
        FROM cart c
        JOIN product p ON c.product_id = p.product_id
        WHERE c.cart_id = ?
    ";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Cart item not found.');</script>";
    } else {
        $price = $result->fetch_assoc()['product_price'];
        $total = $price * $quantity;

        // Update cart
        $update_sql = "UPDATE cart SET cart_quantity = ?, cart_total = ? WHERE cart_id = ?";
        $update_stmt = $dbc->prepare($update_sql);
        $update_stmt->bind_param("idi", $quantity, $total, $cart_id);

        if (!$update_stmt->execute()) {
            echo "<script>alert('Failed to update quantity.');</script>";
        }

        $update_stmt->close();
    }

    $stmt->close();
}

// Handle Remove Item
if (isset($_POST['remove_item'])) {
    $cart_id = intval($_POST['cart_id']);

    $delete_sql = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $dbc->prepare($delete_sql);
    $stmt->bind_param("i", $cart_id);

    if (!$stmt->execute()) {
        echo "<script>alert('Failed to remove item.');</script>";
    }

    $stmt->close();

    // Redirect to refresh page
    header("Location: dashboard-cart-customer.php");
    exit();
}

// Fetch updated cart items - ONLY PENDING ITEMS
$sql = "
    SELECT 
        c.cart_id,
        c.product_id,
        c.cart_quantity,
        c.cart_total,
        p.product_name,
        p.product_price
    FROM cart c
    JOIN product p ON c.product_id = p.product_id
    WHERE c.cust_id = ? AND c.cart_status = 'pending'
";

$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $grand_total += $row['cart_total'];
}
$stmt->close();
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shopping Cart</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
          integrity="sha512-pV1pHpZ5gF..." crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="dashboard-customer.css">


    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 220px;
            padding: 2rem;
        }

        h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #f3f4f6;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #6b7280;
        }

        th, td {
            padding: 1rem;
            text-align: center;
            vertical-align: middle;
        }

        tr {
            background-color: #ffffff;
            border-radius: 10px;
        }

        /* Product Name */
        .product-name {
            font-weight: bold;
            color: #1e293b;
        }

        /* Price */
        .product-price {
            color: green;
            font-weight: 600;
        }

        /* Quantity input */
        input[type="number"] {
            width: 60px;
            padding: 6px 10px;
            font-size: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Action buttons */
        .action-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .btn-update {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background-color 0.3s ease;
        }

        .btn-update:hover {
            background-color: #2563eb;
        }

        .btn-remove {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #dc2626;
        }

        .total-box {
            margin-top: 2rem;
            font-size: 1.2rem;
            text-align: right;
            font-weight: bold;
            color: #1e293b;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            margin-top: 1.5rem;
            padding: 12px 20px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #059669;
        }

        p {
            text-align: center;
            color: #6b7280;
            font-size: 1.1rem;
            margin-top: 3rem;
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
    <h1>My Shopping Cart</h1>

    <?php if (count($cart_items) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><span class="product-name"><?= htmlspecialchars($item['product_name']) ?></span></td>
                        <td><span class="product-price">RM <?= number_format($item['product_price'], 2) ?></span></td>
                        <td>
                            <form method="post" style="display: flex; align-items: center; gap: 10px;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['cart_quantity'] ?>" min="1" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>RM <?= number_format($item['cart_total'], 2) ?></td>
                        <td>
                            <form method="post" style="display: inline-block;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <button type="submit" name="remove_item" class="btn-remove">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-box">
            <strong>Grand Total: RM <?= number_format($grand_total, 2) ?></strong>
        </div>

        <form action="payment-customer.php" method="post">
            <button type="submit" class="checkout-btn">Proceed to Payment</button>
        </form>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>