<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projects</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f4;
}

.projects-section {
    padding: 60px 0;
}

.project-card {
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    margin-top: -20px;
    transition: all 0.3s ease;
    text-align: left;
}

.project-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.project-card img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    transition: all 0.3s ease;
    position: relative;
}

.project-card:hover img {
    transform: scale(1.1);
    box-shadow: inset 0 0 50px rgba(0,0,0,0.5);
}

.project-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0);
    transition: background 0.3s ease;
    z-index: 1;
}

.project-card:hover::before {
    background: rgba(0,0,0,0.2);
}

.view-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0,0,0,0.7);
    color: white;
    border: none;
    padding: 10px 30px;
    font-weight: 600;
    letter-spacing: 2px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 3;
}

.project-card:hover .view-btn {
    opacity: 1;
}

.project-location {
    display: inline-block;
    color: #333;
    font-weight: 600;
    padding: 10px 0 8px 15px;
    margin-top: 15px;
    font-size: 0.9rem;
    position: relative;
}

.project-location::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 15px;
    width: 150px; /* Fixed width for the underline */
    height: 2px;
    background-color: #ED4135;
}

@media (max-width: 768px) {
    .project-card {
        margin-top: -10px;
    }
    .project-card img {
        height: 250px;
    }
    .view-btn {
        padding: 8px 20px;
        font-size: 0.8rem;
    }
    .project-location::after {
        width: 40px; /* Slightly smaller width for mobile */
    }
}
</style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>


        <div class="container my-5 text-center">
        <h1 style="border-bottom: 2px solid #ED4135;" class="fw-bold d-inline-block pb-2 w-25 mb-5">
        Projects
    </h1>
        </div>

        <div class="container projects-section">
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="project-card">
                <img src="assets/projects/Project1.png" alt="Project in Quezon City, Taguig City">
                <button class="view-btn">VIEW</button>
                <div class="project-location">Quezon City, Taguig City</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="project-card">
                <img src="assets/projects/Project2.png" alt="Project in Makati, Cebu, Mandaluyong">
                <button class="view-btn">VIEW</button>
                <div class="project-location">Makati, Cebu, Mandaluyong</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="project-card">
                <img src="assets/projects/Project3.png" alt="Project in Makati City, Quezon City">
                <button class="view-btn">VIEW</button>
                <div class="project-location">Makati City, Quezon City</div>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>   

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>