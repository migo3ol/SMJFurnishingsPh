<?php
include 'database.php';

// Fetch items grouped by tile types
$tile_types = [
    "Nylon Tiles" => "SELECT id, style_name, photo, in_stock, on_sale FROM nylon_tiles",
    "Polypropylene Tiles" => "SELECT id, style_name, photo, in_stock, on_sale FROM polypropylene_tiles",
    "Colordot Collection" => "SELECT id, style_name, photo, in_stock, on_sale FROM colordot_collections",
    "Luxury Vinyl Tiles" => "SELECT id, style_name, photo, in_stock, on_sale FROM luxury_vinyl",
    "Broadloom" => "SELECT id, style_name, photo, in_stock, on_sale FROM broadloom"
];

// Check if the request is for JSON data
if (isset($_GET['format']) && $_GET['format'] === 'json') {
    $inventory = [];
    foreach ($tile_types as $tile_type => $query) {
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($item = $result->fetch_assoc()) {
                $inventory[$tile_type][] = [
                    'id' => $item['id'],
                    'style_name' => $item['style_name'],
                    'photo' => "Uploads/products/" . $item['photo'],
                    'in_stock' => (bool)$item['in_stock'],
                    'on_sale' => (bool)$item['on_sale']
                ];
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($inventory);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | SMJ Furnishings</title>
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
        .tile-section .item-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .tile-section .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .tile-section .item-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .tile-section .view-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 10px 30px;
            letter-spacing: 2px;
            opacity: 0;
            font-weight: 600;
            transition: opacity 0.3s ease;
            z-index: 3;
        }
        .tile-section .item-card:hover .view-btn {
            opacity: 1;
        }
        .status-text {
            color: red;
            font-size: 0.9rem;
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
                        <h2 class="fw-bold mb-3"><?= $tile_type ?></h2>
                        <div class="row g-4">
                            <?php while ($item = $result->fetch_assoc()): ?>
                                <?php
                                // Use the photo directly, with a fallback to a default image
                                $photo = !empty($item['photo']) && file_exists("Uploads/products/" . $item['photo'])
                                    ? $item['photo']
                                    : 'placeholder.jpg';
                                ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="item-card">
                                        <!-- Product Image -->
                                        <img src="Uploads/products/<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($item['style_name']) ?>" class="card-img-top">
                                        <!-- View Button -->
                                        <a href="admin_itemdetails.php?id=<?= $item['id'] ?>&type=<?= urlencode($tile_type) ?>" class="view-btn">View</a>
                                    </div>
                                    <!-- Style Name and Status -->
                                    <div class="text-center mt-3">
                                        <h5 class="card-title"><?= htmlspecialchars($item['style_name']) ?></h5>
                                        <p class="status-text">
                                            <?= $item['in_stock'] ? 'In Stock' : 'Out of Stock' ?>
                                            <?= $item['on_sale'] ? ' | On Sale' : '' ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>