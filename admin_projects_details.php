<?php
include 'database.php';

// Check if project ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_projects.php");
    exit();
}

$project_id = intval($_GET['id']);

// Fetch project details from the database
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin_projects.php");
    exit();
}

$project = $result->fetch_assoc();
$images = json_decode($project['images'], true); // Decode JSON images array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details - <?= htmlspecialchars($project['name']) ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .project-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        .action-btn {
            letter-spacing: 1px;
            font-weight: 600;
            margin-left: 10px;
        }
        .back-btn {
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-10">                 
            <h1 class="fw-bold mt-5"><?= htmlspecialchars($project['name']) ?></h1>
            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="admin_projects.php" class="btn btn-secondary mt-5">Back to Projects</a>
                <div class="right-buttons mt-5">
                    <a href="edit_projects.php?id=<?= $project_id ?>" class="btn btn-primary ms-2">Edit</a>
                    <a href="delete_projects.php?id=<?= $project_id ?>" class="btn btn-danger ms-2" onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                </div>
            </div>
            <div class="image-grid">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $image): ?>
                        <img src="Uploads/projects/<?= htmlspecialchars($image) ?>" alt="Project Image" class="project-image">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No images available for this project.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>