<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'acrylic';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['originalEmail'], $data['username'], $data['useremail'], $data['userpass'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$originalEmail = $conn->real_escape_string($data['originalEmail']);
$username = $conn->real_escape_string($data['username']);
$useremail = $conn->real_escape_string($data['useremail']);
$userpass = $conn->real_escape_string($data['userpass']);

// Update query
$sql = "UPDATE users SET username = '$username', useremail = '$useremail', userpass = '$userpass' WHERE useremail = '$originalEmail'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
}

$conn->close();
?>
