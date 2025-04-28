<?php
include 'database.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $tile_type = isset($_POST['tile_type']) ? $_POST['tile_type'] : '';

    if ($item_id <= 0 || empty($tile_type)) {
        $error_message = "Invalid item ID or type.";
    } else {
        $tables = [
            "Nylon Tiles" => ["table" => "nylon_tiles", "variation_table" => "nylon_tiles_variations"],
            "Polypropylene Tiles" => ["table" => "polypropylene_tiles", "variation_table" => "polypropylene_tiles_variations"],
            "Colordot Collection" => ["table" => "colordot_collections", "variation_table" => "colordot_collections_variations"],
            "Luxury Vinyl Tiles" => ["table" => "luxury_vinyl", "variation_table" => "luxury_vinyl_variations"],
            "Broadloom" => ["table" => "broadloom", "variation_table" => "broadloom_variations"]
        ];

        if (!isset($tables[$tile_type])) {
            $error_message = "Invalid tile type.";
        } else {
            $table = $tables[$tile_type]['table'];
            $variation_table = $tables[$tile_type]['variation_table'];

            // Start transaction
            $conn->begin_transaction();
            try {
                // Delete variations
                $stmt = $conn->prepare("DELETE FROM `$variation_table` WHERE item_id = ?");
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $stmt->close();

                // Delete main item
                $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $stmt->close();

                // Commit transaction
                $conn->commit();
                header("Location: inventory.php?success=Item deleted successfully");
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                $error_message = "Error deleting item: " . $e->getMessage();
            }
        }
    }
}

// Get item ID and type from URL
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tile_type = isset($_GET['type']) ? urldecode($_GET['type']) : '';

if ($item_id <= 0 || empty($tile_type)) {
    die("Invalid item ID or type.");
}

// Define table and variation table based on tile type
$tables = [
    "Nylon Tiles" => ["table" => "nylon_tiles", "variation_table" => "nylon_tiles_variations"],
    "Polypropylene Tiles" => ["table" => "polypropylene_tiles", "variation_table" => "polypropylene_tiles_variations"],
    "Colordot Collection" => ["table" => "colordot_collections", "variation_table" => "colordot_collections_variations"],
    "Luxury Vinyl Tiles" => ["table" => "luxury_vinyl", "variation_table" => "luxury_vinyl_variations"],
    "Broadloom" => ["table" => "broadloom", "variation_table" => "broadloom_variations"]
];

if (!isset($tables[$tile_type])) {
    die("Invalid tile type.");
}

$table = $tables[$tile_type]['table'];
$variation_table = $tables[$tile_type]['variation_table'];

// Fetch item details
$stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();

if (!$item) {
    die("Item not found.");
}

// Fetch variations
$stmt = $conn->prepare("SELECT variation_name, variation_photo FROM `$variation_table` WHERE item_id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$variations_result = $stmt->get_result();
$variations = [];
while ($row = $variations_result->fetch_assoc()) {
    $variations[] = $row;
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details | SMJ Furnishings</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .item-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }
        .variation-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .variation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .variation-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        .header-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .right-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-10">

            <?php if (isset($error_message)): ?>
                <p class="text-danger"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <h1 class="fw-bold">Item Details: <?= htmlspecialchars($item['style_name']) ?></h1>
            <div class="header-buttons mb-5">
                <a href="inventory.php" class="btn btn-secondary mt-5">Back to Inventory</a>
                <div class="right-buttons mt-5">
                    <a href="edit_item.php?id=<?= $item_id ?>&type=<?= urlencode($tile_type) ?>" class="btn btn-primary">Edit</a>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                </div>
            </div>
            <div class="row">
                <!-- Item Image -->
                <div class="col-md-6">
                    <?php
                    $photo = !empty($item['photo']) && file_exists("Uploads/products/" . $item['photo'])
                        ? $item['photo']
                        : 'placeholder.jpg';
                    ?>
                    <img src="Uploads/products/<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($item['style_name']) ?>" class="item-image">
                    <!-- Debugging output (remove in production) -->
                    <p class="text-muted small">
                        Image Path: Uploads/products/<?= htmlspecialchars($photo) ?><br>
                        File Exists: <?= file_exists("Uploads/products/" . $photo) ? 'Yes' : 'No' ?>
                    </p>
                </div>

                <!-- Item Details -->
                <div class="col-md-6">
                    <h3 class="fw-bold mb-4"><?= htmlspecialchars($tile_type) ?></h3>
                    <div class="mb-3">
                        <span class="detail-label">Style Name:</span> <?= htmlspecialchars($item['style_name']) ?>
                    </div>
                    <?php if ($tile_type == "Nylon Tiles" || $tile_type == "Polypropylene Tiles" || $tile_type == "Colordot Collection"): ?>
                        <div class="mb-3">
                            <span class="detail-label">Construction:</span> <?= htmlspecialchars($item['construction'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Yarn System:</span> <?= htmlspecialchars($item['yarn_system'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Dye Method:</span> <?= htmlspecialchars($item['dye_method'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Backing:</span> <?= htmlspecialchars($item['backing'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Size:</span> <?= htmlspecialchars($item['size'] ?? 'N/A') ?>
                        </div>
                    <?php elseif ($tile_type == "Luxury Vinyl Tiles"): ?>
                        <div class="mb-3">
                            <span class="detail-label">Overall Gauge:</span> <?= htmlspecialchars($item['overall_gauge'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Wear Layer:</span> <?= htmlspecialchars($item['wear_layer'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Finish:</span> <?= htmlspecialchars($item['finish'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Size:</span> <?= htmlspecialchars($item['size'] ?? 'N/A') ?>
                        </div>
                    <?php elseif ($tile_type == "Broadloom"): ?>
                        <div class="mb-3">
                            <span class="detail-label">Construction:</span> <?= htmlspecialchars($item['construction'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Yarn System:</span> <?= htmlspecialchars($item['yarn_system'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Dye Method:</span> <?= htmlspecialchars($item['dye_method'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Backing:</span> <?= htmlspecialchars($item['backing'] ?? 'N/A') ?>
                        </div>
                        <div class="mb-3">
                            <span class="detail-label">Width:</span> <?= htmlspecialchars($item['width'] ?? 'N/A') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Variations Section -->
            <?php if (!empty($variations)): ?>
                <h3 class="fw-bold mt-5 mb-4">Variations</h3>
                <div class="row g-4">
                    <?php foreach ($variations as $variation): ?>
                        <?php
                        $variation_photo = !empty($variation['variation_photo']) && file_exists("Uploads/variations/" . $variation['variation_photo'])
                            ? $variation['variation_photo']
                            : 'placeholder.jpg';
                        ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="variation-card">
                                <img src="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" alt="<?= htmlspecialchars($variation['variation_name']) ?>" class="card-img-top">
                                <div class="text-center my-2">
                                    <h5 class="card-title"><?= htmlspecialchars($variation['variation_name']) ?></h5>
                                </div>
                            </div>
                            <!-- Debugging output (remove in production) -->
                            <p class="text-muted small">
                                Variation Image Path: Uploads/variations/<?= htmlspecialchars($variation_photo) ?><br>
                                File Exists: <?= file_exists("Uploads/variations/" . $variation_photo) ? 'Yes' : 'No' ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item and its variations? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="admin_itemdetails.php">
                        <input type="hidden" name="item_id" value="<?= $item_id ?>">
                        <input type="hidden" name="tile_type" value="<?= htmlspecialchars($tile_type) ?>">
                        <button type="submit" name="delete_item" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>