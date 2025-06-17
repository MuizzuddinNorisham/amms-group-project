<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $useremail = $_POST['useremail'] ?? '';
    $userpass = $_POST['userpass'] ?? '';

    if ($username && $useremail && $userpass) {
        $entry = "Username: $username, Email: $useremail, Password: $userpass\n";
        file_put_contents("users.txt", $entry, FILE_APPEND);
        echo "User successfully added!";
    } else {
        echo "Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
