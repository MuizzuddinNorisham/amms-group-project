<?php
<?php
// Simple login check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Replace with your desired credentials
    if ($username === 'admin' && $password === 'password123') {
        header('Location: tets.html');
        exit();
    } else {
        // Redirect back to login page with error (optional)
        header('Location: login.html?error=1');
        exit();
    }
} else {
    // If accessed directly, redirect to login page
    header('Location: login.html');
    exit();
}
?>