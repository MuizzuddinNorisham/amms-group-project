<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "acrylic");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Make sure all required POST fields are set
if (
    isset($_POST['product_name']) &&
    isset($_POST['product_price']) &&
    isset($_POST['product_quantity']) &&
    isset($_POST['product_type']) &&
    isset($_POST['product_design']) &&
    isset($_POST['product_font'])
) {
    // Sanitize and assign values
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    $product_type = $_POST['product_type'];
    $product_design = $_POST['product_design'];
    $product_font = $_POST['product_font'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_price, product_quantity, product_type, product_design, product_font) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $product_name, $product_price, $product_quantity, $product_type, $product_design, $product_font);

    if ($stmt->execute()) {
        echo "Product added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Missing form data.";
}

$conn->close();
?>
