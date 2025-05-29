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
$stmt = $conn->prepare("SELECT id, variation_name, variation_photo FROM `$variation_table` WHERE item_id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$variations_result = $stmt->get_result();
$variations = [];
while ($row = $variations_result->fetch_assoc()) {
    $variations[] = $row;
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $style_name = $_POST['style_name'] ?? '';
    $construction = $_POST['construction'] ?? null;
    $yarn_system = $_POST['yarn_system'] ?? null;
    $dye_method = $_POST['dye_method'] ?? null;
    $backing = $_POST['backing'] ?? null;
    $size = $_POST['size'] ?? null;
    $overall_gauge = $_POST['overall_gauge'] ?? null;
    $wear_layer = $_POST['wear_layer'] ?? null;
    $finish = $_POST['finish'] ?? null;
    $width = $_POST['width'] ?? null;
    $in_stock = isset($_POST['in_stock']) ? 1 : 0;
    $on_sale = isset($_POST['on_sale']) ? 1 : 0;

    // Initialize variables
    $error_message = '';
    $success_message = '';
    $uploadDir = 'Uploads/products/';
    $variation_uploadDir = 'Uploads/variations/';
    $spec_uploadDir = 'Uploads/specs/';
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
    $allowed_spec_types = ['application/pdf', 'image/png', 'image/jpeg'];
    $max_file_size = 5 * 1024 * 1024; // 5MB

    // Create upload directories if they don't exist
    foreach ([$uploadDir, $variation_uploadDir, $spec_uploadDir] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    // Handle main photo upload
    $photo_name = $item['photo'];
    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_type = mime_content_type($_FILES['photo']['tmp_name']);
        if (!in_array($file_type, $allowed_image_types)) {
            $error_message = "Invalid photo type. Only JPEG, PNG, or GIF allowed.";
        } elseif ($_FILES['photo']['size'] > $max_file_size) {
            $error_message = "Photo size exceeds 5MB limit.";
        } else {
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo_name = uniqid() . '.' . $extension;
            $photo_path = $uploadDir . $photo_name;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
                $error_message = "Failed to upload photo.";
                $photo_name = $item['photo'];
            }
        }
    }

    // Handle specification file upload
    $item_fullspecs = $item['item_fullspecs'];
    if (!empty($_FILES['item_fullspecs']['name']) && $_FILES['item_fullspecs']['error'] === UPLOAD_ERR_OK) {
        $file_type = mime_content_type($_FILES['item_fullspecs']['tmp_name']);
        if (!in_array($file_type, $allowed_spec_types)) {
            $error_message = "Invalid specification file type. Only PDF, PNG, or JPEG allowed.";
        } elseif ($_FILES['item_fullspecs']['size'] > $max_file_size) {
            $error_message = "Specification file size exceeds 5MB limit.";
        } else {
            $item_fullspecs = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($_FILES['item_fullspecs']['name']));
            $file_path = $spec_uploadDir . $item_fullspecs;
            if (!move_uploaded_file($_FILES['item_fullspecs']['tmp_name'], $file_path)) {
                $error_message = "Failed to upload specification file.";
                $item_fullspecs = $item['item_fullspecs'];
            }
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update main item
        if (in_array($tile_type, ["Nylon Tiles", "Polypropylene Tiles", "Colordot Collection"])) {
            $stmt = $conn->prepare("UPDATE `$table` SET style_name = ?, construction = ?, yarn_system = ?, dye_method = ?, backing = ?, size = ?, photo = ?, item_fullspecs = ?, in_stock = ?, on_sale = ? WHERE id = ?");
            $stmt->bind_param("ssssssssiii", $style_name, $construction, $yarn_system, $dye_method, $backing, $size, $photo_name, $item_fullspecs, $in_stock, $on_sale, $item_id);
        } elseif ($tile_type == "Luxury Vinyl Tiles") {
            $stmt = $conn->prepare("UPDATE `$table` SET style_name = ?, overall_gauge = ?, wear_layer = ?, finish = ?, size = ?, photo = ?, item_fullspecs = ?, in_stock = ?, on_sale = ? WHERE id = ?");
            $stmt->bind_param("sssssssiii", $style_name, $overall_gauge, $wear_layer, $finish, $size, $photo_name, $item_fullspecs, $in_stock, $on_sale, $item_id);
        } elseif ($tile_type == "Broadloom") {
            $stmt = $conn->prepare("UPDATE `$table` SET style_name = ?, construction = ?, yarn_system = ?, dye_method = ?, backing = ?, width = ?, photo = ?, item_fullspecs = ?, in_stock = ?, on_sale = ? WHERE id = ?");
            $stmt->bind_param("ssssssssiii", $style_name, $construction, $yarn_system, $dye_method, $backing, $width, $photo_name, $item_fullspecs, $in_stock, $on_sale, $item_id);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to update item: " . $stmt->error);
        }
        $stmt->close();

        // Handle variations
        $variation_ids = $_POST['variation_id'] ?? [];
        $variation_names = $_POST['variation_name'] ?? [];
        $variation_photos = $_FILES['variation_photo'] ?? [];
        $delete_variations = $_POST['delete_variation'] ?? [];

        // Delete variations and their photos
        if (!empty($delete_variations)) {
            $placeholders = implode(',', array_fill(0, count($delete_variations), '?'));
            // Fetch photos to delete
            $stmt = $conn->prepare("SELECT variation_photo FROM `$variation_table` WHERE id IN ($placeholders) AND item_id = ?");
            $stmt->bind_param(str_repeat('i', count($delete_variations)) . 'i', ...array_merge($delete_variations, [$item_id]));
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if ($row['variation_photo'] && file_exists($variation_uploadDir . $row['variation_photo'])) {
                    unlink($variation_uploadDir . $row['variation_photo']);
                }
            }
            $stmt->close();

            // Delete variations
            $stmt = $conn->prepare("DELETE FROM `$variation_table` WHERE id IN ($placeholders) AND item_id = ?");
            $stmt->bind_param(str_repeat('i', count($delete_variations)) . 'i', ...array_merge($delete_variations, [$item_id]));
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete variations: " . $stmt->error);
            }
            $stmt->close();
        }

        // Update existing variations
        foreach ($variation_ids as $index => $var_id) {
            if (in_array($var_id, $delete_variations)) {
                continue; // Skip if marked for deletion
            }

            $var_name = $variation_names[$index] ?? '';
            $var_photo = null;

            if (!empty($variation_photos['name'][$index]) && $variation_photos['error'][$index] === UPLOAD_ERR_OK) {
                $file_type = mime_content_type($variation_photos['tmp_name'][$index]);
                if (!in_array($file_type, $allowed_image_types)) {
                    $error_message = "Invalid variation photo type for variation $var_name. Only JPEG, PNG, or GIF allowed.";
                    continue;
                } elseif ($variation_photos['size'][$index] > $max_file_size) {
                    $error_message = "Variation photo size for $var_name exceeds 5MB limit.";
                    continue;
                }

                $extension = pathinfo($variation_photos['name'][$index], PATHINFO_EXTENSION);
                $var_photo = uniqid() . '.' . $extension;
                $var_path = $variation_uploadDir . $var_photo;
                if (!move_uploaded_file($variation_photos['tmp_name'][$index], $var_path)) {
                    $error_message = "Failed to upload variation photo for $var_name.";
                    continue;
                }
            }

            // Update variation
            $query = $var_photo
                ? "UPDATE `$variation_table` SET variation_name = ?, variation_photo = ? WHERE id = ? AND item_id = ?"
                : "UPDATE `$variation_table` SET variation_name = ? WHERE id = ? AND item_id = ?";
            $stmt = $conn->prepare($query);
            if ($var_photo) {
                $stmt->bind_param("ssii", $var_name, $var_photo, $var_id, $item_id);
            } else {
                $stmt->bind_param("sii", $var_name, $var_id, $item_id);
            }
            if (!$stmt->execute()) {
                throw new Exception("Failed to update variation ID $var_id: " . $stmt->error);
            }
            $stmt->close();
        }

        // Add new variations
        if (!empty($_POST['new_variation_name'])) {
            foreach ($_POST['new_variation_name'] as $index => $new_var_name) {
                $new_var_photo = null;
                $photo_index = $index;

                if (!empty($variation_photos['name'][$photo_index]) && $variation_photos['error'][$photo_index] === UPLOAD_ERR_OK) {
                    $file_type = mime_content_type($variation_photos['tmp_name'][$photo_index]);
                    if (!in_array($file_type, $allowed_image_types)) {
                        $error_message = "Invalid photo type for new variation $new_var_name. Only JPEG, PNG, or GIF allowed.";
                        continue;
                    } elseif ($variation_photos['size'][$photo_index] > $max_file_size) {
                        $error_message = "Photo size for new variation $new_var_name exceeds 5MB limit.";
                        continue;
                    }

                    $extension = pathinfo($variation_photos['name'][$photo_index], PATHINFO_EXTENSION);
                    $new_var_photo = uniqid() . '.' . $extension;
                    $new_var_path = $variation_uploadDir . $new_var_photo;
                    if (!move_uploaded_file($variation_photos['tmp_name'][$photo_index], $new_var_path)) {
                        $error_message = "Failed to upload photo for new variation $new_var_name.";
                        continue;
                    }
                }

                $stmt = $conn->prepare("INSERT INTO `$variation_table` (item_id, variation_name, variation_photo) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $item_id, $new_var_name, $new_var_photo);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to add new variation $new_var_name: " . $stmt->error);
                }
                $stmt->close();
            }
        }

        // Commit transaction
        $conn->commit();
        $success_message = "Item and variations updated successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Error: " . $e->getMessage();
        // In production, log the error instead of displaying it
        // error_log($e->getMessage());
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item | SMJ Furnishings</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .variation-group {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .variation-group.disabled {
            opacity: 0.5;
            text-decoration: line-through;
        }
        .delete-variation-btn {
            font-size: 1.2rem;
            line-height: 1;
            padding: 0.25rem 0.5rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateFormFields() {
            const tileType = document.getElementById("tile_type").value;
            const variationContainer = document.getElementById("variation_container");

            // Disable and hide all form sections
            document.querySelectorAll(".form-section").forEach(section => {
                section.style.display = "none";
                section.querySelectorAll("input").forEach(input => input.disabled = true);
            });

            // Enable and show relevant section
            const sections = {
                "Nylon Tiles": "nylon_tiles_fields",
                "Polypropylene Tiles": "polypropylene_tiles_fields",
                "Colordot Collection": "colordot_collections_fields",
                "Luxury Vinyl Tiles": "luxury_vinyl_fields",
                "Broadloom": "broadloom_fields"
            };

            if (sections[tileType]) {
                const section = document.getElementById(sections[tileType]);
                section.style.display = "block";
                section.querySelectorAll("input").forEach(input => input.disabled = false);
                variationContainer.style.display = "block";
            } else {
                variationContainer.style.display = "none";
            }
        }

        function addVariationField() {
            const variationContainer = document.getElementById("new_variation_container");
            const index = variationContainer.children.length;
            const variationGroup = document.createElement("div");
            variationGroup.classList.add("mb-3", "variation-group", "new-variation");
            variationGroup.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <label for="new_variation_name_${index}" class="form-label">Variation Name</label>
                        <input type="text" name="new_variation_name[]" class="form-control" placeholder="Enter variation name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="variation_photo_${index}" class="form-label">Variation Photo</label>
                        <input type="file" name="variation_photo[]" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Optional: Upload a photo for this variation.</small>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeVariationField(this)">Remove</button>
            `;
            variationContainer.appendChild(variationGroup);
        }

        function removeVariationField(button) {
            button.parentElement.remove();
        }

        function toggleDeleteVariation(button, variationId, index) {
            const variationGroup = button.closest('.variation-group');
            const inputs = variationGroup.querySelectorAll('input:not([type="hidden"])');
            const deleteInput = variationGroup.querySelector('input[name="delete_variation[]"]');

            if (deleteInput.disabled) {
                // Undo deletion
                deleteInput.disabled = false;
                variationGroup.classList.remove('disabled');
                inputs.forEach(input => input.disabled = false);
                button.innerHTML = '<i class="fas fa-times"></i>';
                button.title = 'Delete this variation';
            } else {
                // Mark for deletion
                deleteInput.disabled = false; // Enable the hidden input to submit the ID
                variationGroup.classList.add('disabled');
                inputs.forEach(input => input.disabled = true);
                button.innerHTML = '<i class="fas fa-undo"></i>';
                button.title = 'Undo delete';
            }
        }

        // Initialize form fields on page load
        document.addEventListener('DOMContentLoaded', updateFormFields);
    </script>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-9 ms-auto">
            <h1 class="mb-5 fw-bold">Edit Item</h1>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" role="alert"><?= htmlspecialchars($success_message) ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            <form id="editItemForm" method="POST" action="edit_item.php?id=<?= $item_id ?>&type=<?= urlencode($tile_type) ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="tile_type" class="form-label">Tile Type</label>
                    <select id="tile_type" name="tile_type" class="form-control" onchange="updateFormFields()" disabled>
                        <option value="<?= htmlspecialchars($tile_type) ?>" selected><?= htmlspecialchars($tile_type) ?></option>
                    </select>
                </div>

                <!-- Common Fields -->
                <div class="mb-3">
                    <label for="style_name" class="form-label">Style Name</label>
                    <input type="text" id="style_name" name="style_name" class="form-control" value="<?= htmlspecialchars($item['style_name']) ?>" required>
                </div>

                <!-- Stock and Sale Status -->
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" id="in_stock" name="in_stock" class="form-check-input" <?= $item['in_stock'] ? 'checked' : '' ?>>
                        <label for="in_stock" class="form-check-label">In Stock</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" id="on_sale" name="on_sale" class="form-check-input" <?= $item['on_sale'] ? 'checked' : '' ?>>
                        <label for="on_sale" class="form-check-label">On Sale</label>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Current photo: <?= htmlspecialchars($item['photo'] ?: 'None') ?>. Leave blank to keep existing.</small>
                </div>

                <!-- File Upload -->
                <div class="mb-3">
                    <label for="item_fullspecs" class="form-label">Item Full Specifications (PDF, PNG, JPEG)</label>
                    <input type="file" id="item_fullspecs" name="item_fullspecs" class="form-control" accept=".pdf,image/png,image/jpeg">
                    <small class="form-text text-muted">Current File: <?= htmlspecialchars($item['item_fullspecs'] ?: 'None') ?>. Leave blank to keep existing.</small>
                </div>

                <!-- Variations Section -->
                <div id="variation_container">
                    <h5 class="fw-bold">Variations</h5>
                    <!-- Existing Variations -->
                    <?php foreach ($variations as $index => $variation): ?>
                        <div class="variation-group mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="variation_name_<?= $index ?>" class="form-label">Variation Name</label>
                                    <input type="text" name="variation_name[]" class="form-control" value="<?= htmlspecialchars($variation['variation_name']) ?>" required>
                                    <input type="hidden" name="variation_id[]" value="<?= $variation['id'] ?>">
                                    <input type="hidden" name="delete_variation[]" value="<?= $variation['id'] ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="variation_photo_<?= $index ?>" class="form-label">Variation Photo</label>
                                    <input type="file" name="variation_photo[]" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Current photo: <?= htmlspecialchars($variation['variation_photo'] ?: 'None') ?>. Leave blank to keep existing.</small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm delete-variation-btn" onclick="toggleDeleteVariation(this, <?= $variation['id'] ?>, <?= $index ?>)" title="Delete this variation">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- New Variations -->
                    <div id="new_variation_container"></div>
                    <button type="button" class="btn btn-success btn-sm mb-3" onclick="addVariationField()">Add Variation</button>
                </div>

                <!-- Nylon Tiles Fields -->
                <div id="nylon_tiles_fields" class="form-section" style="display: <?= in_array($tile_type, ["Nylon Tiles"]) ? 'block' : 'none' ?>;">
                    <div class="mb-3">
                        <label for="construction_nylon" class="form-label">Construction</label>
                        <input type="text" id="construction_nylon" name="construction" class="form-control" value="<?= htmlspecialchars($item['construction'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_nylon" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_nylon" name="yarn_system" class="form-control" value="<?= htmlspecialchars($item['yarn_system'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_nylon" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_nylon" name="dye_method" class="form-control" value="<?= htmlspecialchars($item['dye_method'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="backing_nylon" class="form-label">Backing</label>
                        <input type="text" id="backing_nylon" name="backing" class="form-control" value="<?= htmlspecialchars($item['backing'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="size_nylon" class="form-label">Size</label>
                        <input type="text" id="size_nylon" name="size" class="form-control" value="<?= htmlspecialchars($item['size'] ?? '') ?>">
                    </div>
                </div>

                <!-- Polypropylene Tiles Fields -->
                <div id="polypropylene_tiles_fields" class="form-section" style="display: <?= in_array($tile_type, ["Polypropylene Tiles"]) ? 'block' : 'none' ?>;">
                    <div class="mb-3">
                        <label for="construction_poly" class="form-label">Construction</label>
                        <input type="text" id="construction_poly" name="construction" class="form-control" value="<?= htmlspecialchars($item['construction'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_poly" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_poly" name="yarn_system" class="form-control" value="<?= htmlspecialchars($item['yarn_system'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_poly" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_poly" name="dye_method" class="form-control" value="<?= htmlspecialchars($item['dye_method'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="backing_poly" class="form-label">Backing</label>
                        <input type="text" id="backing_poly" name="backing" class="form-control" value="<?= htmlspecialchars($item['backing'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="size_poly" class="form-label">Size</label>
                        <input type="text" id="size_poly" name="size" class="form-control" value="<?= htmlspecialchars($item['size'] ?? '') ?>">
                    </div>
                </div>

                <!-- Colordot Collection Fields -->
                <div id="colordot_collections_fields" class="form-section" style="display: <?= in_array($tile_type, ["Colordot Collection"]) ? 'block' : 'none' ?>;">
                    <div class="mb-3">
                        <label for="construction_colordot" class="form-label">Construction</label>
                        <input type="text" id="construction_colordot" name="construction" class="form-control" value="<?= htmlspecialchars($item['construction'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_colordot" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_colordot" name="yarn_system" class="form-control" value="<?= htmlspecialchars($item['yarn_system'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_colordot" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_colordot" name="dye_method" class="form-control" value="<?= htmlspecialchars($item['dye_method'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="backing_colordot" class="form-label">Backing</label>
                        <input type="text" id="backing_colordot" name="backing" class="form-control" value="<?= htmlspecialchars($item['backing'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="size_colordot" class="form-label">Size</label>
                        <input type="text" id="size_colordot" name="size" class="form-control" value="<?= htmlspecialchars($item['size'] ?? '') ?>">
                    </div>
                </div>

                <!-- Luxury Vinyl Tiles Fields -->
                <div id="luxury_vinyl_fields" class="form-section" style="display: <?= in_array($tile_type, ["Luxury Vinyl Tiles"]) ? 'block' : 'none' ?>;">
                    <div class="mb-3">
                        <label for="overall_gauge" class="form-label">Overall Gauge</label>
                        <input type="text" id="overall_gauge" name="overall_gauge" class="form-control" value="<?= htmlspecialchars($item['overall_gauge'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="wear_layer" class="form-label">Wear Layer</label>
                        <input type="text" id="wear_layer" name="wear_layer" class="form-control" value="<?= htmlspecialchars($item['wear_layer'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="finish" class="form-label">Finish</label>
                        <input type="text" id="finish" name="finish" class="form-control" value="<?= htmlspecialchars($item['finish'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="size_vinyl" class="form-label">Size</label>
                        <input type="text" id="size_vinyl" name="size" class="form-control" value="<?= htmlspecialchars($item['size'] ?? '') ?>">
                    </div>
                </div>

                <!-- Broadloom Fields -->
                <div id="broadloom_fields" class="form-section" style="display: <?= in_array($tile_type, ["Broadloom"]) ? 'block' : 'none' ?>;">
                    <div class="mb-3">
                        <label for="construction_broadloom" class="form-label">Construction</label>
                        <input type="text" id="construction_broadloom" name="construction" class="form-control" value="<?= htmlspecialchars($item['construction'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_broadloom" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_broadloom" name="yarn_system" class="form-control" value="<?= htmlspecialchars($item['yarn_system'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_broadloom" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_broadloom" name="dye_method" class="form-control" value="<?= htmlspecialchars($item['dye_method'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="backing_broadloom" class="form-label">Backing</label>
                        <input type="text" id="backing_broadloom" name="backing" class="form-control" value="<?= htmlspecialchars($item['backing'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="width" class="form-label">Width</label>
                        <input type="text" id="width" name="width" class="form-control" value="<?= htmlspecialchars($item['width'] ?? '') ?>">
                    </div>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">Save Changes</button>
                <a href="admin_itemdetails.php?id=<?= $item_id ?>&type=<?= urlencode($tile_type) ?>" class="btn btn-secondary">Cancel</a>
            </form>

            <!-- Confirmation Modal -->
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Changes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to save changes to this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmSaveButton">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('editItemForm');
        const confirmSaveButton = document.getElementById('confirmSaveButton');

        confirmSaveButton.addEventListener('click', function () {
            form.submit();
        });
    </script>
</body>
</html>