<?php
// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $stmt = $dbc->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Product deleted successfully.'); window.location.href='dashboard-product-staff.php';</script>";
    exit();
}

// Handle insert/update
$product_image_path = '';
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
    $uploadDir = 'asset/';
    $filename = basename($_FILES['product_image']['name']);
    $targetPath = $uploadDir . time() . '_' . $filename;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetPath)) {
        $product_image_path = $targetPath;
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_name'])) {
    $id = $_POST['product_id'] ?? '';
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $quantity = $_POST['product_quantity'];
    $type = $_POST['product_type'];
    $design = $_POST['product_design'];
    $font = $_POST['product_font'];

    if ($id) {
        if (!empty($product_image_path)) {
            $stmt = $dbc->prepare("UPDATE product SET product_name=?, product_price=?, product_quantity=?, product_type=?, product_design=?, product_font=?, product_image=? WHERE product_id=?");
            $stmt->bind_param("sdissssi", $name, $price, $quantity, $type, $design, $font, $product_image_path, $id);
        } else {
            $stmt = $dbc->prepare("UPDATE product SET product_name=?, product_price=?, product_quantity=?, product_type=?, product_design=?, product_font=? WHERE product_id=?");
            $stmt->bind_param("sdisssi", $name, $price, $quantity, $type, $design, $font, $id);
        }
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Product updated successfully.'); window.location.href='dashboard-product-staff.php';</script>";
        exit();
    } else {
        $stmt = $dbc->prepare("INSERT INTO product (product_name, product_price, product_quantity, product_type, product_design, product_font, product_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdissss", $name, $price, $quantity, $type, $design, $font, $product_image_path);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Product added successfully.'); window.location.href='dashboard-product-staff.php';</script>";
        exit();
    }
}

// Fetch products
$products = $dbc->query("SELECT * FROM product");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Product Management</title>
  <link rel="stylesheet" href="dashboard-staff.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f0f0f0;
      display: flex;
    }

    .main-wrapper {
      display: flex;
      width: 100%;
    }

    /* Main content */
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
    }

    .add-product {
      padding: 10px 20px;
      background-color: #3cc;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      margin-left: 10px;
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

    .edit-btn, .delete-btn {
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
    }

    .edit-btn {
      background-color: green;
    }

    .delete-btn {
      background-color: red;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0,0,0,0.4);
      overflow-y: auto;
    }

    .modal-content {
      background: #fff;
      margin: 50px auto;
      padding: 30px;
      width: 90%;
      max-width: 500px;
      border-radius: 10px;
      position: relative;
      box-sizing: border-box;
    }

    .close {
      position: absolute;
      right: 20px;
      top: 15px;
      font-size: 28px;
      cursor: pointer;
      color: #aaa;
    }

    .modal-content input,
    .modal-content select {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .modal-content input[type="submit"],
    .modal-content input[type="reset"] {
      width: 48%;
      background-color: #3cc;
      color: white;
      border: none;
      margin-top: 15px;
      cursor: pointer;
    }

    .modal-content input[type="reset"] {
      background-color: #888;
    }
  </style>
</head>
<body>

    <!-- Sidebar -->
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
            <a href="dashboard-profile-staff.php" class="active">
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
                    <a href="dashboard-order-staff.php">
                        <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                        <span class="text">Order</span>
                    </a>
                </li>
        <li>
    <a href="login-administrator.php" class="logout" onclick="confirmLogout(event)">
        <span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span>
        <span class="text">Log out</span>
    </a>
</li>
    </ul>
</div>

  <!-- Content -->
  <div class="content-wrapper">
    <div class="container">
      <div class="header">
        <h3>Product Management</h3>
        <div class="search-container">
          <input type="text" placeholder="Search">
          <button class="add-product">Add Product</button>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Quantity/Pack</th><th>Type</th><th>Design</th><th>Font</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $products->fetch_assoc()): ?>
          <tr data-product='<?= json_encode($row) ?>'>
            <td><?= $row['product_id'] ?></td>
            <td><?= $row['product_name'] ?></td>
            <td><?= number_format($row['product_price'], 2) ?></td>
            <td><?= $row['product_quantity'] ?></td>
            <td><?= $row['product_type'] ?></td>
            <td><?= $row['product_design'] ?></td>
            <td><?= $row['product_font'] ?></td>
            <td>
              <button class="edit-btn">Edit</button>
              <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                <input type="hidden" name="delete_id" value="<?= $row['product_id'] ?>">
                <button type="submit" class="delete-btn">Delete</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <form method="POST" enctype="multipart/form-data">
      <h2>Product</h2>
      <input type="hidden" name="product_id" id="edit-id">

      <label>Upload Image</label>
      <input type="file" name="product_image" accept="image/*" required>

      <label>Name</label>
      <input type="text" name="product_name" required>

      <label>Price (RM)</label>
      <input type="number" step="0.01" name="product_price" required>

      <label>Quantity</label>
      <input type="number" name="product_quantity" required>

      <label>Type</label>
      <select name="product_type" required>
        <option value="">--Select--</option>
        <option value="Acrylic tag">Acrylic tag</option>
        <option value="Label">Label</option>
        <option value="Pouch bag">Pouch bag</option>
        <option value="Card">Card</option>
      </select>

      <label>Design</label>
      <input type="text" name="product_design" required>

      <label>Font</label>
      <input type="text" name="product_font" required>

      <div style="display: flex; justify-content: space-between;">
        <input type="submit" value="Save">
        <input type="reset" value="Reset">
      </div>
    </form>
  </div>
</div>


<!-- Script -->
<script>
  const modal = document.getElementById("productModal");
  const addBtn = document.querySelector(".add-product");
  const closeBtn = document.querySelector(".close");
  const form = modal.querySelector("form");

  addBtn.onclick = () => {
    form.reset();
    form.product_id.value = "";
    modal.style.display = "block";
  };

  closeBtn.onclick = () => modal.style.display = "none";
  window.onclick = e => { if (e.target == modal) modal.style.display = "none"; };

  document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.onclick = function () {
      const row = this.closest("tr");
      const data = JSON.parse(row.dataset.product);
      form.product_id.value = data.product_id;
      form.product_name.value = data.product_name;
      form.product_price.value = data.product_price;
      form.product_quantity.value = data.product_quantity;
      form.product_type.value = data.product_type;
      form.product_design.value = data.product_design;
      form.product_font.value = data.product_font;
      modal.style.display = "block";
    };
  });

  // Functional search bar
  const searchInput = document.querySelector('.search-container input[type="text"]');
  searchInput.addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? "" : "none";
    });
  });

  function confirmLogout(e) {
        e.preventDefault(); // Stop the link from navigating immediately

        // Show confirmation dialog
        const isConfirmed = confirm("Are you sure you want to log out?");

        if (isConfirmed) {
            alert("You have been logged out successfully.");
            window.location.href = "login-administrator.php"; // Redirect to login page
        }
    }
</script>
</body>
</html>