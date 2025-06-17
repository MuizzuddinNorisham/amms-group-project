<?php
$data = json_decode(file_get_contents("php://input"), true);

$userid = $data['userid'];
$username = $data['username'];
$useremail = $data['useremail'];
$userpass = $data['userpass'];

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$stmt = $dbc->prepare("UPDATE user SET username=?, useremail=?, userpass=? WHERE userid=?");
$stmt->bind_param("sssi", $username, $useremail, $userpass, $userid);

if ($stmt->execute()) {
    echo "User updated successfully";
} else {
    echo "Update failed";
}

$stmt->close();
$dbc->close();
?>
