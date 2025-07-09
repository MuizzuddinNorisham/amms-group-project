<?php
// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");
if ($dbc->connect_error) {
  die("Connection failed: " . $dbc->connect_error);
}

// Handle deletion
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $dbc->query("DELETE FROM product WHERE product_id = $id");
  header("Location: dashboard-product-staff.php");
  exit();
}

// Handle insert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsubmit'])) {
  $name = $_POST['product_name'];
  $price = $_POST['product_price'];
  $qty = $_POST['product_quantity'];
  $type = $_POST['product_type'];
  $design = $_POST['product_design'];
  $font = $_POST['product_font'];

  if (!empty($name) && !empty($price) && !empty($qty) && !empty($type) && !empty($design) && !empty($font)) {
    $stmt = $dbc->prepare("INSERT INTO product (product_name, product_price, product_quantity, product_type, product_design, product_font) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $name, $price, $qty, $type, $design, $font);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard-product-staff.php");
    exit();
  }
}

$products = $dbc->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Product Management</title>
  <link rel="stylesheet" href="dashboard-staff.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

<div class="sidebar">
  <!-- Sidebar content here -->
   <div class="sidebar">
  <ul>
    <li><a href="#" class="logo"><span class="icon"><i class="fa-solid fa-users"></i></span><span class="text">Staff</span></a></li>
    <li><a href="#"><span class="icon"><i class="fa-solid fa-table-columns"></i></span><span class="text">Dashboard</span></a></li>
    <li><a href="#"><span class="icon"><i class="fas fa-user"></i></span><span class="text">Profile</span></a></li>
    <li><a href="#"><span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span><span class="text">Products</span></a></li>
    <li><a href="login-administrator.php" class="logout"><span class="icon"><i class="fa-solid fa-circle-arrow-left"></i></span><span class="text">Log out</span></a></li>
  </ul>
</div>
</div>

<div class="content-wrapper">
  <div class="container">
    <div class="header">
      <h1>Product Management</h1>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
        <button onclick="document.getElementById('productModal').style.display='block'" class="add-product">Add Product</button>
      </div>
    </div>

    <table id="productTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price (RM)</th>
          <th>Quantity</th>
          <th>Type</th>
          <th>Design</th>
          <th>Font</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $products->fetch_assoc()): ?>
        <tr>
          <td><?= $row['product_id'] ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= number_format($row['product_price'], 2) ?></td>
          <td><?= $row['product_quantity'] ?></td>
          <td><?= htmlspecialchars($row['product_type']) ?></td>
          <td><?= htmlspecialchars($row['product_design']) ?></td>
          <td><?= htmlspecialchars($row['product_font']) ?></td>
          <td>
            <a href="edit-product.php?id=<?= $row['product_id'] ?>" class="edit-btn">Edit</a>
            <a href="?delete=<?= $row['product_id'] ?>" onclick="return confirm('Delete this product?')" class="delete-btn">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('productModal').style.display='none'">&times;</span>
    <form method="POST" action="">
      <h2>Add Product</h2>
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
        <input type="submit" name="btnsubmit" value="Submit">
        <input type="reset" value="Reset">
      </div>
    </form>
  </div>
</div>

<script>
function searchTable() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const rows = document.querySelectorAll("#productTable tbody tr");
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(input) ? "" : "none";
  });
}
</script>

</body>
</html>
