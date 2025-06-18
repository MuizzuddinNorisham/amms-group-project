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
        echo "<script>alert('Registration successful!'); window.location.href='login-customer.php';</script>";
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
  <link rel="stylesheet" href="register-customer.css"/>
</head>
<body>

<div class="registration-container">
  <div class="registration-card">
    <div class="header">
      <h1>Create Account</h1>
      <p>Please fill in the details below</p>
    </div>

    <form action="" method="post">
  <div class="form-group">
    <label for="name">Full Name:</label>
    <div class="input-wrapper">
      <input type="text" id="name" name="name" placeholder="Full Name" required />
    </div>
  </div>

  <div class="form-group">
    <label for="email">Email Address:</label>
    <div class="input-wrapper">
      <input type="email" id="email" name="email" placeholder="example@example.com" required />
    </div>
  </div>

  <div class="form-group">
    <label for="address">Address:</label>
    <div class="input-wrapper">
      <textarea id="address" name="address" placeholder="City,State" required></textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="phone">Phone Number:</label>
    <div class="input-wrapper">
      <input type="tel" id="phone" name="phone" placeholder="123-456-7890" required />
    </div>
  </div>

  <div class="form-group">
    <label for="password">Password:</label>
    <div class="input-wrapper">
      <input type="password" id="password" name="password" placeholder="Enter password" required />
    </div>
  </div>

  <button type="submit" class="submit-btn">Register</button>
</form>

    <div class="footer">
      <p>Already have an account? <a href="login-customer.php">Login here</a></p>
    </div>
  </div>
</div>

</body>
</html>