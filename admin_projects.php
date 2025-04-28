<?php
include 'database.php';

// Fetch all projects from the database
$query = "SELECT * FROM projects";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Projects</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .projects-section .project-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .projects-section .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .projects-section .project-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .projects-section .view-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 10px 30px;
            letter-spacing: 2px;
            opacity: 0;
            font-weight: 600;
            transition: opacity 0.3s ease;
            z-index: 3;
        }
        .projects-section .project-card:hover .view-btn {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fw-bold mt-5">Projects</h1>
                <button class="btn btn-success mt-5" onclick="window.location.href='add_projects.php'">Add Project</button>
            </div>
            <div class="row g-4 projects-section">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $images = json_decode($row['images'], true); // Decode the JSON-encoded images
                    $firstImage = $images[0] ?? 'default.jpg'; // Use the first image or a default image
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-card">
                        <!-- Project Image -->
                        <img src="uploads/projects/<?= htmlspecialchars($firstImage) ?>" alt="Project Image" class="card-img-top">
                            
                        <!-- View Button -->
                        <a href="admin_projectsdetails.php?id=<?= $row['id'] ?>" class="view-btn">View</a>
                        </div>

                        <!-- Project Name -->
                        <div class="text-center mt-3">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>