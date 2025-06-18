<?php
$data = json_decode(file_get_contents("php://input"), true);

$userid = $data['userid'];
$username = $data['username'];
$useremail = $data['useremail'];
$userpass = $data['userpass'];
$userphone = $data['userphone']; // Retrieve phone number
$useraddress = $data['useraddress']; // Retrieve address

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Update statement to include phone and address
$stmt = $dbc->prepare("UPDATE user SET username=?, useremail=?, userpass=?, userphone=?, useraddress=? WHERE userid=?");
$stmt->bind_param("sssssi", $username, $useremail, $userpass, $userphone, $useraddress, $userid);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User  updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Update failed"]);
}

$stmt->close();
$dbc->close();
?>