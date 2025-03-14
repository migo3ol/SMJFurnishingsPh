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
            cursor: pointer
        }
        .sidebar a {
            text-decoration: none;
        }
        .submenu {
            display: none;
            padding-left: 20px;
        }
        .submenu h4 {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">
            <img src="assets/navbar-logo1.png" alt="SMJ Logo" class="mb-5" width="100%">
            <h4 href="#" class="text-dark mb-5"><img src="assets/dashboard.png" class="img-fluid me-3"></img> Dashboard</h4>
            <h4 href="#" class="text-dark mb-5"><img src="assets/sales.png" class="img-fluid me-3"></img> Sales</h4>
            <h4 href="#" class="text-dark mb-5" id="inventoryLink"><img src="assets/inventory.png" class="img-fluid me-3"></img> Inventory</h4>
            <div class="submenu" id="inventorySubmenu">
                <h4 href="#" class="text-dark mb-5">Nylon Tiles</h4>
                <h4 href="#" class="text-dark mb-5">Polypropylene Tiles</h4>
                <h4 href="#" class="text-dark mb-5">Luxury Vinyl Tiles</h4>
            </div>
            <h4 href="#" class="text-dark mb-5"><img src="assets/employees.png" class="img-fluid me-3"></img> Employees</h4>
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