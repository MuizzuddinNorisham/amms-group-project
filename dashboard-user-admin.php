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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Sidebar section start -->
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
                <a href="dashboard-profile-admin.php">
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
    <!-- Sidebar section end -->

    <div class="content">
        <h1 class="page-title">Admin Dashboard</h1>

        <div class="login-container">
            <h2>Staff Form</h2>

            <!-- Display Messages -->
            <?php if (!empty($message)): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- Add/Edit Form in Table Format -->
            <table>
                <form method="post" action="">
                    <input type="hidden" name="action" value="<?= isset($_GET['edit']) ? 'update' : 'add' ?>">
                    
                    <?php if (isset($_GET['edit'])):
                        $user = array_filter($users, fn($u) => $u['staff_email'] == $_GET['edit']);
                        $user = reset($user); ?>
                        <input type="hidden" name="original_email" value="<?= htmlspecialchars($user['staff_email']) ?>">
                    <?php endif; ?>

                    <tr>
                        <td><label for="name">Name</label></td>
                        <td><input type="text" id="name" name="username" value="<?= isset($user) ? htmlspecialchars($user['staff_name']) : '' ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="email">Email</label></td>
                        <td><input type="email" id="email" name="useremail" value="<?= isset($user) ? htmlspecialchars($user['staff_email']) : '' ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="password">Password</label></td>
                        <td><input type="password" id="password" name="userpass" value="<?= isset($user) ? htmlspecialchars($user['staff_pass']) : '' ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="phone">Phone</label></td>
                        <td><input type="tel" id="phone" name="userphone" value="<?= isset($user) ? htmlspecialchars($user['staff_phone']) : '' ?>"></td>
                    </tr>
                    <tr>
                        <td><label for="address">Address</label></td>
                        <td><input type="text" id="address" name="useraddress" value="<?= isset($user) ? htmlspecialchars($user['staff_address']) : '' ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit"><?= isset($_GET['edit']) ? 'Update' : 'Add' ?> User</button></td>
                    </tr>
                </form>
            </table>

            <hr>

            <!-- Staff List -->
            <h3>Staff List</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
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
                                <a href="?edit=<?= urlencode($user['staff_email']) ?>">Edit</a> |
                                <a href="?action=delete&email=<?= urlencode($user['staff_email']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <hr>

            <!-- Customer List -->
            <h3>Customer List</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
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

    <script>
    function confirmLogout(e) {
        e.preventDefault(); // Prevent default link behavior

        // Show a popup confirmation
        if (confirm("Are you sure you want to log out?")) {
            // Show success message using alert or custom popup
            alert("Logout successful!");
            window.location.href = "login-administrator.php"; // Redirect after confirmation
        }
    }
</script>
</body>
</html>
