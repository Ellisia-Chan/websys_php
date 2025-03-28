<?php
include '../config/config.php';
session_start();

// Check if product ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php"); // Redirect to home if no product ID
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>Product not found.</p>";
    exit();
}

$product = $result->fetch_assoc();

// Fetch related products (excluding the current product)
$related_sql = "SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 4";
$related_stmt = $conn->prepare($related_sql);
$related_stmt->bind_param("i", $product_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Shoe Store</title>
    <link rel="stylesheet" href="user_styles/user_product_style.css">
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="product-page-container">
        <a href="index.php" class="back-btn">⬅</a>

        <div class="product-details">
            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <!-- Updated Buy Button with link to buy.php -->
                <a href="buy.php?id=<?php echo $product_id; ?>" class="buy-btn">Buy Now</a>
            </div>
        </div>

        <!-- Static Reviews Section -->
        <div class="reviews">
            <h2>Customer Reviews</h2>
            <div class="review">
                <img src="../uploads/user1.jpg" alt="User 1">
                <p><strong>John Doe:</strong> Amazing quality! Worth every penny! ⭐⭐⭐⭐⭐</p>
            </div>
            <div class="review">
                <img src="../uploads/user2.jpg" alt="User 2">
                <p><strong>Jane Smith:</strong> Super comfortable shoes, fast shipping! ⭐⭐⭐⭐⭐</p>
            </div>
            <div class="review">
                <img src="../uploads/user3.jpg" alt="User 3">
                <p><strong>Emily Johnson:</strong> Love the style, will buy again! ⭐⭐⭐⭐☆</p>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="related-products">
            <h2>Related Products</h2>
            <?php $gridClass = $related_result->num_rows === 0 ? 'single-grid' : ''; ?>
            <div class="product-grid <?php echo $gridClass; ?>">
                <?php
                if ($related_result->num_rows > 0) {
                    while ($related = $related_result->fetch_assoc()) {
                        echo "
                        <div class='product-card'>
                            <img src='../uploads/" . $related['image'] . "' alt='" . htmlspecialchars($related['name']) . "'>
                            <h3>" . htmlspecialchars($related['name']) . "</h3>
                            <p>$" . number_format($related['price'], 2) . "</p>
                            <a href='product.php?id=" . $related['id'] . "' class='btn'>View Details</a>
                        </div>";
                    }
                } else {
                    echo "<p class='no-products'>No related products available.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <?php include 'user_includes/footer.php'; ?>

</body>
<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>
</html>
