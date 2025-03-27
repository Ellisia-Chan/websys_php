<?php
include '../config/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Shoe Store</title>
    <link rel="stylesheet" href="user_styles/user_style.css">
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="homepage-container">
        <h1>Welcome to Shoe Store</h1>
        <p>Find the best and trendiest shoes at unbeatable prices!</p>

        <div class="product-grid">
            <?php
            // Fetch products from database
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='product-card'>
                        <img src='../uploads/" . $row['image'] . "' alt='" . $row['name'] . "'>
                        <h3>" . $row['name'] . "</h3>
                        <p>$" . $row['price'] . "</p>
                        <a href='product.php?id=" . $row['id'] . "' class='btn'>View Details</a>
                    </div>";
                }
            } else {
                echo "<div class='no-products'>No products available.</div>";
            }
            
            ?>
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
