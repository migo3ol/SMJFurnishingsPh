<?php
// filepath: c:\xampp\htdocs\SMJFurnishingsPh\add_items.php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tile_type = $_POST['tile_type'];
    $style_name = $_POST['style_name'];

    if ($tile_type == "Nylon Tiles") {
        $stmt = $conn->prepare("INSERT INTO nylon_tiles (style_name, construction, yarn_system, dye_method, backing, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size']);
    } elseif ($tile_type == "Polypropylene Tiles") {
        $stmt = $conn->prepare("INSERT INTO polypropylene_tiles (style_name, construction, yarn_system, dye_method, backing, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size']);
    } elseif ($tile_type == "Colordot Collection") {
        $stmt = $conn->prepare("INSERT INTO colordot_collections (style_name, construction, yarn_system, dye_method, backing, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size']);
    }  elseif ($tile_type == "Luxury Vinyl Tiles") {
        $stmt = $conn->prepare("INSERT INTO luxury_vinyl (style_name, overall_gauge, wear_layer, finish, size) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $style_name, $_POST['overall_gauge'], $_POST['wear_layer'], $_POST['finish'], $_POST['size']);
    } elseif ($tile_type == "Broadloom") {
        $stmt = $conn->prepare("INSERT INTO broadloom (style_name, construction, yarn_system, dye_method, backing, width) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['width']);
    }

    if ($stmt->execute()) {
        $success_message = "Item added successfully!";
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
            document.querySelectorAll(".form-section").forEach(section => section.style.display = "none");
            if (tileType === "Nylon Tiles") {
                document.getElementById("nylon_tiles_fields").style.display = "block";
            } else if (tileType === "Polypropylene Tiles") {
                document.getElementById("polypropylene_tiles_fields").style.display = "block";
            }else if (tileType === "Colordot Collection") {
                document.getElementById("colordot_collections_fields").style.display = "block";
            } else if (tileType === "Luxury Vinyl Tiles") {
                document.getElementById("luxury_vinyl_fields").style.display = "block";
            } else if (tileType === "Broadloom") {
                document.getElementById("broadloom_fields").style.display = "block";
            }
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
            <form method="POST" action="add_items.php">
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
                 <!-- Nylon Tiles Fields -->
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
            </form>
        </div>
    </div>
</body>
</html>