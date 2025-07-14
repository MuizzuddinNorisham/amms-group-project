<?php
session_start();

// Check if user is logged in and is a staff member
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'staff') {
    header("Location: login-administrator.php");
    exit();
}

// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Handle delete cart item
if (isset($_POST['delete_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    $stmt = $dbc->prepare("DELETE FROM cart WHERE cart_id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success_message'] = "Cart record deleted successfully!";
    header("Location: dashboard-order-staff.php");
    exit();
}

// Handle delete payment item
if (isset($_POST['delete_payment'])) {
    $payment_id = intval($_POST['payment_id']);
    $stmt = $dbc->prepare("DELETE FROM payment WHERE payment_id = ?");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success_message'] = "Payment record deleted successfully!";
    header("Location: dashboard-order-staff.php");
    exit();
}

// Fetch cart data
$cart_query = "SELECT * FROM cart";
$cart_result = $dbc->query($cart_query);

// Fetch payment data
$payment_query = "SELECT * FROM payment";
$payment_result = $dbc->query($payment_query);

$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Orders</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
        integrity="sha512-pV1pHpZ5gF..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="dashboard-staff.css">
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

        h2 {
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            border-left: 4px solid #0ea5e9;
            padding-left: 10px;
        }

        .table-wrapper {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background-color: #f3f4f6;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        tr:hover {
            background-color: #fefffe;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        .empty-message {
            text-align: center;
            color: #6b7280;
            margin: 2rem 0;
            font-size: 1rem;
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
                <span class="text">Staff</span>
            </a>
        </li>
        <li>
            <a href="dashboard-staff.php">
                <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="dashboard-profile-staff.php">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="dashboard-product-staff.php">
                <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                <span class="text">Products</span>
            </a>
        </li>
        <li>
            <a href="dashboard-order-staff.php" class="active">
                <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="text">Order</span>
            </a>
        </li>
        <li>
            <a href="login-administrator.php" class="logout" onclick="confirmLogout(event)">
                <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
                <span class="text">Log out</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h2>Cart Table</h2>
    <div class="table-wrapper">
        <?php if ($cart_result && $cart_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Cart ID</th>
                        <th>Status</th>
                        <th>Qty</th>
                        <th>Date</th>
                        <th>Total (RM)</th>
                        <th>Cust ID</th>
                        <th>Product ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $cart_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['cart_id']) ?></td>
                            <td><?= htmlspecialchars($row['cart_status']) ?></td>
                            <td><?= htmlspecialchars($row['cart_quantity']) ?></td>
                            <td><?= date("Y-m-d", strtotime($row['cart_created'])) ?></td>
                            <td><?= number_format($row['cart_total'], 2) ?></td>
                            <td><?= htmlspecialchars($row['cust_id']) ?></td>
                            <td><?= htmlspecialchars($row['product_id']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                                    <button type="submit" name="delete_cart" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-message">No records found in the cart table.</p>
        <?php endif; ?>
    </div>

    <h2>Payment Table</h2>
<div class="table-wrapper">
    <?php if ($payment_result && $payment_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Method</th>
                    <th>Amount (RM)</th>
                    <th>Account No</th>
                    <th>Cust ID</th>
                    <!-- Removed: <th>Receipt ID</th> -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $payment_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['payment_id']) ?></td>
                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                        <td><?= number_format($row['payment_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['account_no']) ?></td>
                        <td><?= htmlspecialchars($row['cust_id']) ?></td>
                        <!-- Removed: <td><?= htmlspecialchars($row['receipt_id']) ?></td> -->
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="payment_id" value="<?= $row['payment_id'] ?>">
                                <button type="submit" name="delete_payment" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">No records found in the payment table.</p>
    <?php endif; ?>
</div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
<script>
    window.onload = function() {
        alert("<?= $_SESSION['success_message'] ?>");
    };
</script>
<?php unset($_SESSION['success_message']); endif; ?>

<!-- Logout Script -->
<script>
    function confirmLogout(e) {
        e.preventDefault(); // Stop link from navigating immediately

        // Show confirmation dialog
        const isConfirmed = confirm("Are you sure you want to log out?");
        if (isConfirmed) {
            alert("You have been logged out successfully.");
            window.location.href = "login-administrator.php"; // Redirect to login page
        }
    }

    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this record?')) {
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>