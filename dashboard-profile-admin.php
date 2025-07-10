<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: main-page.php"); // Redirect to login page
    exit();
}

// Connect to database
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Get admin info from session
$admin_id = $_SESSION['admin_id'];

// Fetch admin data
$stmt = $dbc->prepare("SELECT admin_id, admin_name, admin_phone, admin_email FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User  not found.");
}

$admin = $result->fetch_assoc();
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Profile</title>
</head>
<body>

<!-- Sidebar Section -->
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
            <a href="dashboard-profile-admin.php" class="active">
                <span class="icon"><i class="fa-solid fa-user-shield"></i></span>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="dashboard-user-admin.php">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <span class="text">User </span>
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
<main class="content">
    <h1 class="page-title">Your Profile</h1>
    <section class="profile-container" aria-label="Admin Profile Information">
        <article class="profile-card" role="region" aria-labelledby="profile-name">
            <img
                src="https://placehold.co/140x140/png?text=<?= urlencode($admin['admin_name']) ?>"
                alt="Avatar illustration for <?= htmlspecialchars($admin['admin_name']) ?>"
                width="140"
                height="140"
                loading="lazy"
            />
            <h2 id="profile-name" class="profile-name"><?= htmlspecialchars($admin['admin_name']) ?></h2>
            <p class="profile-role">Admin Member</p>
        </article>

        <article class="info-card" role="region" aria-labelledby="info-title">
            <h3 id="info-title" class="info-title">Information</h3>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-id-badge" style="margin-right: 6px;"></i>ID</div>
                <div class="info-value"><?= htmlspecialchars($admin['admin_id']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-user" style="margin-right: 6px;"></i>Full Name</div>
                <div class="info-value"><?= htmlspecialchars($admin['admin_name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-envelope" style="margin-right: 6px;"></i>Email</div>
                <div class="info-value"><?= htmlspecialchars($admin['admin_email']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-phone" style="margin-right: 6px;"></i>Phone</div>
                <div class="info-value"><?= htmlspecialchars($admin['admin_phone']) ?></div>
            </div>
        </article>
    </section>
</main>

<script>
    function confirmLogout(e) {
        e.preventDefault(); // Prevent default link behavior

        // Show a popup confirmation
        if (confirm("Are you sure you want to log out?")) {
            // Show success message using alert or custom popup
            alert("Logout successful!");
            window.location.href = "login-administrator.php"; // Redirect after confirmation
        }
    }
</script>
</body>
</html>
