<?php
session_start();

// Check if user is logged in as customer
if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php"); // Redirect to customer login page
    exit();
}

if (isset($_SESSION['payment_success'])) {
    echo "<div style='text-align:center; color:green; margin-top:20px;'>";
    echo $_SESSION['payment_success'];
    echo "</div>";
    unset($_SESSION['payment_success']); // Clear message after showing once
}

// Connect to database
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Get customer info from session
$cust_id = $_SESSION['cust_id'];

// Fetch customer data
$stmt = $dbc->prepare("SELECT cust_id, cust_name, cust_phone, cust_address, cust_email FROM customer WHERE cust_id = ?");
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Customer not found.");
}

$customer = $result->fetch_assoc();
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="dashboard-customer.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Customer Dashboard</title>
    </head>
    <body>
        <!--sidebar section start-->
        
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
    <a href="#" onclick="confirmLogout(event)" class="logout">
        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
        <span class="text">Log out</span>
    </a>
</li>
            </ul>  
        </div>

        <script>
    function confirmLogout(event) {
        event.preventDefault(); // Prevent default link behavior

        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "login-customer.php"; // Redirect to logout page
        }
    }
</script>
    </body>
</html>

<!-- Main Content -->
<main class="content">
    <h1 class="page-title">Your Profile</h1>
    <section class="profile-container" aria-label="User Profile Information">
        <article class="profile-card" role="region" aria-labelledby="profile-name">
            <img
                src="https://placehold.co/140x140/png?text=<?= urlencode($customer['cust_name']) ?>"
                alt="Avatar illustration for <?= htmlspecialchars($customer['cust_name']) ?>"
                width="140"
                height="140"
                loading="lazy"
            />
            <h2 id="profile-name" class="profile-name"><?= htmlspecialchars($customer['cust_name']) ?></h2>
            <p class="profile-role">Customer</p>
        </article>

        <article class="info-card" role="region" aria-labelledby="info-title">
            <h3 id="info-title" class="info-title">Information</h3>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-id-badge" style="margin-right: 6px;"></i>ID</div>
                <div class="info-value"><?= htmlspecialchars($customer['cust_id']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-user" style="margin-right: 6px;"></i>Full Name</div>
                <div class="info-value"><?= htmlspecialchars($customer['cust_name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-envelope" style="margin-right: 6px;"></i>Email</div>
                <div class="info-value"><?= htmlspecialchars($customer['cust_email']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-phone" style="margin-right: 6px;"></i>Phone</div>
                <div class="info-value"><?= htmlspecialchars($customer['cust_phone']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>Address</div>
                <div class="info-value"><?= htmlspecialchars($customer['cust_address']) ?></div>
            </div>
        </article>
    </section>
</main>
</body>
</html>