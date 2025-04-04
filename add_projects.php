<?php
include 'database.php';

// Handle Add Project Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = $_POST['project_name'];
    $uploadDir = 'uploads/projects/';
    $uploadedFiles = [];

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file uploads
    foreach ($_FILES['project_images']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['project_images']['name'][$key]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFile)) {
            $uploadedFiles[] = $fileName;
        }
    }

    // Insert project details into the database
    $query = "INSERT INTO projects (name, images) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $imagesJson = json_encode($uploadedFiles); // Store image file names as JSON
    $stmt->bind_param("ss", $projectName, $imagesJson);
    $stmt->execute();
    $stmt->close();

    // Redirect to the admin projects page
    header('Location: admin_projects.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
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
        .preview-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }
        .preview-images .image-container {
            position: relative;
        }
        .preview-images img {
            width: 150px; /* Increased width */
            height: 150px; /* Increased height */
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .preview-images .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-10">
            <h1 class="fw-bold mb-4">Add Project</h1>
            <form action="add_projects.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="project_name" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="project_name" name="project_name" required>
                </div>
                <div class="mb-3">
                    <label for="project_images" class="form-label">Project Images</label>
                    <input type="file" class="form-control" id="project_images" name="project_images[]" multiple required>
                    <small class="text-muted">You can upload multiple images.</small>
                </div>
                <!-- Preview Images -->
                <div class="preview-images" id="preview-images"></div>
                <button type="submit" class="btn btn-primary mt-3">Add Project</button>
                <a href="admin_projects.php" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview selected images
        document.getElementById('project_images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('preview-images');
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(event.target.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.classList.add('image-container');

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const deleteBtn = document.createElement('button');
                    deleteBtn.classList.add('delete-btn');
                    deleteBtn.innerHTML = '&times;';
                    deleteBtn.addEventListener('click', function() {
                        // Remove the image from the preview
                        imageContainer.remove();

                        // Remove the file from the input
                        const dataTransfer = new DataTransfer();
                        const input = document.getElementById('project_images');
                        Array.from(input.files).forEach((file, i) => {
                            if (i !== index) {
                                dataTransfer.items.add(file);
                            }
                        });
                        input.files = dataTransfer.files;
                    });

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(deleteBtn);
                    previewContainer.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>
</html>