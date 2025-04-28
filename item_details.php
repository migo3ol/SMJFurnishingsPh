<?php
include 'database.php';

// Get item ID and type from URL
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tile_type = isset($_GET['type']) ? urldecode($_GET['type']) : '';

if ($item_id <= 0 || empty($tile_type)) {
    die("Invalid item ID or type.");
}

// Define table and variation table
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

// Fetch related products (same tile_type, exclude current item, limit 4)
$stmt = $conn->prepare("SELECT id, style_name, photo FROM `$table` WHERE id != ? ORDER BY RAND() LIMIT 4");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$related_result = $stmt->get_result();
$related_products = [];
while ($row = $related_result->fetch_assoc()) {
    $related_products[] = $row;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .item-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        /* Modal */
        .modal-content {
            background-color: transparent;
            border: none;
        }
        .modal-image {
            width: 100%;
            max-width: 80vw;
            height: auto;
            border-radius: 8px;
        }
        .close-btn {
            position: absolute;
            top: -15px;
            right: -15px;
            background-color: #333;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .close-btn:hover {
            background-color: #555;
        }
        /* Variations */
        .variations-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: start;
            margin-top: 15px;
        }
        .variation-item {
            text-align: center;
        }
        .variation-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .variation-thumbnail:hover {
            transform: scale(1.1);
            opacity: 0.8;
        }
        .variation-name {
            font-size: 0.85rem;
            margin-top: 5px;
            color: #333;
        }
        /* Related Products */
        .related-products-container {
            margin-top: 50px;
        }
        .related-item {
            text-align: center;
        }
        .related-thumbnail {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .related-thumbnail:hover {
            transform: scale(1.05);
        }
        .related-name {
            font-size: 0.9rem;
            margin-top: 8px;
            color: #333;
            text-decoration: none; /* Remove underline */
            display: block;
            pointer-events: none; /* Disable link behavior */
        }
        .related-name:hover {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mb-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="fw-bold"><?= htmlspecialchars($item['style_name']) ?></h1>
        </div>

        <div class="row">
            <!-- Item Image -->
            <div class="col-md-6">
                <?php
                $photo = !empty($item['photo']) && file_exists("Uploads/products/" . $item['photo'])
                    ? $item['photo']
                    : 'placeholder.jpg';
                ?>
                <img src="Uploads/products/<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($item['style_name']) ?>" class="item-image" id="mainImage" data-bs-toggle="modal" data-bs-target="#imageModal">
            </div>

            <!-- Item Details -->
            <div class="col-md-6">
                <h3 class="fw-bold mb-4"><?= htmlspecialchars($item['style_name']) ?></h3>
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
                    <!-- Variations Section -->
                    <?php if (!empty($variations)): ?>
                        <h5 class="fw-bold mt-4 mb-3">Variations</h5>
                        <div class="variations-container">
                            <?php foreach ($variations as $variation): ?>
                                <?php
                                $variation_photo = !empty($variation['variation_photo']) && file_exists("Uploads/variations/" . $variation['variation_photo'])
                                    ? $variation['variation_photo']
                                    : 'placeholder.jpg';
                                ?>
                                <div class="variation-item">
                                    <img src="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" 
                                         alt="<?= htmlspecialchars($variation['variation_name']) ?>" 
                                         class="variation-thumbnail" 
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal" 
                                         data-image="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" 
                                         data-alt="<?= htmlspecialchars($variation['variation_name']) ?>">
                                    <div class="variation-name"><?= htmlspecialchars($variation['variation_name']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                    <!-- Variations Section -->
                    <?php if (!empty($variations)): ?>
                        <h5 class="fw-bold mt-4 mb-3">Variations</h5>
                        <div class="variations-container">
                            <?php foreach ($variations as $variation): ?>
                                <?php
                                $variation_photo = !empty($variation['variation_photo']) && file_exists("Uploads/variations/" . $variation['variation_photo'])
                                    ? $variation['variation_photo']
                                    : 'placeholder.jpg';
                                ?>
                                <div class="variation-item">
                                    <img src="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" 
                                         alt="<?= htmlspecialchars($variation['variation_name']) ?>" 
                                         class="variation-thumbnail" 
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal" 
                                         data-image="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" 
                                         data-alt="<?= htmlspecialchars($variation['variation_name']) ?>">
                                    <div class="variation-name"><?= htmlspecialchars($variation['variation_name']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                    <!-- Variations Section -->
                    <?php if (!empty($variations)): ?>
                        <h5 class="fw-bold mt-4 mb-3">Variations</h5>
                        <div class="variations-container">
                            <?php foreach ($variations as $variation): ?>
                                <?php
                                $variation_photo = !empty($variation['variation_photo']) && file_exists("Uploads/variations/" . $variation['variation_photo'])
                                    ? $variation['variation_photo']
                                    : 'placeholder.jpg';
                                ?>
                                <div class="variation-item">
                                    <img src="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" 
                                         alt="<?= htmlspecialchars($variation['variation_name']) ?>" 
                                         class="variation-thumbnail" 
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal" 
                                         data-image="Uploads/variations/<?= htmlspecialchars($variation_photo) ?>" 
                                         data-alt="<?= htmlspecialchars($variation['variation_name']) ?>">
                                    <div class="variation-name"><?= htmlspecialchars($variation['variation_name']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products Section -->
        <section class="related-products-container">
            <h2 class="fw-bold mb-5">Related Products</h2>
            <?php if (!empty($related_products)): ?>
                <div class="row">
                    <?php foreach ($related_products as $related_item): ?>
                        <?php
                        $related_photo = !empty($related_item['photo']) && file_exists("Uploads/products/" . $related_item['photo'])
                            ? $related_item['photo']
                            : 'placeholder.jpg';
                        ?>
                        <div class="col-6 col-md-3 mb-4">
                            <div class="related-item">
                        <a href="item_details.php?id=<?= $related_item['id'] ?>&type=<?= urlencode($tile_type) ?>">
                            <img src="Uploads/products/<?= htmlspecialchars($related_photo) ?>" 
                            alt="<?= htmlspecialchars($related_item['style_name']) ?>" 
                            class="related-thumbnail">
                            <div class="related-name"><?= htmlspecialchars($related_item['style_name']) ?></div>
                        </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No related products available.</p>
            <?php endif; ?>
        </section>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <span class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></span>
                <img src="" alt="" class="modal-image" id="modalImage">
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const mainImage = document.getElementById("mainImage");
            const modalImage = document.getElementById("modalImage");
            const thumbnails = document.querySelectorAll(".variation-thumbnail");

            // Update modal image when main image is clicked
            mainImage.addEventListener("click", function () {
                modalImage.src = mainImage.src;
                modalImage.alt = mainImage.alt;
            });

            // Update modal image when variation thumbnail is clicked
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener("click", function () {
                    modalImage.src = this.dataset.image;
                    modalImage.alt = this.dataset.alt;
                });
            });
        });
    </script>
</body>
</html>