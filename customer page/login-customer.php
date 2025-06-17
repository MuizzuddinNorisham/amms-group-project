<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'acrylic';
$username = 'root'; // default XAMPP/WAMP username
$password = '';     // leave blank if no password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Form data
    $fullname = $_POST['fullname'] ?? '';
    $address = $_POST['address'] ?? '';
    $number = $_POST['number'] ?? '';
    $pass = $_POST['password'] ?? '';
    $confirmPass = $_POST['confirmPassword'] ?? '';

    // Validation
    if (empty($fullname) || empty($address) || empty($number) || empty($pass) || empty($confirmPass)) {
        die("<script>alert('All fields are required.'); window.history.back();</script>");
    }

    if ($pass !== $confirmPass) {
        die("<script>alert('Passwords do not match.'); window.history.back();</script>");
    }

    // Generate unique customer ID (e.g., C001, C002)
    $stmt = $pdo->query("SELECT MAX(cust_id) AS max_id FROM customer");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastId = $result['max_id'] ?? 'C000';
    $nextId = intval(substr($lastId, 1)) + 1;
    $custId = 'C' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    // Insert data (without password hashing)
    $insert = $pdo->prepare("INSERT INTO customer (cust_id, cust_name, cust_address, cust_phone, cust_pass) VALUES (?, ?, ?, ?, ?)");
    $insert->execute([$custId, $fullname, $address, $number, $pass]);

    echo "<script>alert('Account created successfully! Your ID is $custId'); window.location.href = 'login.html';</script>";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Account</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background: white;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 350px;
    }
    .form-container h2 {
      margin-bottom: 20px;
      color: #e84393;
      text-align: center;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input {
      width: 100%;
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background: #e84393;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #ff69b4;
    }
    .footer {
      margin-top: 15px;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Create Account</h2>
    <form action="register.php" method="POST">
      <div class="form-group">
        <label for="fullname">Full Name</label>
        <input type="text" name="fullname" required />
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" required />
      </div>
      <div class="form-group">
        <label for="number">Phone Number</label>
        <input type="text" name="number" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" required />
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" name="confirmPassword" required />
      </div>
      <button type="submit">Create Account</button>
    </form>
    <div class="footer">
      Already have an account? <a href="login.html">Login</a>
    </div>
  </div>

</body>
</html>