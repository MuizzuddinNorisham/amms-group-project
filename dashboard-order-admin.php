<?php
// Start session
session_start();

// Check if user is logged in and is a staff member
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-administrator.php");
    exit();
}

// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Fetch cart data
$cart_query = "
    SELECT cart_id, cart_status, cart_quantity, cart_created, cart_total, cust_id, product_id 
    FROM cart";
$cart_result = $dbc->query($cart_query);

// Fetch payment data
$payment_query = "
    SELECT payment_id, payment_method, payment_amount, account_no, cust_id, receipt_id 
    FROM payment";
$payment_result = $dbc->query($payment_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Styles -->
    <link rel="stylesheet" href="dashboard-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
        integrity="sha512-pV1pHpZ5gF..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .charts-container {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .chart-container {
            max-width: 500px;
            width: 100%;
            height: 300px;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .no-data {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <li>
            <a href="#" class="logo">
                <span class="icon"><i class="fa-solid fa-user-shield"></i></span>
                <span class="text">Admin</span>
            </a>
        </li>
        <li>
            <a href="dashboard-admin.php">
                <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="dashboard-profile-admin.php">
                <span class="icon"><i class="fa-solid fa-user-shield"></i></span>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="dashboard-user-admin.php">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <span class="text">User</span>
            </a>
        </li>
        <li>
            <a href="dashboard-feedback-admin.php">
                <span class="icon"><i class="fa-solid fa-comment"></i></span>
                <span class="text">Feedback</span>
            </a>
        </li>
        <li>
            <a href="dashboard-order-admin.php">
                <span class="icon"><i class="fa-solid fa-clipboard"></i></span>
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
        <div class="table-container">
        <div class="table-wrapper">
            <h2>Cart Table</h2>
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
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found in the cart table.</p>
            <?php endif; ?>
        </div>

        <div class="table-wrapper">
            <h2>Payment Table</h2>
            <?php if ($payment_result && $payment_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Method</th>
                            <th>Amount (RM)</th>
                            <th>Account No</th>
                            <th>Cust ID</th>
                            <th>Receipt ID</th>
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
                                <td><?= htmlspecialchars($row['receipt_id']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found in the payment table.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Logout Script -->
<script>
    function confirmLogout(e) {
        e.preventDefault(); // Stop the link from navigating immediately

        // Show confirmation dialog
        const isConfirmed = confirm("Are you sure you want to log out?");

        if (isConfirmed) {
            alert("You have been logged out successfully.");
            window.location.href = "login-administrator.php"; // Redirect to login page
        }
    }
</script>

</body>
</html>
