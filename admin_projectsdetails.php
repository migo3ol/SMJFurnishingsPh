<?php
include 'database.php';

// Get the project ID from the URL
if (!isset($_GET['id'])) {
    header('Location: admin_projects.php'); // Redirect if no ID is provided
    exit();
}

$projectId = $_GET['id'];

// Fetch project details from the database
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
    header('Location: admin_projects.php'); // Redirect if project not found
    exit();
}

// Decode the images JSON
$images = json_decode($project['images'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .project-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .drop-zone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            margin-bottom: 15px;
        }
        .drop-zone.dragover {
            background: #e1f5fe;
            border-color: #2196f3;
        }
        .image-preview {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .delete-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Side Navbar -->
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="container col-md-10 my-5">
            <h1 class="fw-bold mb-4"><?= htmlspecialchars($project['name']) ?></h1>
            <div class="header-buttons d-flex justify-content-between align-items-center mb-5">
                <a href="inventory.php" class="btn btn-secondary">Back to Inventory</a>
                <div class="right-buttons d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                </div>
            </div>
            
            <!-- Project Images -->
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <img src="Uploads/projects/<?= htmlspecialchars($image) ?>" alt="Project Image" class="project-image">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="admin_projectsdetails.php?id=<?= $projectId ?>" method="POST" enctype="multipart/form-data" id="editForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" value="<?= htmlspecialchars($project['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="project_images" class="form-label">Project Images</label>
                            <div class="drop-zone" id="dropZone">
                                Drag and drop images here or click to select
                                <input type="file" class="form-control d-none" id="project_images" name="project_images[]" multiple accept="image/*">
                            </div>
                            <small class="text-muted">Upload new images or delete existing ones.</small>
                        </div>
                        <div class="mb-3">
                            <label>Existing Images:</label>
                            <div class="d-flex flex-wrap gap-2" id="existingImages">
                                <?php foreach ($images as $index => $image): ?>
                                    <div class="image-preview" data-image="<?= htmlspecialchars($image) ?>">
                                        <img src="Uploads/projects/<?= htmlspecialchars($image) ?>" alt="Existing Image">
                                        <button type="button" class="delete-btn" data-index="<?= $index ?>">×</button>
                                        <input type="hidden" name="existing_images[]" value="<?= htmlspecialchars($image) ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>New Images Preview:</label>
                            <div class="d-flex flex-wrap gap-2" id="newImagesPreview"></div>
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
                    <a href="admin_projectsdetails.php?delete_project_id=<?= $projectId ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('project_images');
            const existingImages = document.getElementById('existingImages');
            const newImagesPreview = document.getElementById('newImagesPreview');

            // Handle click to open file input
            dropZone.addEventListener('click', () => fileInput.click());

            // Handle drag and drop
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                fileInput.files = files;
                previewImages(files);
            });

            // Handle file input change
            fileInput.addEventListener('change', () => {
                previewImages(fileInput.files);
            });

            // Preview images
            function previewImages(files) {
                newImagesPreview.innerHTML = '';
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'image-preview';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="New Image">
                            <button type="button" class="delete-btn" data-new-index="${index}">×</button>
                        `;
                        newImagesPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Handle delete existing images
            existingImages.addEventListener('click', (e) => {
                if (e.target.classList.contains('delete-btn')) {
                    const preview = e.target.parentElement;
                    preview.remove();
                }
            });

            // Handle delete new images
            newImagesPreview.addEventListener('click', (e) => {
                if (e.target.classList.contains('delete-btn')) {
                    const index = e.target.dataset.newIndex;
                    const dt = new DataTransfer();
                    Array.from(fileInput.files).forEach((file, i) => {
                        if (i != index) dt.items.add(file);
                    });
                    fileInput.files = dt.files;
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
</body>
</html>

<?php
// Handle Edit Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_name'])) {
    $projectName = $_POST['project_name'];
    $uploadDir = 'Uploads/projects/';
    $uploadedFiles = [];
    $existingImages = isset($_POST['existing_images']) ? $_POST['existing_images'] : [];

    // Handle new file uploads
    if (!empty($_FILES['project_images']['name'][0])) {
        foreach ($_FILES['project_images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['project_images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['project_images']['name'][$key]);
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $uploadedFiles[] = $fileName;
                }
            }
        }
    }

    // Combine existing images (those not deleted) with new uploads
    $finalImages = array_merge($existingImages, $uploadedFiles);

    // Delete removed images from server
    $originalImages = json_decode($project['images'], true);
    foreach ($originalImages as $image) {
        if (!in_array($image, $existingImages)) {
            $filePath = $uploadDir . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // Update the project in the database
    $query = "UPDATE projects SET name = ?, images = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $imagesJson = json_encode($finalImages);
    $stmt->bind_param("ssi", $projectName, $imagesJson, $projectId);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page
    header("Location: admin_projectsdetails.php?id=$projectId");
    exit();
}

// Handle Delete Request
if (isset($_GET['delete_project_id'])) {
    $deleteProjectId = $_GET['delete_project_id'];

    // Fetch the project details to delete images
    $query = "SELECT images FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $deleteProjectId);
    $stmt->execute();
    $result = $stmt->get_result();
    $projectToDelete = $result->fetch_assoc();

    if ($projectToDelete) {
        $imagesToDelete = json_decode($projectToDelete['images'], true);

        // Delete images from the server
        foreach ($imagesToDelete as $image) {
            $filePath = 'Uploads/projects/' . $image;
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }

        // Delete the project from the database
        $query = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $deleteProjectId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to the admin projects page
    header('Location: admin_projects.php');
    exit();
}
?>