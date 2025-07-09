<?php
session_start();

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

// Fetch cart items with product details
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
    WHERE c.cust_id = ? AND c.cart_status = 'Pending'
";

$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$grand_total = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $grand_total += $row['cart_total'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <link rel="stylesheet" href="dashboard-customer.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <li><a href="#" class="logo"><span class="icon"><i class="fa-solid fa-user"></i></span><span class="text">Customer</span></a></li>
        <li><a href="dashboard-customer.php"><i class="fa-solid fa-table-columns"></i> Dashboard</a></li>
        <li><a href="dashboard-profile-customer.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="dashboard-product-customer.php"><i class="fa-solid fa-bag-shopping"></i> Products</a></li>
        <li><a href="dashboard-cart-customer.php" class="active"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
        <li><a href="dashboard-feedback-customer.php"><i class="fa-solid fa-comments"></i> Feedback</a></li>
        <li><a href="login-customer.php" class="logout"><i class="fa-solid fa-circle-arrow-left"></i> Log out</a></li>
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
                            <form method="post" action="update-cart.php" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['cart_quantity'] ?>" min="1" style="width:60px;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>RM <?= number_format($item['cart_total'], 2) ?></td>
                        <td>
                            <form method="post" action="remove-from-cart.php" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <button type="submit" class="btn btn-remove">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-box">
            <strong>Grand Total: RM <?= number_format($grand_total, 2) ?></strong>
        </div>

        <form action="payment.php" method="post">
            <button type="submit" class="checkout-btn">Proceed to Payment</button>
        </form>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>