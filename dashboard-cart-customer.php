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
}

// Fetch updated cart items (without filtering by status for now)
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
    <link rel="stylesheet" href="dashboard-customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Cart</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
        .total-box { margin-top: 20px; font-size: 1.2rem; }
        .checkout-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
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
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td>RM <?= number_format($item['product_price'], 2) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['cart_quantity'] ?>" min="1" style="width:60px;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>RM <?= number_format($item['cart_total'], 2) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <button type="submit" name="remove_item">Remove</button>
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