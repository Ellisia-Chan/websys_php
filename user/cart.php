<?php
session_start();
include '../config/config.php';

// Mock user ID for testing
$user_id = $_SESSION['user_id'] ?? 1;

// Handle item deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $del_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $del_stmt->bind_param("ii", $user_id, $delete_id);
    $del_stmt->execute();
    header("Location: cart.php");
    exit();
}

// Fetch cart items with product details
$sql = "SELECT products.*, cart.product_id FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="user_styles/cart_style.css">
</head>
<body>
    <?php include 'user_includes/navbar.php'; ?>

    <div class="cart-container">
        <a href="index.php" class="back-btn">⬅</a>
        <h1>Your Shopping Cart</h1>

        <?php if ($result->num_rows > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($item = $result->fetch_assoc()) {
                        echo "<tr>
                            <td><img src='../uploads/{$item['image']}' alt='{$item['name']}' class='product-img'></td>
                            <td>{$item['name']}</td>
                            <td>\${$item['price']}</td>
                            <td class='action-cell'>
                                <a href='buy.php?id={$item['id']}' class='buy-btn'>Buy</a>
                                <a href='cart.php?delete={$item['id']}' class='delete-btn'>Remove</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <?php include 'user_includes/footer.php'; ?>
</body>
</html>

