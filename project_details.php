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
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .project-images img:hover {
            transform: scale(1.02);
        }
        .modal-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 10;
        }
        .modal-arrow.left {
            left: 10px;
        }
        .modal-arrow.right {
            right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-5 text-center">
        <h1 style="border-bottom: 2px solid #ED4135;" class="fw-bold d-inline-block pb-2 w-25 mb-5"><?= htmlspecialchars($project['name']) ?></h1>
        <div class="row">
            <?php foreach ($images as $index => $image): ?>
                <div class="col-md-4">
                    <div class="project-images">
                        <img src="uploads/projects/<?= $image ?>" alt="Project Image" class="img-fluid gallery-image" data-index="<?= $index ?>" data-bs-toggle="modal" data-bs-target="#imageModal">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="projects.php" class="btn btn-secondary">Back to Projects</a>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-relative">
                <button class="modal-arrow left" id="prevBtn">&#10094;</button>
                <button class="modal-arrow right" id="nextBtn">&#10095;</button>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Enlarged Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        const images = <?php echo json_encode($images); ?>;
        const modalImage = document.getElementById('modalImage');
        const modal = document.getElementById('imageModal');
        let currentIndex = 0;

        // Show selected image in modal
        document.querySelectorAll('.gallery-image').forEach(img => {
            img.addEventListener('click', function () {
                currentIndex = parseInt(this.getAttribute('data-index'));
                modalImage.src = 'uploads/projects/' + images[currentIndex];
            });
        });

        // Helper to update image
        function showImage(index) {
            currentIndex = (index + images.length) % images.length;
            modalImage.src = 'uploads/projects/' + images[currentIndex];
        }

        // Arrows click
        document.getElementById('prevBtn').addEventListener('click', () => showImage(currentIndex - 1));
        document.getElementById('nextBtn').addEventListener('click', () => showImage(currentIndex + 1));

        // Arrow keys + Escape key
        document.addEventListener('keydown', function (e) {
            const isVisible = modal.classList.contains('show');
            if (!isVisible) return;

            if (e.key === 'ArrowLeft') {
                showImage(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                showImage(currentIndex + 1);
            } else if (e.key === 'Escape') {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            }
        });
    </script>
</body>
</html>
