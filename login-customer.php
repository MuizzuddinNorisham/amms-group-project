<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "acrylic");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the customer table
    $stmt = $conn->prepare("SELECT cust_id, cust_pass FROM customer WHERE cust_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($cust_id, $db_password);

        if ($stmt->fetch()) {
            // For now, comparing plain text passwords
            if ($password === $db_password) {
                session_start();
                $_SESSION['cust_id'] = $cust_id;
                header("Location: dashboard-customer.php"); // Redirect to dashboard or homepage
                exit();
            } else {
                echo "<script>alert('Incorrect password'); window.location.href='login-customer.php';</script>";
            }
        }
    } else {
        echo "<script>alert('No user found with that email'); window.location.href='login-customer.php';</script>";
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
  <title>Customer Login</title>
  <link rel="stylesheet" href="login-customer.css"/>
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
    }

    .home-icon:hover {
      color: #f06292;
    }

    .login-container {
      background: #16213e;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(232, 67, 147, 0.3);
      width: 100%;
      max-width: 400px;
      z-index: 1;
    }

    .login-container h2 {
      text-align: center;
      color: #e84393;
      margin-bottom: 20px;
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

    .form-group input {
      width: 100%;
      padding: 12px;
      border: 2px solid #334155;
      border-radius: 10px;
      background: #0f172a;
      color: #f1f5f9;
      font-size: 16px;
    }

    .form-group input:focus {
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

  <div class="login-container">
    <h2>Login</h2>
    <form action="login-customer.php" method="POST">
      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="example@example.com" required />
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />
      </div>

      <button type="submit" class="submit-btn">Login</button>
    </form>

    <div class="footer">
      <p>Don't have an account? <a href="register-customer.php">Register here</a></p>
    </div>
  </div>

</body>
</html>