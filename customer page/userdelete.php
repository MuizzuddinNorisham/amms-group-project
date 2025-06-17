<?php
$data = json_decode(file_get_contents("php://input"), true);

$userid = $data['userid'];

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$stmt = $dbc->prepare("DELETE FROM user WHERE userid=?");
$stmt->bind_param("i", $userid);

if ($stmt->execute()) {
    echo "User deleted successfully";
} else {
    echo "Delete failed";
}

$stmt->close();
$dbc->close();
?>
