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
            $message = "User  added successfully.";
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
            $message = "User  deleted successfully.";
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
            $message = "User  updated successfully.";
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

// Fetch all customers (NEW CODE)
$customers = [];
$sql = "SELECT * FROM customer"; // Query to fetch customers
$result = $dbc->query($sql);
while ($row = $result->fetch_assoc()) {
    $customers[] = $row; // Store customer data
}

$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Form</title>
  <link rel="stylesheet" href="account-management-admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
</head>
<body>

<div class="login-container">
  <!-- Back Button -->
    <a href="dashboard-admin.php" class="back-button" title="Go Back">
      <i class="fas fa-arrow-left"></i>
    </a>
    
  <h2>Staff Form</h2>

  <!-- Display Messages -->
  <?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <!-- Add/Edit Form -->
  <form method="post" action="">
    <input type="hidden" name="action" value="<?= isset($_GET['edit']) ? 'update' : 'add' ?>">
    
    <?php if (isset($_GET['edit'])):
      $user = array_filter($users, fn($u) => $u['staff_email'] == $_GET['edit']);
      $user = reset($user); ?>
      <input type="hidden" name="original_email" value="<?= htmlspecialchars($user['staff_email']) ?>">
    <?php endif; ?>

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="username" value="<?= isset($user) ? htmlspecialchars($user['staff_name']) : '' ?>" required>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="useremail" value="<?= isset($user) ? htmlspecialchars($user['staff_email']) : '' ?>" required>
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="userpass" value="<?= isset($user) ? htmlspecialchars($user['staff_pass']) : '' ?>" required>
    </div>

    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" id="phone" name="userphone" value="<?= isset($user) ? htmlspecialchars($user['staff_phone']) : '' ?>">
    </div>

    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" id="address" name="useraddress" value="<?= isset($user) ? htmlspecialchars($user['staff_address']) : '' ?>">
    </div>

    <button type="submit"><?= isset($_GET['edit']) ? 'Update' : 'Add' ?> User</button>
  </form>

  <hr>

  <!-- Staff List -->
  <h3>Staff List</h3>
  <ul>
    <?php foreach ($users as $user): ?>
      <li>
        <strong><?= htmlspecialchars($user['staff_name']) ?></strong> |
        <?= htmlspecialchars($user['staff_email']) ?> |
        <?= htmlspecialchars($user['staff_phone']) ?> |
        <?= htmlspecialchars($user['staff_address']) ?>
        <br>
        <a href="?edit=<?= urlencode($user['staff_email']) ?>">Edit</a> |
        <a href="?action=delete&email=<?= urlencode($user['staff_email']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
      </li>
    <?php endforeach; ?>
  </ul>

  <hr>

  <!-- Customer List (NEW CODE) -->
  <h3>Customer List</h3>
  <ul>
    <?php foreach ($customers as $customer): ?>
      <li>
        <strong><?= htmlspecialchars($customer['cust_name']) ?></strong> |
        <?= htmlspecialchars($customer['cust_email']) ?> |
        <?= htmlspecialchars($customer['cust_phone']) ?> |
        <?= htmlspecialchars($customer['cust_address']) ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

</body>
</html>