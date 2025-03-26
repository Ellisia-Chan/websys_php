<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- External CSS -->
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Banner -->
    <div class="banner">
        <img src="banner.jpg" alt="Shoe Store Banner">
    </div>

    <!-- Welcome Section -->
    <div class="welcome">
        <h1>Welcome to Our Shoe Store</h1>
        <p>Find the best and trendiest shoes at the best prices.</p>
    </div>

    <!-- About Section -->
    <div class="about">
        <h2>About Us</h2>
        <p>We are a leading online shoe store, bringing you stylish and comfortable footwear for every occasion.</p>
        <img src="about-image.jpg" alt="About Our Store">
    </div>

    <!-- Featured Products -->
    <div class="featured-products">
        <h2>Featured Products</h2>
        <div class="product">
            <img src="shoe1.jpg" alt="Shoe 1">
            <p>Pink Sneakers - $49.99</p>
        </div>
        <div class="product">
            <img src="shoe2.jpg" alt="Shoe 2">
            <p>Casual Shoes - $39.99</p>
        </div>
        <div class="product">
            <img src="shoe3.jpg" alt="Shoe 3">
            <p>Elegant Heels - $59.99</p>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>
