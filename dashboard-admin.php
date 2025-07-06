<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-administrator.php");
    exit();
}
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
        <!--sidebar section start-->
        
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
                        <span class="text">User</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard-feedback-admin.php">
                        <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                        <span class="text">Feedback</span>
                    </a>
                </li>
                <li>
                    <a href="login-administrator.php" class="logout">
                        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
                        <span class="text">Log out</span>
                    </a>
                </li>
            </ul>  
        </div>
        <div class="content">
            <h1 class="page-title">Admin Dashboard</h1>
        </div>
    
    </body>
</html>
