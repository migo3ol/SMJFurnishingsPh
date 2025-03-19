<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            cursor: pointer;
        }
        .sidebar a {
            text-decoration: none;
            color: #000;
        }
        .submenu {
            display: none;
            padding-left: 20px;
        }
        .submenu a {
            display: block;
            margin-bottom: 10px;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">
            <img src="assets/navbar-logo1.png" alt="SMJ Logo" class="mb-5" width="100%">
            <a href="dashboard.php" class="text-dark mb-5"><img src="assets/dashboard.png" class="img-fluid me-3"> Dashboard</a>
            <a href="sales.php" class="text-dark mb-5"><img src="assets/sales.png" class="img-fluid me-3"> Sales</a>
            <a href="#" class="text-dark mb-5" id="inventoryLink"><img src="assets/inventory.png" class="img-fluid me-3"> Inventory</a>
            <div class="submenu" id="inventorySubmenu">
                <a href="#" class="text-dark mb-5">Nylon Tiles</a>
                <a href="#" class="text-dark mb-5">Polypropylene Tiles</a>
                <a href="#" class="text-dark mb-5">Luxury Vinyl Tiles</a>
            </div>
            <a href="#" class="text-dark mb-5"><img src="assets/project-icon.png" class="img-fluid me-3"> Projects</a>
            <a href="#" class="text-dark mb-5"><img src="assets/employees.png" class="img-fluid me-3"> Employees</a>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('inventoryLink').addEventListener('click', function() {
            var submenu = document.getElementById('inventorySubmenu');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>
</html>