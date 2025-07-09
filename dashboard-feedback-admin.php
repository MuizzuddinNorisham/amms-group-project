<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-administrator.php");
    exit();
}

// Connect to database
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Fetch feedback data
$sql = "SELECT feedback_id, feedback_name, feedback_rate, feedback_details FROM feedback";
$result = $dbc->query($sql);

// Fetch all users (if needed for other functionalities)
$users = [];
$sql_users = "SELECT * FROM staff";
$result_users = $dbc->query($sql_users);
while ($row = $result_users->fetch_assoc()) {
    $users[] = $row;
}

$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Sidebar section start -->
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
                    <span class="icon"><i class="fas fa-user"></i></span>
                    <span class="text">Profile</span>
                </a>
            </li>
            <li>
                <a href="dashboard-user-admin.php">
                    <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                    <span class="text">User </span>
                </a>
            </li>
            <li>
                <a href="dashboard-feedback-admin.php" class="active">
                    <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                    <span class="text">Feedback</span>
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
    <!-- Sidebar section end -->

    <div class="content">
        <h1 class="page-title">Feedback</h1>

        <h2>Feedback List</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['feedback_id']) ?></td>
                            <td><?= htmlspecialchars($row['feedback_name']) ?></td>
                            <td><?= htmlspecialchars($row['feedback_rate']) ?></td>
                            <td><?= htmlspecialchars($row['feedback_details']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No feedback found.</p>
        <?php endif; ?>
    </div>

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
