<?php
include 'database.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type'];

    // Determine the table based on the tile type
    $table_map = [
        "Nylon Tiles" => "nylon_tiles",
        "Polypropylene Tiles" => "polypropylene_tiles",
        "Colordot Collection" => "colordot_collections",
        "Luxury Vinyl Tiles" => "luxury_vinyl",
        "Broadloom" => "broadloom"
    ];

    if (array_key_exists($type, $table_map)) {
        $table = $table_map[$type];

        // Delete the item from the appropriate table
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: inventory.php?success=Item deleted successfully");
        } else {
            header("Location: inventory.php?error=Failed to delete item");
        }
        $stmt->close();
    } else {
        header("Location: inventory.php?error=Invalid tile type");
    }
} else {
    header("Location: inventory.php?error=Missing parameters");
}
$conn->close();
?>