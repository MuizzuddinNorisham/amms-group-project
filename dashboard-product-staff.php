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
        echo '<script>window.location.assign("dashboard-product-staff.php");</script>';
    } else {
        echo '<script>alert("Data Is Invalid, No Record Has Been Added");</script>';
        echo '<script>window.location.assign("dashboard-product-staff.php");</script>';
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
    <link rel="stylesheet" href="dashboard-staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css " 
          integrity="sha512-pV1uV+g3w7iXLZFXgPPBPcvBnkbIq3JhXtWq9QKfvU5yLLjEa4LkZ3DsGqGUVfYzWmEYwTBrWLTIa2MIw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Staff</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 20px auto;
            width: 90%;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar Section -->
<div class="sidebar">
    <ul>
        <li>
            <a href="#" class="logo">
                <span class="icon"><i class="fa-solid fa-users"></i></span>
                <span class="text">Staff</span>
            </a>
        </li>
        <li>
            <a href="dashboard-staff.php">
                <span class="icon"><i class="fa-solid fa-table-columns"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="dashboard-profile-staff.php">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="dashboard-product-staff.php">
                <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                <span class="text">Products</span>
            </a>
        </li>
        <li>
            <a href="login-administrator.php" class="logout">
                <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
                <span class="text">Log out</span>
            </a>
        </li>
    </ul>  
</div>

<!-- Main Content -->
<div class="content" id="product">
    <form method="POST" action="">
        <h2 class="header">Product Registration</h2>
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
                        <option value="Acrylic tag">Acrylic tag</option>
                        <option value="Label">Label</option>
                        <option value="Pouch bag">Pouch bag</option>
                        <option value="Card">Card</option>
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

<!-- Product List Table -->
<div class="content" id="product-list">
    <h2 class="header">Product List</h2>
    <?php
    // Connect to database
    $dbc = new mysqli("localhost", "root", "", "acrylic");

    if ($dbc->connect_error) {
        die("Connection failed: " . $dbc->connect_error);
    }

    // Fetch all products
    $result = $dbc->query("SELECT * FROM product");

    if ($result->num_rows > 0) {
        echo "<table border='1' align='center'>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price (RM)</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Design</th>
                    <th>Font</th>
                </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['product_id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['product_price']}</td>
                    <td>{$row['product_quantity']}</td>
                    <td>{$row['product_type']}</td>
                    <td>{$row['product_design']}</td>
                    <td>{$row['product_font']}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p align='center'>No products found.</p>";
    }

    $dbc->close();
    ?>
</div>

</body>
</html>