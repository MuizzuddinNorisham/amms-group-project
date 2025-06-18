    <?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
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
        echo "<script>alert('Thank you for your feedback!'); window.location.href='feedback-customer.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 4rem;
            max-width: 600px;
            margin: auto;
            background: #f9fafb;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 1rem;
            color: #333;
        }
        input, textarea, select {
            width: 100%;
            padding: 1rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            margin-top: 1.5rem;
            padding: 1rem 2rem;
            background: #e84393;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.6rem;
            cursor: pointer;
        }
        button:hover {
            background: #d63384;
        }
    </style>
</head>
<body>
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
</body>
</html>