<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "acrylic");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into feedback table
    $stmt = $conn->prepare("INSERT INTO feedback (feedback_name, feedback_rate, feedback_details) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $rating, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='dashboard-feedback-customer.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}

// Fetch all feedbacks from the database
$conn = new mysqli("localhost", "root", "", "acrylic");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT feedback_id, feedback_name, feedback_rate, feedback_details FROM feedback";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- External CSS -->
    <link rel="stylesheet" href="dashboard-customer.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <li>
            <a href="#" class="logo">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <span class="text">Customer</span>
            </a>
        </li>
        <li>
            <a href="dashboard-customer.php">
                <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="dashboard-profile-customer.php">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="dashboard-product-customer.php">
                <span class="icon"><i class="fa-solid fa-bag-shopping"></i></span>
                <span class="text">Product</span>
            </a>
        </li>
        <li>
            <a href="dashboard-cart-customer.php">
                <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="text">Cart</span>
            </a>
        </li>
        <li>
            <a href="dashboard-feedback-customer.php" class="active">
                <span class="icon"><i class="fa-solid fa-comments"></i></span>
                <span class="text">Feedback</span>
            </a>
        </li>
        <li>
            <a href="login-customer.php" class="logout">
                <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
                <span class="text">Log out</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h1 class="page-title">Feedback</h1>

    <!-- Feedback Form -->
    <h2>Leave Your Feedback</h2>
    <form action="" method="POST">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>

        <label for="rating">Rating</label>
        <select id="rating" name="rating" required>
            <option value="">-- Select Rating --</option>
            <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
            <option value="4">⭐⭐⭐⭐ - Good</option>
            <option value="3">⭐⭐⭐ - Average</option>
            <option value="2">⭐⭐ - Poor</option>
            <option value="1">⭐ - Very Poor</option>
        </select>

        <label for="message">Your Feedback</label>
        <textarea id="message" name="message" rows="5" placeholder="Write your feedback here..." required></textarea>

        <button type="submit" class="btn">Submit Feedback</button>
    </form>

    <!-- Feedback Table -->
    <?php if ($result->num_rows > 0): ?>
        <h2>Previous Feedback</h2>
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['feedback_name']) ?></td>
                        <td>
                            <?php
                            $stars = '';
                            for ($i = 0; $i < $row['feedback_rate']; $i++) {
                                $stars .= '⭐';
                            }
                            echo "<span class='rating-star'>$stars</span>";
                            ?>
                        </td>
                        <td><?= nl2br(htmlspecialchars($row['feedback_details'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No feedback has been submitted yet.</p>
    <?php endif; ?>
</div>

</body>
</html>