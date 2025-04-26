<?php
include 'database.php';

// Fetch items grouped by tile types
$tile_types = [
    "Nylon Tiles" => "SELECT * FROM nylon_tiles",
    "Polypropylene Tiles" => "SELECT * FROM polypropylene_tiles",
    "Colordot Collection" => "SELECT * FROM colordot_collections",
    "Luxury Vinyl Tiles" => "SELECT * FROM luxury_vinyl",
    "Broadloom" => "SELECT * FROM broadloom"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | SMJ Furnishings</title>
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
        .card {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card img {
            height: 150px;
            object-fit: cover;
        }
        .tile-section {
            margin-bottom: 50px;
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
                <h1 class="fw-bold">Inventory</h1>
                <button class="btn btn-success" onclick="window.location.href='add_items.php'">Add new item</button>
            </div>

            <?php foreach ($tile_types as $tile_type => $query): ?>
                <?php
                $result = $conn->query($query);
                if ($result->num_rows > 0):
                ?>
                    <div class="tile-section">
                        <h2 class="fw-bold mb-4"><?= $tile_type ?></h2>
                        <div class="row">
                            <?php while ($item = $result->fetch_assoc()): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                    <img src="uploads/<?= htmlspecialchars($item['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['style_name']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($item['style_name']) ?></h5>
                                            <p class="card-text">
                                                <?php if (isset($item['construction'])): ?>
                                                    <strong>Construction:</strong> <?= htmlspecialchars($item['construction']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['yarn_system'])): ?>
                                                    <strong>Yarn System:</strong> <?= htmlspecialchars($item['yarn_system']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['dye_method'])): ?>
                                                    <strong>Dye Method:</strong> <?= htmlspecialchars($item['dye_method']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['backing'])): ?>
                                                    <strong>Backing:</strong> <?= htmlspecialchars($item['backing']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['size'])): ?>
                                                    <strong>Size:</strong> <?= htmlspecialchars($item['size']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['overall_gauge'])): ?>
                                                    <strong>Overall Gauge:</strong> <?= htmlspecialchars($item['overall_gauge']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['wear_layer'])): ?>
                                                    <strong>Wear Layer:</strong> <?= htmlspecialchars($item['wear_layer']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['finish'])): ?>
                                                    <strong>Finish:</strong> <?= htmlspecialchars($item['finish']) ?><br>
                                                <?php endif; ?>
                                                <?php if (isset($item['width'])): ?>
                                                    <strong>Width:</strong> <?= htmlspecialchars($item['width']) ?><br>
                                                <?php endif; ?>
                                            </p>
                                            <a href="edit_item.php?id=<?= $item['id'] ?>&type=<?= urlencode($tile_type) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(<?= $item['id'] ?>, '<?= htmlspecialchars($tile_type) ?>')">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="delete_item.php" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showDeleteModal(itemId, tileType) {
            const deleteUrl = `delete_item.php?id=${itemId}&type=${encodeURIComponent(tileType)}`;
            document.getElementById('confirmDeleteButton').setAttribute('href', deleteUrl);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>