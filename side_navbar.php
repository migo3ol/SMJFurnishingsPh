<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"/>
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
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 5px;
    }
    .sidebar a.active-link {
      background-color: #ffe5e3;
      font-weight: 600;
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
    .logout-btn {
      margin-top: auto;
      background-color: #ED4135;
      color: white;
      border: none;
      padding: 10px 20px;
      text-align: center;
      border-radius: 5px;
      cursor: pointer;
    }
    .logout-btn:hover {
      background-color: #c7352b;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
      <img src="assets/navbar-logo1.png" alt="SMJ Logo" class="mb-5" width="100%">
      <a href="dashboard.php" class="text-dark mb-3"><img src="assets/dashboard.png" class="img-fluid me-3"> Dashboard</a>
      <a href="sales.php" class="text-dark mb-3"><img src="assets/sales.png" class="img-fluid me-3"> Sales</a>
      <a href="inventory.php" class="text-dark mb-3" id="inventoryLink"><img src="assets/inventory.png" class="img-fluid me-3"> Inventory</a>
      <a href="admin_projects.php" class="text-dark mb-3"><img src="assets/project-icon.png" class="img-fluid me-3"> Projects</a>

      <!-- Logout Button -->
      <button type="button" class="logout-btn w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Are you sure you want to log out?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger">Log Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap + Custom Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script>
    // Highlight current page link
    const currentPage = window.location.pathname.split('/').pop();
    const links = document.querySelectorAll('.sidebar a');

    links.forEach(link => {
      const href = link.getAttribute('href');
      if (href === currentPage) {
        link.classList.add('active-link');
      }
    });

    // Inventory submenu toggle (optional feature from earlier)
    const inventoryLink = document.getElementById('inventoryLink');
    if (inventoryLink) {
      inventoryLink.addEventListener('click', function () {
        const submenu = document.getElementById('inventorySubmenu');
        if (submenu) submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
      });
    }
  </script>
</body>
</html>
