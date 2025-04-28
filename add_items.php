<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tile_type = $_POST['tile_type'] ?? '';
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

    // Log submitted data for debugging
    error_log("POST Data: " . print_r($_POST, true));

    // Handle the main photo upload
    $uploadDir = 'Uploads/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $photo_name = null;
    if (!empty($_FILES['photo']['name'])) {
        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . $extension; // Store only the file name
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_path = $uploadDir . $photo_name;

        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $error_message = "File upload error: " . $_FILES['photo']['error'];
        } elseif (!move_uploaded_file($photo_tmp, $photo_path)) {
            $error_message = "Failed to move uploaded file to $photo_path";
            $photo_name = null;
        }
    }

    // Insert into the correct table
    try {
        if ($tile_type == "Nylon Tiles") {
            $stmt = $conn->prepare("INSERT INTO nylon_tiles (style_name, construction, yarn_system, dye_method, backing, size, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $style_name, $construction, $yarn_system, $dye_method, $backing, $size, $photo_name);
            $variation_table = "nylon_tiles_variations";
        } elseif ($tile_type == "Polypropylene Tiles") {
            $stmt = $conn->prepare("INSERT INTO polypropylene_tiles (style_name, construction, yarn_system, dye_method, backing, size, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $style_name, $construction, $yarn_system, $dye_method, $backing, $size, $photo_name);
            $variation_table = "polypropylene_tiles_variations";
        } elseif ($tile_type == "Colordot Collection") {
            $stmt = $conn->prepare("INSERT INTO colordot_collections (style_name, construction, yarn_system, dye_method, backing, size, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $style_name, $construction, $yarn_system, $dye_method, $backing, $size, $photo_name);
            $variation_table = "colordot_collections_variations";
        } elseif ($tile_type == "Luxury Vinyl Tiles") {
            $stmt = $conn->prepare("INSERT INTO luxury_vinyl (style_name, overall_gauge, wear_layer, finish, size, photo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $style_name, $overall_gauge, $wear_layer, $finish, $size, $photo_name);
            $variation_table = "luxury_vinyl_variations";
        } elseif ($tile_type == "Broadloom") {
            $stmt = $conn->prepare("INSERT INTO broadloom (style_name, construction, yarn_system, dye_method, backing, width, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $style_name, $construction, $yarn_system, $dye_method, $backing, $width, $photo_name);
            $variation_table = "broadloom_variations";
        } else {
            $error_message = "Invalid tile type selected.";
            exit;
        }

        // Log data before insertion
        error_log("Inserting $tile_type: style_name=$style_name, construction=$construction, yarn_system=$yarn_system, dye_method=$dye_method, backing=$backing, size=$size, photo=$photo_name");

        if ($stmt->execute()) {
            $item_id = $stmt->insert_id;

            // Handle variations
            if (!empty($_POST['variation_name'])) {
                $upload_dir = 'Uploads/variations/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                foreach ($_POST['variation_name'] as $index => $variation_name) {
                    if (!empty($_FILES['variation_photo']['name'][$index])) {
                        $extension = pathinfo($_FILES['variation_photo']['name'][$index], PATHINFO_EXTENSION);
                        $variation_photo = uniqid() . '.' . $extension; // Store only file name
                        $variation_tmp = $_FILES['variation_photo']['tmp_name'][$index];
                        $variation_path = $upload_dir . $variation_photo;

                        if (move_uploaded_file($variation_tmp, $variation_path)) {
                            $stmt_var = $conn->prepare("INSERT INTO $variation_table (item_id, variation_name, variation_photo) VALUES (?, ?, ?)");
                            $stmt_var->bind_param("iss", $item_id, $variation_name, $variation_photo);
                            if (!$stmt_var->execute()) {
                                $error_message = "Variation insert error: " . $stmt_var->error;
                            }
                            $stmt_var->close();
                        } else {
                            $error_message = "Failed to upload variation photo: $variation_photo";
                        }
                    }
                }
            }

            $success_message = "Item and variations added successfully!";
        } else {
            $error_message = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        $error_message = "Exception: " . $e->getMessage();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item | SMJ Furnishings</title>
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateFormFields() {
            const tileType = document.getElementById("tile_type").value;
            const variationContainer = document.getElementById("variation_container");

            // Disable all form sections initially
            document.querySelectorAll(".form-section input").forEach(input => {
                input.disabled = true;
            });

            // Hide all form sections initially
            document.querySelectorAll(".form-section").forEach(section => {
                section.style.display = "none";
            });

            // Enable and show the appropriate form section based on the selected tile type
            if (tileType === "Nylon Tiles") {
                document.getElementById("nylon_tiles_fields").style.display = "block";
                document.querySelectorAll("#nylon_tiles_fields input").forEach(input => {
                    input.disabled = false;
                });
                variationContainer.style.display = "block";
            } else if (tileType === "Polypropylene Tiles") {
                document.getElementById("polypropylene_tiles_fields").style.display = "block";
                document.querySelectorAll("#polypropylene_tiles_fields input").forEach(input => {
                    input.disabled = false;
                });
                variationContainer.style.display = "block";
            } else if (tileType === "Colordot Collection") {
                document.getElementById("colordot_collections_fields").style.display = "block";
                document.querySelectorAll("#colordot_collections_fields input").forEach(input => {
                    input.disabled = false;
                });
                variationContainer.style.display = "block";
            } else if (tileType === "Luxury Vinyl Tiles") {
                document.getElementById("luxury_vinyl_fields").style.display = "block";
                document.querySelectorAll("#luxury_vinyl_fields input").forEach(input => {
                    input.disabled = false;
                });
                variationContainer.style.display = "block";
            } else if (tileType === "Broadloom") {
                document.getElementById("broadloom_fields").style.display = "block";
                document.querySelectorAll("#broadloom_fields input").forEach(input => {
                    input.disabled = false;
                });
                variationContainer.style.display = "block";
            } else {
                variationContainer.style.display = "none";
            }
        }

        function addVariationField() {
            const variationContainer = document.getElementById("variation_container");
            const variationGroup = document.createElement("div");
            variationGroup.classList.add("mb-3", "variation-group");

            variationGroup.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <label for="variation_name[]" class="form-label">Variation Name</label>
                        <input type="text" name="variation_name[]" class="form-control" placeholder="Enter variation name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="variation_photo[]" class="form-label">Variation Photo</label>
                        <input type="file" name="variation_photo[]" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeVariationField(this)">Remove</button>
            `;

            variationContainer.appendChild(variationGroup);
        }

        function removeVariationField(button) {
            button.parentElement.remove();
        }
    </script>
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-9 ms-auto">
            <h1 class="mb-5 fw-bold">Add Item</h1>
            <?php if (isset($success_message)): ?>
                <p class="text-success"><?= htmlspecialchars($success_message) ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="text-danger"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
            <form id="addItemForm" method="POST" action="add_items.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="tile_type" class="form-label">Tile Type</label>
                    <select id="tile_type" name="tile_type" class="form-control" onchange="updateFormFields()" required>
                        <option value="">Select Tile Type</option>
                        <option value="Nylon Tiles">Nylon Tiles</option>
                        <option value="Polypropylene Tiles">Polypropylene Tiles</option>
                        <option value="Colordot Collection">Colordot Collection</option>
                        <option value="Luxury Vinyl Tiles">Luxury Vinyl Tiles</option>
                        <option value="Broadloom">Broadloom</option>
                    </select>
                </div>

                <!-- Common Fields -->
                <div class="mb-3">
                    <label for="style_name" class="form-label">Style Name</label>
                    <input type="text" id="style_name" name="style_name" class="form-control" required>
                </div>

                <!-- Photo Upload -->
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required>
                </div>

                <!-- Variations Section -->
                <div id="variation_container" style="display: none;">
                    <h5 class="fw-bold">Variations</h5>
                    <button type="button" class="btn btn-success btn-sm mb-3" onclick="addVariationField()">Add Variation</button>
                </div>

                <!-- Nylon Tiles Fields -->
                <div id="nylon_tiles_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction_nylon" class="form-label">Construction</label>
                        <input type="text" id="construction_nylon" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_nylon" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_nylon" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_nylon" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_nylon" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing_nylon" class="form-label">Backing</label>
                        <input type="text" id="backing_nylon" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size_nylon" class="form-label">Size</label>
                        <input type="text" id="size_nylon" name="size" class="form-control">
                    </div>
                </div>

                <!-- Polypropylene Tiles Fields -->
                <div id="polypropylene_tiles_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction_poly" class="form-label">Construction</label>
                        <input type="text" id="construction_poly" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_poly" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_poly" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_poly" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_poly" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing_poly" class="form-label">Backing</label>
                        <input type="text" id="backing_poly" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size_poly" class="form-label">Size</label>
                        <input type="text" id="size_poly" name="size" class="form-control">
                    </div>
                </div>

                <!-- Colordot Collection Fields -->
                <div id="colordot_collections_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction_colordot" class="form-label">Construction</label>
                        <input type="text" id="construction_colordot" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_colordot" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_colordot" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_colordot" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_colordot" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing_colordot" class="form-label">Backing</label>
                        <input type="text" id="backing_colordot" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size_colordot" class="form-label">Size</label>
                        <input type="text" id="size_colordot" name="size" class="form-control">
                    </div>
                </div>

                <!-- Luxury Vinyl Tiles Fields -->
                <div id="luxury_vinyl_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="overall_gauge" class="form-label">Overall Gauge</label>
                        <input type="text" id="overall_gauge" name="overall_gauge" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="wear_layer" class="form-label">Wear Layer</label>
                        <input type="text" id="wear_layer" name="wear_layer" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="finish" class="form-label">Finish</label>
                        <input type="text" id="finish" name="finish" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size_vinyl" class="form-label">Size</label>
                        <input type="text" id="size_vinyl" name="size" class="form-control">
                    </div>
                </div>

                <!-- Broadloom Fields -->
                <div id="broadloom_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction_broadloom" class="form-label">Construction</label>
                        <input type="text" id="construction_broadloom" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system_broadloom" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system_broadloom" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method_broadloom" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method_broadloom" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing_broadloom" class="form-label">Backing</label>
                        <input type=" personally identifiable informationacking_broadloom" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="width" class="form-label">Width</label>
                        <input type="text" id="width" name="width" class="form-control">
                    </div>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">Add Item</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='inventory.php'">Cancel</button>
            </form>

            <!-- Modal -->
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to add this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmAddButton">Add new item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('addItemForm');
        const confirmAddButton = document.getElementById('confirmAddButton');

        confirmAddButton.addEventListener('click', function () {
            form.submit();
        });
    </script>
</body>
</html>