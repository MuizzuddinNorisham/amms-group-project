<?php
session_start();

// Get payment method from session storage via JS
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
    <form action="process-payment.php" method="POST">
        <input type="hidden" id="paymentMethod" name="payment_method" value="" required>

        <input type="text" name="accountNumber" placeholder="Account Number" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Submit Payment</button>
    </form>
</div>

<script>
    // Retrieve payment method from previous page
    const paymentMethod = sessionStorage.getItem("paymentMethod");
    if (!paymentMethod) {
        alert("No payment method selected. Redirecting...");
        window.location.href = "payment-method.php";
    } else {
        document.getElementById("paymentMethod").value = paymentMethod;
    }
</script>

</body>
</html>