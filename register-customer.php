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

  <!-- Font Awesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #1a1a2e;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      position: relative;
    }

    /* Home Icon Styles */
    .home-icon {
      position: absolute;
      top: 20px;
      right: 20px;
      color: #e84393;
      font-size: 24px;
      text-decoration: none;
      transition: color 0.3s ease;
      z-index: 10;
    }

    .home-icon:hover {
      color: #f06292;
    }

    .registration-card h1 {
      text-align: center;
      color: #e84393;
      margin-bottom: 10px;
    }

    .registration-card p {
      text-align: center;
      color: #ccc;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      color: #f1f5f9;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .input-wrapper input,
    .input-wrapper textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #334155;
      border-radius: 10px;
      background: #0f172a;
      color: #f1f5f9;
      font-size: 16px;
    }

    .input-wrapper input:focus,
    .input-wrapper textarea:focus {
      outline: none;
      border-color: #e84393;
      box-shadow: 0 0 0 3px rgba(232, 67, 147, 0.1);
    }

    .submit-btn {
      width: 100%;
      background: #e84393;
      color: white;
      border: none;
      padding: 14px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .submit-btn:hover {
      background: #d63384;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(232, 67, 147, 0.4);
    }

    .footer {
      text-align: center;
      margin-top: 20px;
    }

    .footer a {
      color: #e84393;
      text-decoration: none;
      font-weight: 600;
    }

    .footer a:hover {
      color: #f06292;
    }
  </style>
</head>
<body>

  <!-- Home Icon -->
  <a href="main-page.php" class="home-icon" title="Go to Homepage">
    <i class="fas fa-home"></i>
  </a>

  <div class="registration-container">
    <div class="registration-card">
      <h1>Create Account</h1>
      <p>Please fill in the details below</p>

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
            <textarea id="address" name="address" placeholder="City, State" required></textarea>
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