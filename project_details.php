<?php
include 'database.php';

// Get the project ID from the query string
$projectId = $_GET['id'] ?? null;

if (!$projectId) {
    die('Project not found.');
}

// Fetch the project details from the database
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
    die('Project not found.');
}

$images = json_decode($project['images'], true); // Decode the JSON-encoded images
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($project['name']) ?> - Project Details</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .project-images img {
            width: 100%; /* Ensure the image takes up the full width of its container */
            height: 500px; /* Set a fixed height for the images */
            object-fit: cover; /* Crop the image to fit the dimensions */
            border-radius: 5px; /* Optional: Add rounded corners */
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-5 text-center">
        <h1 style="border-bottom: 2px solid #ED4135;" class="fw-bold d-inline-block pb-2 w-25 mb-5"><?= htmlspecialchars($project['name']) ?></h1>
        <div class="row">
            <?php foreach ($images as $image): ?>
                <div class="col-md-4">
                    <div class="project-images">
                        <img src="uploads/projects/<?= $image ?>" alt="Project Image" class="img-fluid">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="projects.php" class="btn btn-secondary">Back to Projects</a>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>