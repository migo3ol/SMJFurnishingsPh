<?php
include 'database.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

// Fetch total sales and number of orders for the current month for the logged-in user
$currentMonth = date('m');
$currentYear = date('Y');

$query = "SELECT SUM(total_amount) AS total_sales, COUNT(*) AS total_orders 
          FROM sales_records 
          WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $userId, $currentMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$totalSales = $data['total_sales'] ?? 0;
$totalOrders = $data['total_orders'] ?? 0;

$stmt->close();

// Fetch monthly sales data for the current year for the logged-in user
$query = "SELECT MONTH(date) AS month, SUM(total_amount) AS total_sales 
          FROM sales_records 
          WHERE user_id = ? AND YEAR(date) = ? 
          GROUP BY MONTH(date) 
          ORDER BY MONTH(date)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userId, $currentYear);
$stmt->execute();
$result = $stmt->get_result();

$monthlySales = [];
while ($row = $result->fetch_assoc()) {
    $monthlySales[(int)$row['month']] = $row['total_sales'];
}
$stmt->close();

// Fill in missing months with 0 sales
for ($i = 1; $i <= 12; $i++) {
    if (!isset($monthlySales[$i])) {
        $monthlySales[$i] = 0;
    }
}
ksort($monthlySales); // Sort by month

// Fetch top sales records for the logged-in user
$query = "SELECT project_name, total_amount, date 
          FROM sales_records 
          WHERE user_id = ? 
          ORDER BY total_amount DESC 
          LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$topSales = [];
while ($row = $result->fetch_assoc()) {
    $topSales[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            margin-top: 50px;
        }
        .table-container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-9 ms-auto">
            <h1 class="mb-5 fw-bold">Admin Dashboard</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales for the Month</h5>
                            <p class="card-text fs-3">₱<?= number_format($totalSales, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders for the Month</h5>
                            <p class="card-text fs-3"><?= $totalOrders ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <!-- Line Graph -->
                <div class="col-md-8">
                    <div class="chart-container">
                        <h3 class="text-center">Monthly Sales Progress</h3>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Top Sales Table -->
                <div class="col-md-4">
                    <div class="table-container">
                        <h3 class="text-center">Top Sales</h3>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Project Name</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topSales as $sale): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sale['project_name']) ?></td>
                                        <td>₱<?= number_format($sale['total_amount'], 2) ?></td>
                                        <td><?= date('F j, Y', strtotime($sale['date'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare data for the chart
        const monthlySales = <?= json_encode(array_values($monthlySales)) ?>;
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Create the line chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Sales (₱)',
                    data: monthlySales,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>