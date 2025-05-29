<?php
include 'database.php';

// Check if project ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_projects.php");
    exit();
}

$project_id = intval($_GET['id']);

// Fetch project details
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
$images = json_decode($project['images'], true) ?? [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $new_images = $_FILES['images']['name'];
    $existing_images = isset($_POST['existing_images']) ? $_POST['existing_images'] : [];
    $all_images = $existing_images;

    // Validate name
    if (empty($name)) {
        $error = "Project name is required.";
    } else {
        // Handle new image uploads
        if (!empty($new_images[0])) {
            foreach ($new_images as $index => $image_name) {
                if ($image_name) {
                    $target_dir = "Uploads/projects/";
                    $target_file = $target_dir . basename($image_name);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    // Validate image
                    if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['images']['size'][$index] <= 5000000) {
                        if (move_uploaded_file($_FILES['images']['tmp_name'][$index], $target_file)) {
                            $all_images[] = $image_name;
                        }
                    }
                }
            }
        }

        // Update project in database
        $images_json = json_encode($all_images);
        $update_query = "UPDATE projects SET name = ?, images = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $name, $images_json, $project_id);
        
        if ($update_stmt->execute()) {
            header("Location: admin_projectsdetails.php?id=$project_id");
            exit();
        } else {
            $error = "Failed to update project.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - <?= htmlspecialchars($project['name']) ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .form-container {
            /* Full-width form */
            margin: auto;
        }
        .image-preview, .new-image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .image-preview img, .new-image-preview img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-container {
            position: relative;
            display: inline-block;
        }
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.2s;
        }
        .remove-btn:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }
        .image-removed {
            opacity: 0.5;
            filter: grayscale(100%);
        }
        .action-btn {
            letter-spacing: 1px;
            font-weight: 600;
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
                <h1 class="fw-bold mt-5"><?= htmlspecialchars($project['name']) ?></h1>
                <a href="admin_projectsdetails.php?id=<?= $project_id ?>" class="btn btn-primary mt-5 action-btn">Back to Details</a>
            </div>
            <div class="form-container">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Images</label>
                        <div class="image-preview">
                            <?php foreach ($images as $index => $image): ?>
                                <div class="image-container" data-image="<?= htmlspecialchars($image) ?>">
                                    <img src="Uploads/projects/<?= htmlspecialchars($image) ?>" alt="Project Image" data-image="<?= htmlspecialchars($image) ?>">
                                    <button type="button" class="remove-btn" onclick="deleteImage(this, '<?= htmlspecialchars($image) ?>', <?= $project_id ?>)">X</button>
                                    <input type="hidden" name="existing_images[]" value="<?= htmlspecialchars($image) ?>" class="image-input">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="images" class="form-label">Add New Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <div class="new-image-preview mt-3"></div>
                    </div>
                    <button type="submit" class="btn btn-success action-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Delete existing image via AJAX
        function deleteImage(button, imageName, projectId) {
            const container = button.parentElement;
            const image = container.querySelector('img');
            const input = container.querySelector('.image-input');
            
            // Confirm deletion
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            // Send AJAX request to delete image
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_image.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Remove image from UI
                        container.remove();
                    } else {
                        alert('Failed to delete image: ' + response.error);
                    }
                }
            };
            xhr.send('project_id=' + encodeURIComponent(projectId) + '&image_name=' + encodeURIComponent(imageName));

            // Update UI immediately (optimistic update)
            input.disabled = true;
            image.classList.add('image-removed');
            button.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
            button.style.color = 'black';
        }

        // Preview and manage new images
        const fileInput = document.getElementById('images');
        const newImagePreview = document.querySelector('.new-image-preview');
        
        fileInput.addEventListener('change', function() {
            newImagePreview.innerHTML = ''; // Clear previous previews
            const files = Array.from(this.files);
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.createElement('div');
                    container.className = 'image-container';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'New Image Preview';
                    img.dataset.index = index;
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-btn';
                    removeBtn.textContent = 'X';
                    removeBtn.onclick = function() {
                        removeNewImage(index);
                        container.remove();
                    };
                    
                    container.appendChild(img);
                    container.appendChild(removeBtn);
                    newImagePreview.appendChild(container);
                };
                reader.readAsDataURL(file);
            });
        });

        function removeNewImage(indexToRemove) {
            const files = Array.from(fileInput.files);
            const newFiles = files.filter((_, index) => index !== indexToRemove);
            
            const dataTransfer = new DataTransfer();
            newFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
            
            // Trigger change event toL update preview
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    </script>
</body>
</html>