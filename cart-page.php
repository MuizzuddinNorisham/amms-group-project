<?php
session_start();
if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php"); // Redirect to login if not logged in
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "acrylic");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cust_id = $_SESSION['cust_id'];

// Fetch cart items
$sql = "SELECT c.cart_id, c.cart_quantity, c.cart_total, p.product_name, p.product_price 
        FROM cart c 
        JOIN products p ON c.product_id = p.product_id 
        WHERE c.cust_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acrylic Manufacturer Management System</title>
    <link rel="stylesheet" href="../mainpage.css">
    <link rel="stylesheet" href="addtocart.css">
</head>
<body>

<header>
    <a href="#" class="logo">Review Your <span>Items</span></a>
</header>

<main>
    <div id="cart-items">
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['cart_quantity']) ?></td>
                            <td>RM <?= htmlspecialchars($item['product_price']) ?></td>
                            <td>RM <?= htmlspecialchars($item['cart_total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div id="cart-total" style="text-align:right; font-size:1.2rem; font-weight:600; margin-top:1.5rem;">
        Total: RM <?= array_sum(array_column($cart_items, 'cart_total')) ?>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
        <a href="main-page.php" class="proceed-btn back-btn">&#8592; Back</a>
        <button id="proceed-payment" class="proceed-btn">Proceed to Payment</button>
    </div>
</main>

<script src="addtocart.js"></script>
</body>
</html>
