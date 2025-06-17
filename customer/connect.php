<?php
$cust_name = $_POST["cust_name"];
$cust_email = $_POST["cust_email"];
$cust_pass = $_POST["cust_pass"];

// Database connection
$conn = new mysqli("localhost", "root", "", "acrylic");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO customer (cust_name, cust_email, cust_pass) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $cust_name, $cust_email, $hashed_password);

// Execute the statement
if ($stmt->execute()) {
    echo "Registration successful!";
    // Optional: Redirect after success
    // header("Location: login.html");
    // exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>