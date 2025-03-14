<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | SMJ Furnishings</title>
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
        <div class="col-md-3">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-9 ms-auto">
            <h1 class="mb-5 fw-bold">Point of Sales</h1>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order No.</th>
                        <th>Client Name</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>031425</td>
                        <td>John Doe</td>
                        <td>Bluestone SQ</td>
                        <td>300</td>
                        <td>P15000</td>
                        <td>March 14, 2025</td>
                        <td><button class="btn btn-primary">Edit</button>
                        <button class="btn btn-secondary">Delete</button></td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>