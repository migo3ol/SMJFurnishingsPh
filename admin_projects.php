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
        .container {
            margin-top: 50px;
        }
        .table {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .project-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 5px;
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
                <h1 class="fw-bold">Projects</h1>
                <button class="btn btn-success" onclick="window.location.href='add_projects.php'">Add Project</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Images</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td class="project-images">
                            <?php
                            $images = json_decode($row['images'], true); // Decode the JSON-encoded images
                            foreach ($images as $image) {
                                echo "<img src='uploads/projects/$image' alt='Project Image'>";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit_project.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                            <a href="delete_project.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>