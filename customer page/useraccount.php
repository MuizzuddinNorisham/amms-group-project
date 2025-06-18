<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $dbc->connect_error]));
}

$action = isset($_POST['action']) ? $_POST['action'] : (isset($data['action']) ? $data['action'] : '');

switch ($action) {
    case 'add':
        // Handle Add
        $username = $dbc->real_escape_string($_POST['username'] ?? '');
        $useremail = $dbc->real_escape_string($_POST['useremail'] ?? '');
        $userpass = $dbc->real_escape_string($_POST['userpass'] ?? '');
        $userphone = $dbc->real_escape_string($_POST['userphone'] ?? '');
        $useraddress = $dbc->real_escape_string($_POST['useraddress'] ?? '');

        if (empty($username) || empty($useremail) || empty($userpass) || empty($userphone) || empty($useraddress)) {
            echo json_encode(["success" => false, "message" => "All fields are required"]);
            exit;
        }

        $sql = "INSERT INTO staff (staff_name, staff_email, staff_pass, staff_phone, staff_address)
                VALUES ('$username', '$useremail', '$userpass', '$userphone', '$useraddress')";

        if ($dbc->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "User added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding user: " . $dbc->error]);
        }
        break;

    case 'edit':
        // Handle Edit
        $originalEmail = $dbc->real_escape_string($data['originalEmail'] ?? '');
        $username = $dbc->real_escape_string($data['username'] ?? '');
        $useremail = $dbc->real_escape_string($data['useremail'] ?? '');
        $userpass = $dbc->real_escape_string($data['userpass'] ?? '');
        $userphone = $dbc->real_escape_string($data['userphone'] ?? '');
        $useraddress = $dbc->real_escape_string($data['useraddress'] ?? '');

        if (empty($originalEmail) || empty($username) || empty($useremail) || empty($userpass) || empty($userphone) || empty($useraddress)) {
            echo json_encode(["success" => false, "message" => "All fields are required"]);
            exit;
        }

        $sql = "UPDATE staff SET 
                    staff_name = '$username',
                    staff_email = '$useremail',
                    staff_pass = '$userpass',
                    staff_phone = '$userphone',
                    staff_address = '$useraddress'
                WHERE staff_email = '$originalEmail'";

        if ($dbc->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "User updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating user: " . $dbc->error]);
        }
        break;

    case 'delete':
        // Handle Delete
        $useremail = $dbc->real_escape_string($data['useremail'] ?? '');

        if (empty($useremail)) {
            echo json_encode(["success" => false, "message" => "Email is required"]);
            exit;
        }

        $sql = "DELETE FROM staff WHERE staff_email = '$useremail'";

        if ($dbc->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "User deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting user: " . $dbc->error]);
        }
        break;

    default:
        echo json_encode(["success" => false, "message" => "Invalid action specified"]);
        break;
}

$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Form</title>
  <link rel="stylesheet" href="useraccount.css" />
</head>
<body>
  <form name="user" method="post" action="">
    <div class="login-container">
      <div class="top-buttons">
        <button type="button" onclick="alert('Going Back')">Back</button>
        <button type="button" onclick="alert('Going Home')">Home</button>
      </div>

      <header class="login-header">
        <h1>Staff Form</h1>
      </header>

      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="username" placeholder="Enter name" />
      </div>

      <div class="form-group">
        <label for="email">Staff Email</label>
        <input type="email" id="email" name="useremail" placeholder="Enter email" />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="userpass" placeholder="Enter password" />
      </div>

      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="userphone" placeholder="Enter phone number" />
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="useraddress" placeholder="Enter address" />
      </div>

      <div class="action-buttons">
        <button type="button" id="editBtn">Edit</button>
        <button type="submit" name="add" id="addBtn">Add</button>
        <button type="button" id="deleteBtn">Delete</button>
      </div>

      <ul id="userList"></ul>
    </div>
  </form>

  <script src="useraccount.js"></script>
</body>
</html>
