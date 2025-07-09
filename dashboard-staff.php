<?php
// Optional: Start session if you plan to use $_SESSION variables later
session_start();

// Check if the user is logged in and has staff privileges
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'staff') {
    header("Location: login-administrator.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="dashboard-staff.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Staff</title>
    </head>
    <body>
        <!--sidebar section start-->
        
        <div class="sidebar" >
            <ul>
                <li>
                    <a href="#" class="logo">
                        <span class="icon"><i class="fa-solid fa-users"></i></i></span>
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
                    <a href="dashboard-profile-staff.php" >
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
                    <a href="login-administrator.php" class="logout">
                        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></i></span>
                        <span class="text">Log out</span>
                    </a>
                </li>
            </ul>  
        </div>
        <div class="content">
            <h1 class="page-title">Dashboard</h1>
        </div>
    
    </body>
</html>