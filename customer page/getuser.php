<?php
$dbc = new mysqli("localhost", "root", "", "acrylic");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$result = $dbc->query("SELECT * FROM user");
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);
?>
