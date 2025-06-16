<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // plain password
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Connect to MySQL database
    $conn = new mysqli("localhost", "root", "", "acrylic");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into customer table (without hashing)
    $sql = "INSERT INTO customer (cust_name, cust_email, cust_pass, cust_phone, cust_address) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='customer-login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Registration</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }

    .container {
      max-width: 400px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
    }

    label {
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"],
    textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
    }

    button:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Register</h2>
  <form action="" method="post">
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" placeholder="Full Name" required />

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="example@example.com" required />

    <label for="address">Address:</label>
    <textarea id="address" name="address" rows="3" placeholder="123 Main St, City" required></textarea>

    <label for="phone">Phone Number:</label>
    <input type="tel" id="phone" name="phone" placeholder="123-456-7890" required />

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required />

    <button type="submit">Register</button>
  </form>
</div>

</body>
</html>