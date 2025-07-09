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

// Fetch updated cart items
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Cart</title>
    <style>
        body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f9fafb;
  color: #1f2937;
}

.content {
  margin-left: 200px;
  padding: 2rem;
  max-width: calc(100% - 200px);
}

h3 {
  font-size: 2.2rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 2rem;
  text-align: left;
}

/* TABLE STYLING */
table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 12px;
}

thead {
  background-color: #f3f4f6;
  text-transform: uppercase;
  font-size: 0.85rem;
  color: #6b7280;
}

th, td {
  padding: 1rem;
  background-color: white;
  text-align: center;
  font-size: 0.95rem;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
  vertical-align: middle;
}

tr {
  border-radius: 10px;
  overflow: hidden;
}

/* ROUNDED CORNERS FOR FIRST/LAST CELL */
td:first-child {
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
}
td:last-child {
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
}

/* QUANTITY INPUT */
input[type="number"] {
  width: 60px;
  padding: 6px 10px;
  font-size: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  background-color: #f9fafb;
  text-align: center;
  transition: 0.2s ease-in-out;
}

input[type="number"]:focus {
  outline: none;
  border-color: #db2777;
  box-shadow: 0 0 0 2px rgba(219, 39, 119, 0.2);
}

input[type="number"]::-webkit-inner-spin-button {
  margin: 0;
}

/* REMOVE BUTTON */
button[name="remove_item"] {
  background-color: #f87171;
  border: none;
  color: white;
  font-size: 0.9rem;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: background-color 0.2s ease;
}

button[name="remove_item"]:hover {
  background-color: #dc2626;
}

button[name="remove_item"] i {
  font-size: 0.9rem;
}

/* GRAND TOTAL */
/* GRAND TOTAL & PAYMENT WRAPPER */
.total-payment-wrapper {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin-top: 2rem;
  gap: 1.5rem;
}

/* GRAND TOTAL TEXT */
.total-text {
  font-size: 1.25rem;
  font-weight: bold;
  color: #1e293b;
}

/* PAYMENT BUTTON */
.checkout-btn {
  padding: 14px 28px;
  background-color: #db2777;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1.05rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}
.checkout-btn:hover {
  background-color: #be185d;
  transform: translateY(-1px);
}


p {
  font-size: 1rem;
  color: #6b7280;
  text-align: center;
  margin-top: 3rem;
}

    </style>
</head>
<body>

<div class="sidebar">
            <ul>
                <li>
                    <a href="#" class="logo">
                        <span class="icon"><i class="fa-solid fa-users"></i></span>
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

<div class="content">
    <h3>Your Cart</h3>

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
                                <input type="number" name="quantity" value="<?= $item['cart_quantity'] ?>" min="1" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>RM <?= number_format($item['cart_total'], 2) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <button type="submit" name="remove_item"><i class="fas fa-trash"></i> Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            Grand Total: RM <?= number_format($grand_total, 2) ?>
        </div>

        <form action="payment-customer.php" method="post">
            <button type="submit" class="checkout-btn">Proceed To Payment</button>
        </form>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
