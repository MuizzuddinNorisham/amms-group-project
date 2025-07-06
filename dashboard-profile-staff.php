<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login-administrator.php"); // Redirect to login page
    exit();
}

// Connect to database
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Get staff info from session
$staff_id = $_SESSION['staff_id'];
// Fetch user data
$stmt = $dbc->prepare("SELECT staff_id, staff_name, staff_phone, staff_address, staff_email FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard-staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"  crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Staff Profile</title>
</head>
<body>

<!-- Sidebar Section -->
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
            <a href="dashboard-profile-staff.php" class="active">
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
            <a href="order.html">
                <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="text">Order</span>
            </a>
        </li>
        <li>
            <a href="main-page.php" class="logout">
                <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
                <span class="text">Log out</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<main class="content">
    <h1 class="page-title">Your Profile</h1>
    <section class="profile-container" aria-label="User Profile Information">
        <article class="profile-card" role="region" aria-labelledby="profile-name">
            <img
                src="https://placehold.co/140x140/png?text=<?= urlencode($user['staff_name']) ?>"
                alt="Avatar illustration for <?= htmlspecialchars($user['staff_name']) ?>"
                width="140"
                height="140"
                loading="lazy"
            />
            <h2 id="profile-name" class="profile-name"><?= htmlspecialchars($user['staff_name']) ?></h2>
            <p class="profile-role">Staff Member</p>
        </article>

        <article class="info-card" role="region" aria-labelledby="info-title">
            <h3 id="info-title" class="info-title">Information</h3>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-id-badge" style="margin-right: 6px;"></i>ID</div>
                <div class="info-value"><?= htmlspecialchars($user['staff_id']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-user" style="margin-right: 6px;"></i>Full Name</div>
                <div class="info-value"><?= htmlspecialchars($user['staff_name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-envelope" style="margin-right: 6px;"></i>Email</div>
                <div class="info-value"><?= htmlspecialchars($user['staff_email']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-phone" style="margin-right: 6px;"></i>Phone</div>
                <div class="info-value"><?= htmlspecialchars($user['staff_phone']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>Address</div>
                <div class="info-value"><?= htmlspecialchars($user['staff_address']) ?></div>
            </div>
        </article>
    </section>
</main>
</body>
</html>