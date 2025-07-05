<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "acrylic");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query based on the role
    if ($role === 'admin') {
        // Query the admin table
        $sql = "SELECT * FROM admin WHERE admin_name = ? AND admin_pass = ?";
        $table = "admin";
    } elseif ($role === 'staff') {
        // Query the staff table
        $sql = "SELECT * FROM staff WHERE staff_name = ? AND staff_pass = ?";
        $table = "staff";
    } else {
        echo "<script>alert('Please select a valid role.'); window.location.href='login-administrator.php';</script>";
        exit();
    }

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        // Start session and store user data
        session_start();
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on the role
        if ($role === 'admin') {
            header("Location: dashboard-admin.php");
        } elseif ($role === 'staff') {
            header("Location: dashboard-staff.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href='login-administrator.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Administrator Login</title>
  <link rel="stylesheet" href="login-administrator.css" />
</head>
<body>

  <div class="wrapper">
    <h1>Login</h1>
    <form action="" method="POST">
      
      <!-- Username -->
      <div class="input-box">
        <input type="text" name="username" placeholder="Username" required />
        <i class="fa-solid fa-user"></i>
      </div>

      <!-- Password -->
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required />
        <i class="fa-solid fa-lock"></i>
      </div>

      <!-- Admin/Staff Role Selection -->
      <div class="admin-staff">
        <label><input type="radio" name="role" value="admin" required /> Admin</label>
        <label><input type="radio" name="role" value="staff" required /> Staff</label>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn">Login</button>

      <!-- Register Link -->
      <div class="register-link">
        <p>Back to <a href="main-page.php">Home</a></p>
      </div>
    </form>
  </div>

</body>
</html>