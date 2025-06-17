<?php
$conn = new mysqli("localhost", "root", "", "acrylic");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_quantity = $_POST['product_quantity'];
$product_type = $_POST['product_type'];
$product_design = $_POST['product_design'];
$product_font = $_POST['product_font'];

$sql = "INSERT INTO products (product_name, product_price, product_quantity, product_type, product_design, product_font)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdisss", $product_name, $product_price, $product_quantity, $product_type, $product_design, $product_font);

if ($stmt->execute()) {
    echo "✅ Product added successfully!<br>";
    echo "<a href='viewproducts.php'>Click here to view all products</a>";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
