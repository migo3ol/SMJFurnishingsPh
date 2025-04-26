<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tile_type = $_POST['tile_type'];
    $style_name = $_POST['style_name'];

    // Insert the main item into the appropriate table
    if ($tile_type == "Nylon Tiles") {
        $stmt = $conn->prepare("INSERT INTO nylon_tiles (style_name, construction, yarn_system, dye_method, backing, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size']);
        $variation_table = "nylon_tiles_variations";
    } elseif ($tile_type == "Polypropylene Tiles") {
        $stmt = $conn->prepare("INSERT INTO polypropylene_tiles (style_name, construction, yarn_system, dye_method, backing, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size']);
        $variation_table = "polypropylene_tiles_variations";
    } elseif ($tile_type == "Colordot Collection") {
        $stmt = $conn->prepare("INSERT INTO colordot_collections (style_name, construction, yarn_system, dye_method, backing, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size']);
        $variation_table = "colordot_collections_variations";
    } elseif ($tile_type == "Luxury Vinyl Tiles") {
        $stmt = $conn->prepare("INSERT INTO luxury_vinyl (style_name, overall_gauge, wear_layer, finish, size) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $style_name, $_POST['overall_gauge'], $_POST['wear_layer'], $_POST['finish'], $_POST['size']);
        $variation_table = "luxury_vinyl_variations";
    } elseif ($tile_type == "Broadloom") {
        $stmt = $conn->prepare("INSERT INTO broadloom (style_name, construction, yarn_system, dye_method, backing, width) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['width']);
        $variation_table = "broadloom_variations";
    }

    if ($stmt->execute()) {
        $item_id = $stmt->insert_id; // Get the ID of the inserted item

        // Handle variations
        if (!empty($_POST['variation_name'])) {
            $upload_dir = 'uploads/variations/';

            // Check if the directory exists, if not, create it
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create the directory with write permissions
            }

            foreach ($_POST['variation_name'] as $index => $variation_name) {
                $variation_photo = $_FILES['variation_photo']['name'][$index];
                $variation_photo_tmp = $_FILES['variation_photo']['tmp_name'][$index];
                $variation_photo_path = $upload_dir . basename($variation_photo);

                // Move uploaded file to the server
                if (move_uploaded_file($variation_photo_tmp, $variation_photo_path)) {
                    // Insert into the appropriate variation table
                    $stmt_variation = $conn->prepare("INSERT INTO $variation_table (item_id, variation_name, variation_photo) VALUES (?, ?, ?)");
                    $stmt_variation->bind_param("iss", $item_id, $variation_name, $variation_photo_path);
                    $stmt_variation->execute();
                } else {
                    echo "Failed to upload file: " . htmlspecialchars($variation_photo) . "<br>";
                }
            }
        }

        $success_message = "Item and variations added successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item | SMJ Furnishings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updateFormFields() {
            const tileType = document.getElementById("tile_type").value;
            const variationContainer = document.getElementById("variation_container");

            // Hide all form sections initially
            document.querySelectorAll(".form-section").forEach(section => section.style.display = "none");

            // Show the appropriate form section based on the selected tile type
            if (tileType === "Nylon Tiles") {
                document.getElementById("nylon_tiles_fields").style.display = "block";
                variationContainer.style.display = "block"; // Show variations
            } else if (tileType === "Polypropylene Tiles") {
                document.getElementById("polypropylene_tiles_fields").style.display = "block";
                variationContainer.style.display = "block"; // Show variations
            } else if (tileType === "Colordot Collection") {
                document.getElementById("colordot_collections_fields").style.display = "block";
                variationContainer.style.display = "block"; // Show variations
            } else if (tileType === "Luxury Vinyl Tiles") {
                document.getElementById("luxury_vinyl_fields").style.display = "block";
                variationContainer.style.display = "block"; // Show variations
            } else if (tileType === "Broadloom") {
                document.getElementById("broadloom_fields").style.display = "block";
                variationContainer.style.display = "block"; // Show variations
            } else {
                variationContainer.style.display = "none"; // Hide variations if no tile type is selected
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
                <p class="text-success"><?= $success_message ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="text-danger"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="POST" action="add_items.php" enctype="multipart/form-data">
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
                        <label for="construction" class="form-label">Construction</label>
                        <input type="text" id="construction" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing" class="form-label">Backing</label>
                        <input type="text" id="backing" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <input type="text" id="size" name="size" class="form-control">
                    </div>
                </div>

                <!-- Polypropylene Tiles Fields -->
                <div id="polypropylene_tiles_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction" class="form-label">Construction</label>
                        <input type="text" id="construction" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing" class="form-label">Backing</label>
                        <input type="text" id="backing" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <input type="text" id="size" name="size" class="form-control">
                    </div>
                </div>

                <!-- Colordot Collection Fields -->
                <div id="colordot_collections_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction" class="form-label">Construction</label>
                        <input type="text" id="construction" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing" class="form-label">Backing</label>
                        <input type="text" id="backing" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <input type="text" id="size" name="size" class="form-control">
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
                        <label for="size" class="form-label">Size</label>
                        <input type="text" id="size" name="size" class="form-control">
                    </div>
                </div>

                <!-- Broadloom Fields -->
                <div id="broadloom_fields" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label for="construction" class="form-label">Construction</label>
                        <input type="text" id="construction" name="construction" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="yarn_system" class="form-label">Yarn System</label>
                        <input type="text" id="yarn_system" name="yarn_system" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="dye_method" class="form-label">Dye Method</label>
                        <input type="text" id="dye_method" name="dye_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="backing" class="form-label">Backing</label>
                        <input type="text" id="backing" name="backing" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="width" class="form-label">Width</label>
                        <input type="text" id="width" name="width" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Item</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='inventory.php'">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>


