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

// Handle search and tile type filter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$selected_tile_type = isset($_GET['tile_type']) && $_GET['tile_type'] !== 'all' ? $_GET['tile_type'] : null;

// Modify queries based on search and filter
$filtered_tile_types = $selected_tile_type ? [$selected_tile_type => $tile_types[$selected_tile_type]] : $tile_types;

// Check if the request is for JSON data
if (isset($_GET['format']) && $_GET['format'] === 'json') {
    $inventory = [];
    foreach ($filtered_tile_types as $tile_type => $query) {
        // Add search condition if search query exists
        if ($search_query) {
            $query .= " WHERE style_name LIKE '%" . $conn->real_escape_string($search_query) . "%'";
        }
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
        .search-bar {
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold">Inventory</h1>
                <button class="btn btn-success" onclick="window.location.href='add_items.php'">Add new item</button>
            </div>
            <!-- Search and Filter Form -->
            <div class="mb-4">
                <form method="GET" class="d-flex gap-3 align-items-center" id="search-form">
                    <div class="search-bar flex-grow-1">
                        <label for="tile" class="form-label">Search Tile</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by style name..." value="<?= htmlspecialchars($search_query) ?>" id="search-input">
                    </div>
                    <div>
                        <label for="filter" class="form-label">Filter</label>
                        <select name="tile_type" class="form-select" id="tile-type-select">
                            <option value="all" <?= !$selected_tile_type ? 'selected' : '' ?>>All Tile Types</option>
                            <?php foreach ($tile_types as $tile_type => $query): ?>
                                <option value="<?= htmlspecialchars($tile_type) ?>" <?= $selected_tile_type === $tile_type ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tile_type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <?php foreach ($filtered_tile_types as $tile_type => $query): ?>
                <?php
                // Add search condition to the query if search term exists
                $modified_query = $query;
                if ($search_query) {
                    $modified_query .= " WHERE style_name LIKE '%" . $conn->real_escape_string($search_query) . "%'";
                }
                $result = $conn->query($modified_query);
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
    <script>
        // Auto-submit form on input change with debounce
        let debounceTimeout;
        const form = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const tileTypeSelect = document.getElementById('tile-type-select');

        function submitForm() {
            clearTimeout(debounceTimeout);
            // Only submit if search input is empty or has 2 or more characters
            const searchValue = searchInput.value.trim();
            if (searchValue.length === 0 || searchValue.length >= 2) {
                debounceTimeout = setTimeout(() => {
                    form.submit();
                }, 500); // 500ms debounce
            }
        }

        searchInput.addEventListener('input', submitForm);
        tileTypeSelect.addEventListener('change', () => {
            clearTimeout(debounceTimeout);
            form.submit(); // Immediate submit on tile type change
        });

        // Maintain focus and cursor position on search input after submission
        window.addEventListener('load', () => {
            if (searchInput.value.trim().length > 0) {
                searchInput.focus();
                // Set cursor to the end of the input value
                const valueLength = searchInput.value.length;
                searchInput.setSelectionRange(valueLength, valueLength);
            }
        });
    </script>
</body>
</html>