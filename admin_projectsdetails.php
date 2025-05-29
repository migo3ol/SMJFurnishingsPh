<?php
include 'database.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the project ID from the URL
if (!isset($_GET['id'])) {
    error_log("No project ID provided in URL");
    header('Location: admin_projects.php');
    exit();
}

$projectId = (int)$_GET['id'];
error_log("Project ID: $projectId");

// Fetch project details from the database
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $projectId);
if (!$stmt->execute()) {
    error_log("Failed to fetch project: " . $stmt->error);
    header('Location: admin_projects.php');
    exit();
}
$result = $stmt->get_result();
$project = $result->fetch_assoc();
$stmt->close();

if (!$project) {
    error_log("Project ID $projectId not found");
    header('Location: admin_projects.php');
    exit();
}

// Decode the images JSON
$images = json_decode($project['images'], true) ?? [];
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
        .image-preview.marked-for-deletion {
            opacity: 0.5;
            text-decoration: line-through;
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
                <a href="admin_projects.php" class="btn btn-secondary">Back to Projects</a>
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
                                        <input type="hidden" name="delete_images[]" value="<?= htmlspecialchars($image) ?>" disabled>
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
                    <form action="admin_projectsdetails.php" method="POST" style="display: inline;">
                        <input type="hidden" name="delete_project_id" value="<?= $projectId ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
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
                    const deleteInput = preview.querySelector('input[name="delete_images[]"]');
                    const existingInput = preview.querySelector('input[name="existing_images[]"]');

                    if (preview.classList.contains('marked-for-deletion')) {
                        // Undo deletion
                        preview.classList.remove('marked-for-deletion');
                        deleteInput.disabled = true;
                        existingInput.disabled = false;
                        e.target.textContent = '×';
                        e.target.title = 'Delete this image';
                    } else {
                        // Mark for deletion
                        preview.classList.add('marked-for-deletion');
                        deleteInput.disabled = false;
                        existingInput.disabled = true;
                        e.target.textContent = '↺';
                        e.target.title = 'Undo delete';
                    }
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
    $existingImages = isset($_POST['existing_images']) && is_array($_POST['existing_images']) ? $_POST['existing_images'] : [];
    $deleteImages = isset($_POST['delete_images']) && is_array($_POST['delete_images']) ? array_filter($_POST['delete_images']) : [];

    // Log submitted data for debugging
    error_log("POST data: " . print_r($_POST, true));
    error_log("Existing images: " . print_r($existingImages, true));
    error_log("Delete images: " . print_r($deleteImages, true));

    // Validate and handle new file uploads
    if (!empty($_FILES['project_images']['name'][0])) {
        foreach ($_FILES['project_images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['project_images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", basename($_FILES['project_images']['name'][$key]));
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $uploadedFiles[] = $fileName;
                    error_log("Uploaded file: $fileName");
                } else {
                    error_log("Failed to upload file: " . $_FILES['project_images']['name'][$key]);
                }
            }
        }
    }

    // Filter out deleted images from existing images
    $finalImages = array_diff($existingImages, $deleteImages);
    $finalImages = array_merge(array_values($finalImages), $uploadedFiles);

    // Log final images
    error_log("Final images: " . print_r($finalImages, true));

    // Delete removed images from the server
    $originalImages = json_decode($project['images'], true) ?? [];
    foreach ($originalImages as $image) {
        if (!in_array($image, $finalImages)) {
            $filePath = $uploadDir . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
                error_log("Deleted file: $filePath");
            }
        }
    }

    // Update the project in the database
    $query = "UPDATE projects SET name = ?, images = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $imagesJson = json_encode($finalImages);
    $stmt->bind_param("ssi", $projectName, $imagesJson, $projectId);
    if ($stmt->execute()) {
        error_log("Database updated successfully. Images JSON: $imagesJson");
    } else {
        error_log("Database update failed: " . $stmt->error);
    }
    $stmt->close();

    // Redirect to refresh the page
    header("Location: admin_projectsdetails.php?id=$projectId");
    exit();
}

// Handle Delete Project Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project_id'])) {
    $deleteProjectId = (int)$_POST['delete_project_id'];
    error_log("Attempting to delete project ID: $deleteProjectId");

    // Fetch the project details to get images
    $query = "SELECT images FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $deleteProjectId);
    if (!$stmt->execute()) {
        error_log("Failed to fetch project: " . $stmt->error);
        header('Location: admin_projects.php');
        exit();
    }
    $result = $stmt->get_result();
    $projectToDelete = $result->fetch_assoc();
    $stmt->close();

    if ($projectToDelete) {
        // Delete images from the server
        $imagesToDelete = json_decode($projectToDelete['images'], true) ?? [];
        foreach ($imagesToDelete as $image) {
            $filePath = 'Uploads/projects/' . $image;
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    error_log("Deleted project image: $filePath");
                } else {
                    error_log("Failed to delete project image: $filePath");
                }
            } else {
                error_log("Image file not found: $filePath");
            }
        }

        // Delete the project from the database
        $query = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $deleteProjectId);
        if ($stmt->execute()) {
            error_log("Project ID $deleteProjectId deleted successfully");
        } else {
            error_log("Failed to delete project: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Project ID $deleteProjectId not found");
    }

    // Redirect to the admin projects page
    header('Location: admin_projects.php');
    exit();
}

// Close database connection
$conn->close();
?>