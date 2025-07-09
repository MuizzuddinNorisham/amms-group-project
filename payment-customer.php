<?php
session_start();

if (!isset($_SESSION['cust_id'])) {
    header("Location: login-customer.php");
    exit();
}

$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Fetch total cart amount
$cust_id = $_SESSION['cust_id'];
$total_amount = 0;
$sql = "SELECT SUM(cart_total) AS total FROM cart WHERE cust_id = ? AND cart_status = 'Pending'";
$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_amount = $row['total'] ?? 0;

$error = "";
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment'])) {
    $payment_method = $dbc->real_escape_string($_POST['payment_method']);
    $account_number = $dbc->real_escape_string($_POST['accountNumber']);
    $password = $dbc->real_escape_string($_POST['password']);

    if (empty($payment_method) || empty($account_number) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Generate receipt ID
        $receipt_id = "RCPT-" . date("YmdHis") . "-" . rand(1000, 9999);

        // Insert new payment record
        $insert_sql = "INSERT INTO payment (payment_method, payment_amount, account_no, cust_id, receipt_id)
                       VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $dbc->prepare($insert_sql);
        $insert_stmt->bind_param("sdsss", $payment_method, $total_amount, $account_number, $cust_id, $receipt_id);

        if ($insert_stmt->execute()) {
            // Update cart status to paid
            $dbc->query("UPDATE cart SET cart_status = 'Paid' WHERE cust_id = $cust_id AND cart_status = 'Pending'");
            
            // ✅ Redirect after success
            $_SESSION['payment_success'] = "Payment processed successfully!";
            header("Location: dashboard-profile-customer.php");
            exit();
        } else {
            $error = "Error processing payment.";
        }

        $insert_stmt->close();
    }
}
    

$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css "
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Payment Method</title>
    <style>
        /* Your existing styles remain unchanged */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .title h4 {
            text-align: center;
            color: #333;
        }

        .title span {
            color: rgb(97, 101, 181);
        }

        .category {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 10px;
            transition: border-color 0.3s;
        }

        label:hover {
            border-color: rgb(97, 101, 181);
        }

        label input[type="radio"] {
            display: none;
        }

        .imgContainer img {
            width: 60px;
        }

        .name {
            margin-top: 10px;
            font-weight: bold;
        }

        .check {
            display: none;
            margin-top: 10px;
        }

        input[type="radio"]:checked + .visaMethod .check,
        input[type="radio"]:checked + .mastercardMethod .check {
            display: inline-block;
        }

        .confirmButton {
            width: 100%;
            padding: 12px;
            background-color: rgb(97, 101, 181);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 30px;
        }

        .confirmButton:hover {
            background-color: #505a9a;
        }

        .inputFields {
            display: none;
            margin-top: 20px;
        }

        .inputFields input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="title">
        <h4>Select a <span>Payment</span></h4>
    </div>

    <form id="paymentForm" method="post">
        <input type="hidden" name="payment_method" id="paymentMethodField" value="">

        <div class="category">
            <label onclick="selectPayment('visa')">
                <input type="radio" name="payment" value="visa">
                <div class="imgName">
                    <div class="imgContainer visa">
                        <img src="image.visa.png" alt="">
                    </div>
                    <span class="name">Visa</span>
                </div>
                <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
            </label>

            <label onclick="selectPayment('mastercard')">
                <input type="radio" name="payment" value="mastercard">
                <div class="imgName">
                    <div class="imgContainer">
                        <img src="image.mastercard.png" alt="">
                    </div>
                    <span class="name">MasterCard</span>
                </div>
                <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
            </label>
        </div>

        <button type="button" class="confirmButton" onclick="showPaymentForm()">Confirm Payment Method</button>

        <!-- Hidden Form -->
        <div class="inputFields" id="inputFields">
            <input type="text" name="accountNumber" placeholder="Enter Bank Account Number" required>
            <input type="password" name="password" placeholder="Enter Customer Password" required>
            <button type="submit" name="submit_payment" class="confirmButton">Submit Payment</button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php endif; ?>
</div>

<script>
    let selectedPaymentMethod = "";

    function selectPayment(method) {
        selectedPaymentMethod = method;
    }

    function showPaymentForm() {
        if (!selectedPaymentMethod) {
            alert("Please select a payment method.");
            return;
        }

        document.getElementById("paymentMethodField").value = selectedPaymentMethod;
        document.getElementById("inputFields").style.display = "block";
    }
</script>

</body>
</html>