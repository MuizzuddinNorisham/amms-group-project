<?php
// Start the session
session_start();

// Database connection parameters
$servername = localhost; // Change if your server is different
$username = login_username; // Your MySQL username
$password = login_password; // Your MySQL password
$dbname = login; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];
    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM login WHERE login_username = ?");
    $stmt->bind_param("s", $login_username);
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($login_password, $user['login_password'])) {
            // Set session variables
            $_SESSION['username'] = $user['login_username'];
            header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
    // Close the statement
    $stmt->close();
}
// Close the connection
$conn->close();
?>
