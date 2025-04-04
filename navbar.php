<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMJ Furnishings</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="navbar.css">

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm p-3 bg-body-tertiary sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/navbar-logo1.png" alt="Logo">
        </a>        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.php">About us</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="collectionsDropdown" role="button" data-bs-toggle="dropdown">
                        Collections
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Nylon Tiles</a></li>
                        <li><a class="dropdown-item" href="#">Polypropylene Tiles</a></li>
                        <li><a class="dropdown-item" href="#">Luxury Vinyl Tiles</a></li>
                        <li><a class="dropdown-item" href="#">Broadloom</a></li>
                    </ul>
                </li>
                
                <li class="nav-item"><a class="nav-link" href="projects.php">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.php">Contact us</a></li>
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get current URL path
        let currentPath = window.location.pathname.split("/").pop();

        // Get all nav links
        let navLinks = document.querySelectorAll(".navbar-nav .nav-link");

        navLinks.forEach(link => {
            let linkPath = link.getAttribute("href");

            // If the link matches the current path, add "active" class
            if (linkPath === currentPath) {
                link.classList.add("active");
            }
        });
    });
</script>
    
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>