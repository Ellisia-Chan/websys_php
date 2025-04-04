<?php
include '../config/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TreadSpot</title>
    <link rel="stylesheet" href="user_styles/user_style.css">
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

        <!-- Hero Banner -->
    <div class="banner">
        <img src="../images/Shoes_img_1.jpg" alt="Shoe Store Banner">
    </div>

    <div class="homepage-container">
        <h1>Welcome to TreadSpot</h1>
        <p>Find the best and trendiest shoes at the best prices.</p>

        <?php
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $result = $conn->query($sql);
        $grid_class = ($result->num_rows > 0) ? "grid-4" : "grid-1";
        ?>

        <div class="product-grid <?php echo $grid_class; ?>">
            <?php
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
