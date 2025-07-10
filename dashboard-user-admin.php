<?php
// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$message = "";

// Add User
if ($action === 'add') {
    $username = $dbc->real_escape_string($_POST['username'] ?? '');
    $useremail = $dbc->real_escape_string($_POST['useremail'] ?? '');
    $userpass = $dbc->real_escape_string($_POST['userpass'] ?? '');
    $userphone = $dbc->real_escape_string($_POST['userphone'] ?? '');
    $useraddress = $dbc->real_escape_string($_POST['useraddress'] ?? '');

    if (!$username || !$useremail || !$userpass || !$userphone || !$useraddress) {
        $message = "All fields are required.";
    } else {
        $sql = "INSERT INTO staff (staff_name, staff_email, staff_pass, staff_phone, staff_address)
                VALUES ('$username', '$useremail', '$userpass', '$userphone', '$useraddress')";
        if ($dbc->query($sql)) {
            $message = "User added successfully.";
        } else {
            $message = "Error adding user: " . $dbc->error;
        }
    }
}

// Delete User
if ($action === 'delete') {
    $useremail = $dbc->real_escape_string($_GET['email'] ?? '');
    if ($useremail) {
        $sql = "DELETE FROM staff WHERE staff_email = '$useremail'";
        if ($dbc->query($sql)) {
            $message = "User deleted successfully.";
        } else {
            $message = "Error deleting user: " . $dbc->error;
        }
    }
}

// Update User
if ($action === 'update') {
    $originalEmail = $dbc->real_escape_string($_POST['original_email'] ?? '');
    $username = $dbc->real_escape_string($_POST['username'] ?? '');
    $useremail = $dbc->real_escape_string($_POST['useremail'] ?? '');
    $userpass = $dbc->real_escape_string($_POST['userpass'] ?? '');
    $userphone = $dbc->real_escape_string($_POST['userphone'] ?? '');
    $useraddress = $dbc->real_escape_string($_POST['useraddress'] ?? '');

    if (!$originalEmail || !$username || !$useremail || !$userpass || !$userphone || !$useraddress) {
        $message = "All fields are required.";
    } else {
        $sql = "UPDATE staff SET 
                    staff_name = '$username',
                    staff_email = '$useremail',
                    staff_pass = '$userpass',
                    staff_phone = '$userphone',
                    staff_address = '$useraddress'
                WHERE staff_email = '$originalEmail'";

        if ($dbc->query($sql)) {
            $message = "User updated successfully.";
        } else {
            $message = "Error updating user: " . $dbc->error;
        }
    }
}

// Fetch all users
$users = [];
$sql = "SELECT * FROM staff";
$result = $dbc->query($sql);
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Fetch all customers
$customers = [];
$sql = "SELECT * FROM customer"; 
$result = $dbc->query($sql);
while ($row = $result->fetch_assoc()) {
    $customers[] = $row; 
}

$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - User Management</title>
  <link rel="stylesheet" href="dashboard-admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f0f0f0;
      display: flex;
    }

    .content-wrapper {
      margin-left: 200px;
      padding: 20px;
      flex: 1;
    }

    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #3cc;
      color: white;
    }

    .edit-btn, .delete-btn {
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
    }

    .edit-btn {
      background-color: green;
    }

    .delete-btn {
      background-color: red;
    }

    .form-section {
      margin-bottom: 40px;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      padding: 10px 20px;
      border: none;
      background-color: #3cc;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <ul>
        <li>
            <a href="#" class="logo">
                <span class="icon"><i class="fa-solid fa-user-shield"></i></span>
                <span class="text">Admin</span>
            </a>
        </li>
        <li>
            <a href="dashboard-admin.php">
                <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="dashboard-profile-admin.php" class="active">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="dashboard-user-admin.php">
                <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                <span class="text">User </span>
            </a>
        </li>
        <li>
            <a href="dashboard-feedback-admin.php">
                <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="text">Feedback</span>
            </a>
        </li>
        <li>
    <a href="login-administrator.php" class="logout" onclick="confirmLogout(event)">
        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
        <span class="text">Log out</span>
    </a>
</li>
    </ul>
</div>
  <div class="content-wrapper">
    <div class="container">
      <div class="header">
        <h2>User Management</h2>
      </div>

      <?php if (!empty($message)): ?>
        <div class="message"> <?= htmlspecialchars($message) ?> </div>
      <?php endif; ?>

      <div class="form-section">
        <form method="POST">
          <input type="hidden" name="action" value="<?= isset($_GET['edit']) ? 'update' : 'add' ?>">
          <?php if (isset($_GET['edit'])):
            $user = array_filter($users, fn($u) => $u['staff_email'] == $_GET['edit']);
            $user = reset($user); ?>
            <input type="hidden" name="original_email" value="<?= htmlspecialchars($user['staff_email']) ?>">
          <?php endif; ?>

          <label>Name</label>
          <input type="text" name="username" value="<?= isset($user) ? htmlspecialchars($user['staff_name']) : '' ?>" required>

          <label>Email</label>
          <input type="email" name="useremail" value="<?= isset($user) ? htmlspecialchars($user['staff_email']) : '' ?>" required>

          <label>Password</label>
          <input type="password" name="userpass" value="<?= isset($user) ? htmlspecialchars($user['staff_pass']) : '' ?>" required>

          <label>Phone</label>
          <input type="tel" name="userphone" value="<?= isset($user) ? htmlspecialchars($user['staff_phone']) : '' ?>">

          <label>Address</label>
          <textarea name="useraddress"><?= isset($user) ? htmlspecialchars($user['staff_address']) : '' ?></textarea>

          <button type="submit"> <?= isset($_GET['edit']) ? 'Update' : 'Add' ?> User </button>
        </form>
      </div>

      <h3>Staff List</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['staff_name']) ?></td>
              <td><?= htmlspecialchars($user['staff_email']) ?></td>
              <td><?= htmlspecialchars($user['staff_phone']) ?></td>
              <td><?= htmlspecialchars($user['staff_address']) ?></td>
              <td>
                <a href="?edit=<?= urlencode($user['staff_email']) ?>" class="edit-btn">Edit</a>
                <a href="?action=delete&email=<?= urlencode($user['staff_email']) ?>" onclick="return confirm('Are you sure?')" class="delete-btn">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h3>Customer List</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Phone</th><th>Address</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $customer): ?>
            <tr>
              <td><?= htmlspecialchars($customer['cust_name']) ?></td>
              <td><?= htmlspecialchars($customer['cust_email']) ?></td>
              <td><?= htmlspecialchars($customer['cust_phone']) ?></td>
              <td><?= htmlspecialchars($customer['cust_address']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
