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
    $rating = intval($_POST['rating']);
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
        echo "<script>alert('Error submitting feedback. Please try again.'); window.location.href='dashboard-feedback-customer.php';</script>";
    }

    $stmt->close();
    $conn->close();
}

// Fetch all feedbacks from the database
$conn = new mysqli("localhost", "root", "", "acrylic");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT feedback_name, feedback_rate, feedback_details FROM feedback ORDER BY feedback_id DESC";
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

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 220px;
            padding: 2rem;
        }

        h1.page-title {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }

        /* Feedback Form */
        form {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #111827;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.2rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.2rem;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Feedback Table */
        .feedback-table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .feedback-table thead th {
            background-color: #f3f4f6;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #6b7280;
            text-align: left;
            padding: 1rem;
        }

        .feedback-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .feedback-table td {
            padding: 1rem;
            vertical-align: top;
            font-size: 0.95rem;
            color: #1f2937;
        }

        .rating-star {
            font-size: 1.2rem;
        }

        p {
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
        }
    </style>
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
    <a href="#" onclick="confirmLogout(event)" class="logout">
        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
        <span class="text">Log out</span>
    </a>
</li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h1 class="page-title">Customer Feedback</h1>

    <!-- Feedback Form -->
    <h2 style="margin-bottom: 1rem;">Leave Your Feedback</h2>
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

    <!-- Previous Feedback -->
    <?php if ($result && $result->num_rows > 0): ?>
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
                        <td><span class="rating-star"><?= str_repeat('⭐', $row['feedback_rate']) ?></span></td>
                        <td><?= nl2br(htmlspecialchars($row['feedback_details'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No feedback has been submitted yet.</p>
    <?php endif; ?>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault(); // Prevent default link behavior

        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "login-customer.php"; // Redirect to logout page
        }
    }
</script>
</body>
</html>