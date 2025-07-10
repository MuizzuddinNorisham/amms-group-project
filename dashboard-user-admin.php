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

    .add-user {
      padding: 10px 20px;
      background-color: #3cc;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background: #fff;
      margin: 50px auto;
      padding: 30px;
      width: 90%;
      max-width: 500px;
      border-radius: 10px;
      position: relative;
    }

    .close {
      position: absolute;
      right: 20px;
      top: 15px;
      font-size: 28px;
      cursor: pointer;
      color: #aaa;
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
        <button class="add-user">Add User</button>
      </div>

      <?php if (!empty($message)): ?>
        <div class="message"> <?= htmlspecialchars($message) ?> </div>
      <?php endif; ?>

      <h3>Staff List</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr data-user='<?= json_encode($user) ?>'>
              <td><?= htmlspecialchars($user['staff_name']) ?></td>
              <td><?= htmlspecialchars($user['staff_email']) ?></td>
              <td><?= htmlspecialchars($user['staff_phone']) ?></td>
              <td><?= htmlspecialchars($user['staff_address']) ?></td>
              <td>
                <button class="edit-btn">Edit</button>
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

  <div id="userModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="original_email" id="original_email">

        <label>Name</label>
        <input type="text" name="username" id="username" required>

        <label>Email</label>
        <input type="email" name="useremail" id="useremail" required>

        <label>Password</label>
        <input type="password" name="userpass" id="userpass" required>

        <label>Phone</label>
        <input type="tel" name="userphone" id="userphone">

        <label>Address</label>
        <textarea name="useraddress" id="useraddress"></textarea>

        <button type="submit">Save</button>
        <button type="reset" style="background-color:#aaa; margin-left:10px;">Reset</button>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById("userModal");
    const openBtn = document.querySelector(".add-user");
    const closeBtn = document.querySelector(".close");
    const form = modal.querySelector("form");

    openBtn.onclick = () => {
      form.reset();
      form.action.value = 'add';
      document.getElementById('original_email').value = '';
      modal.style.display = "block";
    };

    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = e => { if (e.target == modal) modal.style.display = "none"; };

    document.querySelectorAll(".edit-btn").forEach(btn => {
      btn.onclick = function () {
        const data = JSON.parse(this.closest("tr").dataset.user);
        form.action.value = 'update';
        document.getElementById('original_email').value = data.staff_email;
        document.getElementById('username').value = data.staff_name;
        document.getElementById('useremail').value = data.staff_email;
        document.getElementById('userpass').value = data.staff_pass;
        document.getElementById('userphone').value = data.staff_phone;
        document.getElementById('useraddress').value = data.staff_address;
        modal.style.display = "block";
      }
    });
  </script>
</body>
</html>
