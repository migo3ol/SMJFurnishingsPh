<?php
// sales.php - Display all sales records
include 'database.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

// Get the selected month from the query parameter or use the current month
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records | SMJ Furnishings</title>
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
        .table {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-9 ms-auto">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fw-bold">Sales Records</h1>
                <button class="btn btn-success" onclick="window.location.href='add_sales.php'">Add Sales</button>
            </div>
            <form method="GET" action="sales.php" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <input type="month" id="month" name="month" class="form-control" value="<?= $selected_month ?>">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order No.</th>
                        <th>PO No</th>
                        <th>Client Name</th>
                        <th>Project Name</th>
                        <th>Product Classification</th>
                        <th>Area</th>
                        <th>Total Amount</th>
                        <th>OR No.</th>
                        <th>DR No.</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Fetch sales records for the logged-in user and selected month
                $stmt = $conn->prepare("SELECT * FROM sales_records WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?");
                $stmt->bind_param("is", $userId, $selected_month);
                $stmt->execute();
                $result = $stmt->get_result();

                $totalAmount = 0;
                while ($row = $result->fetch_assoc()):
                    if ($row['status'] !== 'Pending' && $row['status'] !== 'Cancelled') {
                        $totalAmount += $row['total_amount'];
                    }
                ?>
                    <tr>
                        <td><?= $row['order_no'] ?></td>
                        <td><?= $row['po_no'] ?></td>
                        <td><?= $row['client_name'] ?></td>
                        <td><?= $row['project_name'] ?></td>
                        <td><?= $row['product_specification'] ?></td>
                        <td><?= $row['area'] ?></td>
                        <td>₱<?= number_format($row['total_amount'], 2) ?></td>
                        <td><?= $row['or_no'] ?></td>
                        <td><?= $row['dr_no'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <a href="edit_sales.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="delete_sales.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Total Sales:</th>
                        <th colspan="5">₱<?= number_format($totalAmount, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>