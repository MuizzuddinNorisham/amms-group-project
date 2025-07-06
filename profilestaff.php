
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard-staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                src="https://placehold.co/140x140/png?text=Staff"
                alt="Avatar illustration"
                width="140"
                height="140"
                loading="lazy"
            />
            <h2 id="profile-name" class="profile-name">John Doe</h2>
            <p class="profile-role">Staff Member</p>
        </article>

        <article class="info-card" role="region" aria-labelledby="info-title">
            <h3 id="info-title" class="info-title">Information</h3>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-id-badge" style="margin-right: 6px;"></i>ID</div>
                <div class="info-value"></div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-user" style="margin-right: 6px;"></i>Full Name</div>
                <div class="info-value">John Doe</div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-envelope" style="margin-right: 6px;"></i>Email</div>
                <div class="info-value">johndoe@example.com</div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-phone" style="margin-right: 6px;"></i>Phone</div>
                <div class="info-value">012-3456789</div>
            </div>
            <div class="info-row">
                <div class="info-label"><i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>Address</div>
                <div class="info-value">123 Jalan Example, Kuala Lumpur</div>
            </div>
        </article>
    </section>
</main>
</body>
</html>
