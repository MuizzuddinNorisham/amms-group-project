<?php
session_start();
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
    <link rel="stylesheet" href="payment.css">
    <style>
        .inputFields {
            display: none; /* Initially hidden */
            margin-top: 20px;
        }

        .inputFields input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .confirmButton {
            width: 100%;
            padding: 10px;
            background-color: rgb(97, 101, 181);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
        }

        .confirmButton:hover {
            background-color: #505a9a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h4>Select a <span style="color:rgb(97, 101, 181)">Payment</span></h4>
        </div>

        <form id="paymentForm" action="#" method="POST">
            <input type="radio" name="payment" id="visa" value="visa">
            <input type="radio" name="payment" id="mastercard" value="mastercard">

            <div class="category">
                <label for="visa" class="visaMethod">
                    <div class="imgName">
                        <div class="imgContainer visa">
                            <img src="image.visa.png" alt="">
                        </div>
                        <span class="name">Visa</span>
                    </div>
                    <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
                </label>

                <label for="mastercard" class="mastercardMethod">
                    <div class="imgName">
                        <div class="imgContainer">
                            <img src="image.mastercard.png" alt="">
                        </div>
                        <span class="name">MasterCard</span>
                    </div>
                    <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
                </label>
            </div>

            <!-- Changed Inputs -->
            <div class="inputFields" id="inputFields">
                <input type="text" id="accountNumber" name="accountNumber" placeholder="Enter Account Number" required>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
            </div>

            <button type="button" class="confirmButton" onclick="submitPayment()">Confirm Payment Method</button>
        </form>
    </div>

    <script>
        function submitPayment() {
            const paymentOption = document.querySelector('input[name="payment"]:checked');

            if (!paymentOption) {
                alert("Please select a payment method.");
                return;
            }

            // Show inputs (if needed)
            document.getElementById('inputFields').style.display = 'block';

            // Simulate successful payment and redirect
            setTimeout(() => {
                alert("Payment confirmed!");
                window.location.href = 'payment-information.php'; // Redirect after confirmation
            }, 500);
        }
    </script>
</body>
</html>