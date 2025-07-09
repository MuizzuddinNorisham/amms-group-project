<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php");
    exit();
}

// Assume total amount comes from cart or previous step
if (!isset($_SESSION['total_amount'])) {
    // Optionally fetch from DB or redirect back
    $_SESSION['total_amount'] = 0; // fallback
}

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $dbc->real_escape_string($_POST['payment_method']);
    $account_number = $dbc->real_escape_string($_POST['accountNumber']);
    // $password = $dbc->real_escape_string($_POST['password']); // Not used for now unless needed for validation

    $cust_id = $_SESSION['cust_id'];
    $payment_amount = $_SESSION['total_amount'];

    // Generate receipt ID (simple example)
    $receipt_id = "RCPT-" . date("Ymd") . "-" . rand(1000, 9999);

    // Insert into payment table
    $stmt = $dbc->prepare("
        INSERT INTO payment (payment_method, payment_amount, account_no, cust_id, receipt_id)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sdsss",
        $payment_method,
        $payment_amount,
        $account_number,
        $cust_id,
        $receipt_id
    );

    if ($stmt->execute()) {
        // Save receipt ID in session for next page
        $_SESSION['receipt_id'] = $receipt_id;

        // Redirect to confirmation page
        header("Location: payment-success.php");
        exit();
    } else {
        echo "<script>alert('Error processing payment. Please try again.');</script>";
    }

    $stmt->close();
}
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 12px;
            background-color: rgb(97, 101, 181);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #505a9a;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enter Payment Details</h2>
    <form action="payment-info.php" method="POST">
        <input type="hidden" id="paymentMethod" name="payment_method" value="" required>

        <input type="text" name="accountNumber" placeholder="Account Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="hidden" id="paymentMethod" name="payment_method" value="" required>

        <button type="submit">Submit Payment</button>
    </form>
</div>

<script>
    // Retrieve payment method from previous page
    const paymentMethod = sessionStorage.getItem("paymentMethod");
    if (!paymentMethod) {
        alert("No payment method selected. Redirecting...");
        window.location.href = "payment-customer.php";
    } else {
        document.getElementById("paymentMethod").value = paymentMethod;
    }
</script>

</body>
</html>