<?php
include 'database.php';

// Check if project ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_projects.php");
    exit();
}

$project_id = intval($_GET['id']);

// Fetch project to get images for deletion
$query = "SELECT images FROM projects WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin_projects.php");
    exit();
}

$project = $result->fetch_assoc();
$images = json_decode($project['images'], true) ?? [];

// Delete images from server
foreach ($images as $image) {
    $image_path = "Uploads/projects/" . $image;
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete project from database
$delete_query = "DELETE FROM projects WHERE id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $project_id);

if ($delete_stmt->execute()) {
    header("Location: admin_projects.php?message=Project deleted successfully");
} else {
    header("Location: admin_projects.php?error=Failed to delete project");
}
exit();
?>