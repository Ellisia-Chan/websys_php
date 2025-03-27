<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order_id"])) {
    $order_id = $_POST["order_id"];
    $user_id = $_SESSION["user_id"];

    // Delete order from database
    $sql = "DELETE FROM orders WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $user_id);

    if ($stmt->execute()) {
        header("Location: orders.php?deleted=success");
        exit();
    } else {
        echo "<p>❌ Error deleting order. Please try again.</p>";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>
