<?php
include 'database.php';

// Handle Edit Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_project_id'])) {
    $projectId = $_POST['edit_project_id'];
    $projectName = $_POST['edit_project_name'];
    $uploadDir = 'uploads/projects/';
    $uploadedFiles = [];

    // Fetch existing images
    $query = "SELECT images FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    $existingImages = json_decode($project['images'], true);

    // Handle new file uploads
    if (!empty($_FILES['edit_project_images']['name'][0])) {
        foreach ($_FILES['edit_project_images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['edit_project_images']['name'][$key]);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $uploadedFiles[] = $fileName;
            }
        }
    } else {
        $uploadedFiles = $existingImages; // Keep existing images if no new images are uploaded
    }

    // Update the project in the database
    $query = "UPDATE projects SET name = ?, images = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $imagesJson = json_encode($uploadedFiles);
    $stmt->bind_param("ssi", $projectName, $imagesJson, $projectId);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to refresh the project list
    header('Location: admin_projects.php');
    exit();
}

// Handle Delete Request
if (isset($_GET['delete_project_id'])) {
    $projectId = $_GET['delete_project_id'];

    // Fetch the project details to delete images
    $query = "SELECT images FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();

    if ($project) {
        $images = json_decode($project['images'], true);

        // Delete images from the server
        foreach ($images as $image) {
            $filePath = 'uploads/projects/' . $image;
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }

        // Delete the project from the database
        $query = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to the same page to refresh the project list
    header('Location: admin_projects.php');
    exit();
}

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
                            <!-- Edit Button -->
                            <button class="btn btn-outline-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal" 
                                    onclick="loadEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>', '<?= htmlspecialchars(json_encode($images)) ?>')">
                                Edit
                            </button>
                            <!-- Delete Button -->
                            <button class="btn btn-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    onclick="setDeleteId(<?= $row['id'] ?>)">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form id="editForm" action="admin_projects.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_project_id" id="edit_project_id">
                    <div class="mb-3">
                        <label for="edit_project_name" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="edit_project_name" name="edit_project_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_project_images" class="form-label">Project Images</label>
                        <input type="file" class="form-control" id="edit_project_images" name="edit_project_images[]" multiple>
                        <small class="text-muted">Upload new images to replace existing ones.</small>
                    </div>
                    <div class="mb-3">
                        <label>Existing Images:</label>
                        <div id="existing_images" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this project?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadEditModal(id, name, images) {
            document.getElementById('edit_project_id').value = id;
            document.getElementById('edit_project_name').value = name;

            const existingImagesContainer = document.getElementById('existing_images');
            existingImagesContainer.innerHTML = ''; // Clear previous images
            const imageArray = JSON.parse(images);
            imageArray.forEach(image => {
                const img = document.createElement('img');
                img.src = 'uploads/projects/' + image;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.marginRight = '5px';
                existingImagesContainer.appendChild(img);
            });
        }

        function setDeleteId(id) {
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.href = `admin_projects.php?delete_project_id=${id}`;
        }
    </script>
</body>
</html>