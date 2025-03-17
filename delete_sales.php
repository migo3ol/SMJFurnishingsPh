<?php
// delete_sales.php - Delete a sale
include 'database.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM sales_records WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: sales.php");
exit();
?>