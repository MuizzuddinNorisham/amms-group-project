<?php
// Connect to database
$dbc = new mysqli("localhost", "root", "", "acrylic");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['product_name'] ?? '';
    $price = $_POST['product_price'] ?? '';
    $quantity = $_POST['product_quantity'] ?? '';
    $type = $_POST['product_type'] ?? '';
    $design = $_POST['product_design'] ?? '';
    $font = $_POST['product_font'] ?? '';

    if ($name && $price && $quantity && $type && $design && $font) {
        $stmt = $dbc->prepare("INSERT INTO product (product_name, product_price, product_quantity, product_type, product_design, product_font) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisss", $name, $price, $quantity, $type, $design, $font);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard-product-staff.php");
        exit();
    } else {
        echo "<script>alert('Please fill all fields.');</script>";
    }
}

// Fetch products
$products = $dbc->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <link rel="stylesheet" href="dashboard-staff.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
            background-color: #f0f0f0;
        }

        .content-wrapper {
            margin-left: 200px;
            padding: 20px;
            flex: 1;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .add-product {
            padding: 10px 20px;
            background-color: #3cc;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #3cc;
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background: #fff;
            margin: 80px auto;
            padding: 30px;
            width: 500px;
            border-radius: 10px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        .modal-content input, .modal-content select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .modal-content input[type="submit"],
        .modal-content input[type="reset"] {
            width: 48%;
            margin-top: 15px;
            background-color: #3cc;
            color: white;
            border: none;
            cursor: pointer;
        }

        .modal-content input[type="reset"] {
            background-color: #888;
        }

        .modal-content input:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <li><a href="#" class="logo"><span class="icon"><i class="fa-solid fa-users"></i></span><span class="text">Staff</span></a></li>
        <li><a href="#"><span class="icon"><i class="fa-solid fa-table-columns"></i></span><span class="text">Dashboard</span></a></li>
        <li><a href="#"><span class="icon"><i class="fas fa-user"></i></span><span class="text">Profile</span></a></li>
        <li><a href="#"><span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span><span class="text">Products</span></a></li>
        <li><a href="login-administrator.php" class="logout"><span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span><span class="text">Log out</span></a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content-wrapper">
    <div class="container">
        <div class="header">
            <h1>Product Management</h1>
            <div class="search-container">
                <input type="text" placeholder="Search" />
                <button class="add-product">Add Product</button>
            </div>
        </div>

        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price (RM)</th>
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Design</th>
                    <th>Font</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['product_id'] ?></td>
                    <td><?= $row['product_name'] ?></td>
                    <td><?= number_format($row['product_price'], 2) ?></td>
                    <td><?= $row['product_quantity'] ?></td>
                    <td><?= $row['product_type'] ?></td>
                    <td><?= $row['product_design'] ?></td>
                    <td><?= $row['product_font'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form method="POST" action="">
            <h2 style="text-align:center;">Add Product</h2>
            <label>Product Name</label>
            <input type="text" name="product_name" required>

            <label>Product Price (RM)</label>
            <input type="number" step="0.01" min="0.01" name="product_price" required>

            <label>Product Quantity</label>
            <input type="number" min="1" name="product_quantity" required>

            <label>Product Type</label>
            <select name="product_type" required>
                <option value="">-- Select Type --</option>
                <option value="Acrylic tag">Acrylic tag</option>
                <option value="Label">Label</option>
                <option value="Pouch bag">Pouch bag</option>
                <option value="Card">Card</option>
            </select>

            <label>Product Design</label>
            <input type="text" name="product_design" required>

            <label>Product Font</label>
            <input type="text" name="product_font" required>

            <div style="display: flex; justify-content: space-between;">
                <input type="submit" value="Submit">
                <input type="reset" value="Reset">
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script>
  const modal = document.getElementById("productModal");
  const btn = document.querySelector(".add-product");
  const span = document.querySelector(".close");

  btn.onclick = () => modal.style.display = "block";
  span.onclick = () => modal.style.display = "none";
  window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; };
</script>

</body>
</html>
