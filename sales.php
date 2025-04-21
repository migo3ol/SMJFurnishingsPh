<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $selected_month = $_GET['month'] ?? date('Y-m');
    $search_po_no = $_GET['po_no'] ?? '';

    $query = "SELECT * FROM sales_records WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    $params = [$userId, $selected_month];
    $types = "is";

    if (!empty($search_po_no)) {
        $query .= " AND po_no LIKE ?";
        $params[] = '%' . $search_po_no . '%';
        $types .= "s";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . date('Y-m-d', strtotime($row['date'])) . "</td>
                <td>" . $row['po_no'] . "</td>
                <td>" . $row['client_name'] . "</td>
                <td>" . $row['project_name'] . "</td>
                <td>" . $row['product_classification'] . "</td>
                <td>" . $row['area'] . "</td>
                <td>₱" . number_format($row['total_amount'], 2) . "</td>
                <td>" . $row['or_no'] . "</td>
                <td>" . $row['sibi_no'] . "</td>
                <td>" . $row['dr_no'] . "</td>
                <td>" . $row['status'] . "</td>
                <td>
                    <a href='edit_sales.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='delete_sales.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                </td>
            </tr>";
    }
    exit();
}

// Regular Load
$selected_month = $_GET['month'] ?? date('Y-m');
$search_po_no = $_GET['po_no'] ?? '';

// Total Sales
$total_sales_query = "SELECT SUM(total_amount) AS total_sales FROM sales_records WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
$total_sales_stmt = $conn->prepare($total_sales_query);
$total_sales_stmt->bind_param("is", $userId, $selected_month);
$total_sales_stmt->execute();
$total_sales_result = $total_sales_stmt->get_result();
$total_sales_row = $total_sales_result->fetch_assoc();
$total_sales = $total_sales_row['total_sales'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Records | SMJ Furnishings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .container { margin-top: 50px; }
        .table { box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
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
            <div>
                <button class="btn btn-success" onclick="window.location.href='add_sales.php'">Add Sales</button>
                <button class="btn btn-primary" id="generatePdf">Generate PDF</button>
            </div>
        </div>
        <form method="GET" action="sales.php" class="mb-4" onsubmit="return false;">
            <div class="row">
                <div class="col-md-4">
                    <label for="month" class="form-label">Select Month</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?= $selected_month ?>">
                </div>
                <div class="col-md-4">
                    <label for="po_no" class="form-label">Search PO No</label>
                    <input type="text" id="po_no" name="po_no" class="form-control" placeholder="Enter PO No" value="<?= $search_po_no ?>">
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped" id="salesTable">
            <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>PO No</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Product Classification</th>
                <th>Area in sqm</th>
                <th>Total Amount</th>
                <th>OR</th>
                <th>SI/BI</th>
                <th>DR</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM sales_records WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
            $params = [$userId, $selected_month];
            $types = "is";

            if (!empty($search_po_no)) {
                $query .= " AND po_no LIKE ?";
                $params[] = '%' . $search_po_no . '%';
                $types .= "s";
            }

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= date('Y-m-d', strtotime($row['date'])) ?></td>
                    <td><?= $row['po_no'] ?></td>
                    <td><?= $row['client_name'] ?></td>
                    <td><?= $row['project_name'] ?></td>
                    <td><?= $row['product_classification'] ?></td>
                    <td><?= $row['area'] ?></td>
                    <td>₱<?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= $row['or_no'] ?></td>
                    <td><?= $row['sibi_no'] ?></td>
                    <td><?= $row['dr_no'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="edit_sales.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_sales.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="6" class="text-end">Total Sales:</th>
                <th colspan="5">₱<?= number_format($total_sales, 2) ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    const monthInput = document.getElementById('month');
    const poNoInput = document.getElementById('po_no');

    function fetchSalesData() {
        const poNo = poNoInput.value;
        const month = monthInput.value;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `sales.php?ajax=1&po_no=${encodeURIComponent(poNo)}&month=${encodeURIComponent(month)}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('#salesTable tbody').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    poNoInput.addEventListener('input', fetchSalesData);
    monthInput.addEventListener('change', fetchSalesData);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</body>
</html>
