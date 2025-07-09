<?php
// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $dbc->query("DELETE FROM product WHERE product_id = $id");
    header("Location: dashboard-product-staff.php");
    exit();
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_product'])) {
    $name = $_POST['product_name'] ?? '';
    $price = $_POST['product_price'] ?? '';
    $quantity = $_POST['product_quantity'] ?? '';
    $type = $_POST['product_type'] ?? '';
    $design = $_POST['product_design'] ?? '';
    $font = $_POST['product_font'] ?? '';

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['product_image']['name']);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath);
        $imagePath = $targetFilePath;
    }

    if ($name && $price && $quantity && $type && $design && $font) {
        $stmt = $dbc->prepare("INSERT INTO product (product_name, product_price, product_quantity, product_type, product_design, product_font, product_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdissss", $name, $price, $quantity, $type, $design, $font, $imagePath);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard-product-staff.php");
        exit();
    } else {
        echo "<script>alert('All fields are required.');</script>";
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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 20px;
    }

    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      margin-bottom: 20px;
    }

    input[type="text"], input[type="number"], select, input[type="file"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 12px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    input[type="submit"], button {
      padding: 10px 20px;
      background-color: #3cc;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #3cc;
      color: white;
    }

    .thumbnail {
      width: 80px;
      height: auto;
    }

    #modal {
      position: fixed;
      top: 10%;
      left: 30%;
      background: white;
      padding: 20px;
      border: 1px solid #ccc;
      z-index: 1000;
      display: none;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Product Management</h1>

    <!-- Search bar -->
    <input type="text" id="searchInput" placeholder="Search by product name..." onkeyup="filterTable()" />
    <button onclick="document.getElementById('modal').style.display='block'">Add Product</button>

    <table id="productTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Type</th>
          <th>Design</th>
          <th>Font</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $products->fetch_assoc()): ?>
        <tr>
          <td><?= $row['product_id'] ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= number_format($row['product_price'], 2) ?></td>
          <td><?= $row['product_quantity'] ?></td>
          <td><?= $row['product_type'] ?></td>
          <td><?= $row['product_design'] ?></td>
          <td><?= $row['product_font'] ?></td>
          <td>
            <?php if ($row['product_image']): ?>
              <img src="<?= $row['product_image'] ?>" class="thumbnail">
            <?php else: ?>
              No image
            <?php endif; ?>
          </td>
          <td>
            <a href="edit-product.php?id=<?= $row['product_id'] ?>">Edit</a>
            <a href="?delete=<?= $row['product_id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal Form -->
  <div id="modal">
    <form method="POST" enctype="multipart/form-data">
      <h2>Add Product</h2>
      <input type="hidden" name="add_product" value="1">
      <label>Name</label><input type="text" name="product_name" required>
      <label>Price</label><input type="number" step="0.01" name="product_price" required>
      <label>Quantity</label><input type="number" name="product_quantity" required>
      <label>Type</label><input type="text" name="product_type" required>
      <label>Design</label><input type="text" name="product_design" required>
      <label>Font</label><input type="text" name="product_font" required>
      <label>Image</label><input type="file" name="product_image">
      <br><br>
      <input type="submit" value="Add">
      <button type="button" onclick="document.getElementById('modal').style.display='none'">Cancel</button>
    </form>
  </div>

  <script>
    function filterTable() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toLowerCase();
      const rows = document.querySelectorAll('#productTable tbody tr');

      rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        row.style.display = name.includes(filter) ? '' : 'none';
      });
    }
  </script>
</body>
</html>
