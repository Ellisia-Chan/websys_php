<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Ensure order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = $_GET['id'];

// Delete the order
$delete_query = "DELETE FROM orders WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "<div class='success'>✅ Order deleted successfully!</div>";
} else {
    $_SESSION['message'] = "<div class='error'>⚠️ Failed to delete order.</div>";
}

// Redirect back to manage orders page
header("Location: manage_orders.php");
exit();
?>
