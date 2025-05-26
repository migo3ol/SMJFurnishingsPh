<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href انواع: "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
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
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
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
    .content {
      margin-left: 250px;
      padding: 40px;
    }
    .profile-form {
      max-width: 600px;
    }
    .form-label {
      font-weight: 600;
    }
    .btn-save {
      background-color: #28a745;
      color: white;
    }
    .btn-save:hover {
      background-color: #218838;
    }
    .password-container {
      position: relative;
    }
    .toggle-password {
      background: transparent;
      border: none;
      cursor: pointer;
    }
    .input-group-text {
      background: transparent;
      border: none;
    }
    .input-group-text i {
      color: #000;
    }
  </style>
</head>
<body>
  <div class="sidebar d-flex flex-column">
    <img src="assets/navbar-logo1.png" alt="SMJ Logo" class="mb-5" width="100%">
    <a href="dashboard.php" class="text-dark mb-3"><img src="assets/dashboard.png" class="img-fluid me-3"> Dashboard</a>
    <a href="sales.php" class="text-dark mb-3"><img src="assets/sales.png" class="img-fluid me-3"> Sales</a>
    <a href="inventory.php" class="text-dark mb-3" id="inventoryLink"><img src="assets/inventory.png" class="img-fluid me-3"> Inventory</a>
    <a href="admin_projects.php" class="text-dark mb-3"><img src="assets/project-icon.png" class="img-fluid me-3"> Projects</a>
    <a href="edit_profile.php" class="text-dark mb-3 active-link"><i class="fas fa-user me-3"></i> Profile</a>
    <button type="button" class="logout-btn w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
  </div>

  <div class="content">
    <h2>Edit Profile</h2>
    <?php
    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
    if ($message) {
      echo '<div class="alert alert-info">' . $message . '</div>';
    }
    ?>
    <form class="profile-form" action="update_profile.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="firstname" class="form-label">First Name</label>
          <input type="text" class="form-control" id="firstname" name="firstname" required>
        </div>
        <div class="col-md-4">
          <label for="middlename" class="form-label">Middle Name</label>
          <input type="text" class="form-control" id="middlename" name="middlename">
        </div>
        <div class="col-md-4">
          <label for="surname" class="form-label">Surname</label>
          <input type="text" class="form-control" id="surname" name="surname" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone">
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="password" class="form-label">New Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password">
            <span class="input-group-text toggle-password"><i class="fas fa-eye"></i></span>
          </div>
        </div>
        <div class="col-md-6">
          <label for="confirm_password" class="form-label">Confirm New Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            <span class="input-group-text toggle-password"><i class="fas fa-eye"></i></span>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="pfp" class="form-label">Profile Picture</label>
        <input type="file" class="form-control" id="pfp" name="pfp" accept="image/png,image/jpeg">
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-save">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>

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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script>
    document.querySelectorAll('.toggle-password').forEach(item => {
      item.addEventListener('click', function() {
        let input = this.previousElementSibling;
        if (input.type === "password") {
          input.type = "text";
          this.querySelector('i').classList.replace("fa-eye", "fa-eye-slash");
        } else {
          input.type = "password";
          this.querySelector('i').classList.replace("fa-eye-slash", "fa-eye");
        }
      });
    });

    function validateForm() {
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirm_password").value;
      if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
      }
      return true;
    }

    const currentPage = window.location.pathname.split('/').pop();
    const links = document.querySelectorAll('.sidebar a');
    links.forEach(link => {
      const href = link.getAttribute('href');
      if (href === currentPage) {
        link.classList.add('active-link');
      }
    });

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