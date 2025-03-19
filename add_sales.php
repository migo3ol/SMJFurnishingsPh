<?php
// add_sales.php - Add a new sale
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO sales_records (order_no, po_no, client_name, project_name, product_specification, area, total_amount, or_no, dr_no, status, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdssss", $_POST['order_no'], $_POST['po_no'], $_POST['client_name'], $_POST['project_name'], $_POST['product_classification'], $_POST['area'], $_POST['total_amount'], $_POST['or_no'], $_POST['dr_no'], $_POST['status'], $current_date);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: sales.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sales | SMJ Furnishings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .container {
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
            <h1 class="fw-bold mb-5">Add Sales</h1>
            <form action="add_sales.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order No.</label>
                        <input type="text" class="form-control" name="order_no" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">PO No</label>
                        <input type="text" class="form-control" name="po_no" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Client Name</label>
                    <input type="text" class="form-control" name="client_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Project Name</label>
                    <input type="text" class="form-control" name="project_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Product Classification</label>
                    <input type="text" class="form-control" name="product_classification" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Area</label>
                    <input type="text" class="form-control" name="area" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <input type="number" class="form-control" name="total_amount" step="0.01" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">OR No.</label>
                        <input type="text" class="form-control" name="or_no" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">DR No.</label>
                        <input type="text" class="form-control" name="dr_no" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Add Sales</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='sales.php'">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>