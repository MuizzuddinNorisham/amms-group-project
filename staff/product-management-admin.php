<?php
if (isset($_POST['btnsubmit'])) {
    // Capture form inputs
    $pname = $_POST['product_name'];
    $pprice = $_POST['product_price'];
    $pquantity = $_POST['product_quantity'];
    $ptype = $_POST['product_type'];
    $pdesign = $_POST['product_design'];
    $font = $_POST['product_font'];

    // Validate required fields
    if (empty($pname) || empty($pprice) || empty($pquantity) ||
        empty($ptype) || empty($pdesign) || empty($font)) {
        echo '<script>alert("All fields are required.");</script>';
        echo '<script>window.location.assign("product.php");</script>';
        exit();
    }

    // Connect to database
    $dbc = new mysqli("localhost", "root", "", "acrylic");

    // Check connection
    if ($dbc->connect_error) {
        die("Connection failed: " . $dbc->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $dbc->prepare("INSERT INTO product (product_name, product_price, product_quantity, product_type, product_design, product_font) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sdssss", $pname, $pprice, $pquantity, $ptype, $pdesign, $font);

    if ($stmt->execute()) {
        echo '<script>alert("Record Has Been Added");</script>';
        echo '<script>window.location.assign("product.php");</script>';
    } else {
        echo '<script>alert("Data Is Invalid, No Record Has Been Added");</script>';
        echo '<script>window.location.assign("product.php");</script>';
    }

    $stmt->close();
    $dbc->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="staff.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Staff</title>
    </head>
    <body>
        <!--sidebar section start-->
        
        <div class="sidebar" >
            <ul>
                <li>
                    <a href="#" class="logo">
                        <span class="icon"><i class="fa-solid fa-users"></i></i></span>
                        <span class="text">Hello, Staff</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadContent('profile.html')">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="text">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="staff-product.html" onclick="loadContent('staff-product.html')">
                        <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                        <span class="text">Products</span>
                    </a>
                </li>
                 <li>
                   <a href="#" onclick="loadContent('order.html')">
                        <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                        <span class="text">Order</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></i></span>
                        <span class="text">Log out</span>
                    </a>
                </li>
            </ul>  
        </div>

        <!--sidebar section ends-->

        <!--update products section starts-->
        
        <div class="main-content" id="main-content">
            <form method="POST" action="">
                <h2 align="center">Product Registration</h2>
                <table border="1" align="center">
                    <tr>
                        <td>Product Name</td>
                        <td><input type="text" name="product_name" size="50" required /></td>
                    </tr>
                    <tr>
                        <td>Product Price (RM)</td>
                        <td><input type="number" name="product_price" step="0.01" min="0.01" required /></td>
                    </tr>
                    <tr>
                        <td>Product Quantity</td>
                        <td><input type="number" name="product_quantity" min="1" required /></td>
                    </tr>
                    <tr>
                        <td>Product Type</td>
                        <td>
                            <select name="product_type" required>
                                <option value="">-- Select Type --</option>
                                <option value="T-shirt">Acrylic tag</option>
                                <option value="Mug">Label</option>
                                <option value="Sticker">Pouch bag</option>
                                <option value="Other">Card</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Product Design</td>
                        <td><input type="text" name="product_design" required /></td>
                    </tr>
                    <tr>
                        <td>Product Font</td>
                        <td><input type="text" name="product_font" required /></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" name="btnsubmit" value="Submit" />
                            <input type="reset" value="Reset" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>


        <!--update products section ends-->
      

    </body>
</html>