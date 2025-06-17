<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Connect to the database
$host = 'localhost';
$dbname = 'acrylic';
$username = 'root'; // Change if needed
$password = '';     // Change if needed

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Get the raw POST data and decode JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'User email not provided.']);
    exit;
}

$useremail = $conn->real_escape_string($data['email']);

// Delete the user
$sql = "DELETE FROM users WHERE useremail = '$useremail'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
}

$conn->close();
?>
