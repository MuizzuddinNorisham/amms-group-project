<?php
$data = json_decode(file_get_contents("php://input"), true);

$useremail = $data['useremail'];

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$stmt = $dbc->prepare("DELETE FROM user WHERE useremail=?");
$stmt->bind_param("s", $useremail);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Delete failed"]);
}

$stmt->close();
$dbc->close();
?>