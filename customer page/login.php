<?php
// Simple login check

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get username and password from POST request
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Replace with your desired credentials
    if ($username === 'admin' && $password === 'password123') {
        // Successful login - redirect to dashboard or home page
        header('Location: amms.html');
        exit();
    } else {
        // Invalid credentials - redirect back with error
        header('Location: test.html?error=1');
        exit();
    }
} else {
    // If accessed directly without POST, redirect to login page
    header('Location: test.html');
    exit();
}
?>