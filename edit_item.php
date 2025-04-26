<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tile_type = $_POST['tile_type'];
    $item_id = $_POST['id'];
    $style_name = $_POST['style_name'];

    // Update the main item in the appropriate table
    $stmt = null;
    $variation_table = null;

    switch ($tile_type) {
        case "Nylon Tiles":
            $stmt = $conn->prepare("UPDATE nylon_tiles SET style_name = ?, construction = ?, yarn_system = ?, dye_method = ?, backing = ?, size = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size'], $item_id);
            $variation_table = "nylon_tiles_variations";
            break;
        case "Polypropylene Tiles":
            $stmt = $conn->prepare("UPDATE polypropylene_tiles SET style_name = ?, construction = ?, yarn_system = ?, dye_method = ?, backing = ?, size = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size'], $item_id);
            $variation_table = "polypropylene_tiles_variations";
            break;
        case "Colordot Collection":
            $stmt = $conn->prepare("UPDATE colordot_collections SET style_name = ?, construction = ?, yarn_system = ?, dye_method = ?, backing = ?, size = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['size'], $item_id);
            $variation_table = "colordot_collections_variations";
            break;
        case "Luxury Vinyl Tiles":
            $stmt = $conn->prepare("UPDATE luxury_vinyl SET style_name = ?, overall_gauge = ?, wear_layer = ?, finish = ?, size = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $style_name, $_POST['overall_gauge'], $_POST['wear_layer'], $_POST['finish'], $_POST['size'], $item_id);
            $variation_table = "luxury_vinyl_variations";
            break;
        case "Broadloom":
            $stmt = $conn->prepare("UPDATE broadloom SET style_name = ?, construction = ?, yarn_system = ?, dye_method = ?, backing = ?, width = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $style_name, $_POST['construction'], $_POST['yarn_system'], $_POST['dye_method'], $_POST['backing'], $_POST['width'], $item_id);
            $variation_table = "broadloom_variations";
            break;
    }

    if ($stmt && $stmt->execute()) {
        // Update variations
        if (!empty($_POST['variation_name'])) {
            $upload_dir = 'uploads/variations/';

            foreach ($_POST['variation_name'] as $index => $variation_name) {
                $variation_photo = $_FILES['variation_photo']['name'][$index];
                $variation_photo_tmp = $_FILES['variation_photo']['tmp_name'][$index];
                $variation_photo_path = $upload_dir . basename($variation_photo);

                if (!empty($variation_photo) && move_uploaded_file($variation_photo_tmp, $variation_photo_path)) {
                    $stmt_variation = $conn->prepare("UPDATE $variation_table SET variation_name = ?, variation_photo = ? WHERE id = ?");
                    $stmt_variation->bind_param("ssi", $variation_name, $variation_photo_path, $_POST['variation_id'][$index]);
                } else {
                    $stmt_variation = $conn->prepare("UPDATE $variation_table SET variation_name = ? WHERE id = ?");
                    $stmt_variation->bind_param("si", $variation_name, $_POST['variation_id'][$index]);
                }
                $stmt_variation->execute();
                $stmt_variation->close();
            }
        }

        $success_message = "Item and variations updated successfully!";
    } else {
        $error_message = "Error: " . ($stmt ? $stmt->error : "Invalid tile type");
    }

    if ($stmt) $stmt->close();
    $conn->close();
} else {
    // Fetch the existing item and variations
    $id = $_GET['id'];
    $tile_type = $_GET['type'];

    $stmt = null;
    $variation_table = null;

    switch ($tile_type) {
        case "Nylon Tiles":
            $stmt = $conn->prepare("SELECT * FROM nylon_tiles WHERE id = ?");
            $variation_table = "nylon_tiles_variations";
            break;
        case "Polypropylene Tiles":
            $stmt = $conn->prepare("SELECT * FROM polypropylene_tiles WHERE id = ?");
            $variation_table = "polypropylene_tiles_variations";
            break;
        case "Colordot Collection":
            $stmt = $conn->prepare("SELECT * FROM colordot_collections WHERE id = ?");
            $variation_table = "colordot_collections_variations";
            break;
        case "Luxury Vinyl Tiles":
            $stmt = $conn->prepare("SELECT * FROM luxury_vinyl WHERE id = ?");
            $variation_table = "luxury_vinyl_variations";
            break;
        case "Broadloom":
            $stmt = $conn->prepare("SELECT * FROM broadloom WHERE id = ?");
            $variation_table = "broadloom_variations";
            break;
    }

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Fetch variations
        $stmt_variations = $conn->prepare("SELECT * FROM $variation_table WHERE item_id = ?");
        $stmt_variations->bind_param("i", $id);
        $stmt_variations->execute();
        $variations = $stmt_variations->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_variations->close();
    } else {
        die("Invalid tile type");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item | SMJ Furnishings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <div class="col-md-2">
            <?php include 'side_navbar.php'; ?>
        </div>
        <div class="container col-md-9 ms-auto">
            <h1 class="mb-5">Edit Item</h1>
            <?php if (isset($success_message)): ?>
                <p class="text-success"><?= $success_message ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="text-danger"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="POST" action="edit_item.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <input type="hidden" name="tile_type" value="<?= htmlspecialchars($tile_type) ?>">

                <div class="mb-3">
                    <label for="style_name" class="form-label">Style Name</label>
                    <input type="text" id="style_name" name="style_name" class="form-control" value="<?= htmlspecialchars($item['style_name']) ?>" required>
                </div>

                <!-- Dynamic Fields Based on Tile Type -->
                <?php include 'edit_item_fields.php'; ?>

                <!-- Variations -->
                <h5>Variations</h5>
                <?php foreach ($variations as $index => $variation): ?>
                    <div class="mb-3">
                        <input type="hidden" name="variation_id[]" value="<?= $variation['id'] ?>">
                        <label for="variation_name_<?= $index ?>" class="form-label">Variation Name</label>
                        <input type="text" id="variation_name_<?= $index ?>" name="variation_name[]" class="form-control" value="<?= htmlspecialchars($variation['variation_name']) ?>" required>
                        <label for="variation_photo_<?= $index ?>" class="form-label">Variation Photo</label>
                        <input type="file" id="variation_photo_<?= $index ?>" name="variation_photo[]" class="form-control">
                        <img src="<?= htmlspecialchars($variation['variation_photo']) ?>" alt="Variation Photo" class="img-thumbnail mt-2" style="max-height: 100px;">
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Update Item</button>
                <a href="inventory.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>