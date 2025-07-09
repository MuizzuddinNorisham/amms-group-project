<?php
session_start();

// Authentication check
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'staff') {
    header("Location: login-administrator.php");
    exit();
}

// Database connection
$dbc = new mysqli("localhost", "root", "", "acrylic");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Fetch sales data
$sql = "
    SELECT p.product_name, 
           SUM(c.cart_quantity) AS total_quantity, 
           SUM(c.cart_total) AS total_sales
    FROM cart c
    JOIN product p ON c.product_id = p.product_id
    WHERE c.cart_status = 'paid'
    GROUP BY p.product_id
";

$result = $dbc->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$json_data = json_encode($data);
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Sales Dashboard</title>
    <link rel="stylesheet" href="dashboard-staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css "
        crossorigin="anonymous" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .charts-container {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .chart-container {
            max-width: 500px;
            width: 100%;
            height: 300px;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .no-data {
            text-align: center;
            color: red;
            margin-top: 20px;
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
            <a href="dashboard-order-staff.php" class="active">
                <span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="text">Order</span>
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
<div class="content">
    <h2>Product Sales Overview</h2>

    <?php if (count($data) > 0): ?>
        <div class="charts-container">
            <!-- Bar Chart -->
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>

            <!-- Pie Chart -->
            <div class="chart-container">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    <?php else: ?>
        <p class="no-data">No sales data available.</p>
    <?php endif; ?>
</div>

<script>
    const jsonData = <?= $json_data ?>;

    const labels = jsonData.map(item => item.product_name);
    const quantities = jsonData.map(item => parseInt(item.total_quantity));
    const sales = jsonData.map(item => parseFloat(item.total_sales));

    if (labels.length > 0) {
        // BAR CHART - Units Sold & Total Sales
        const ctxBar = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Units Sold',
                        data: quantities,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    },
                    {
                        label: 'Total Sales (RM)',
                        data: sales,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Sales by Product' }
                }
            }
        });

        // PIE CHART - Quantity Distribution
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Distribution',
                    data: quantities,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Sales Distribution by Product' }
                }
            }
        });
    }
</script>

</body>
</html>