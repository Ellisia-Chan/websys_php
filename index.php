<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TreadSpot</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- External CSS -->
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Banner -->
    <div class="banner">
        <img src="images/Shoes_img_1.jpg" alt="Shoe Store Banner">
    </div>

    <!-- Welcome Section -->
    <div class="welcome">
        <h1>Welcome to TreadSpot</h1>
        <p>Find the best and trendiest shoes at the best prices.</p>
        <a href="login.php" class="btn">Login</a>
        <a href="registration.php" class="btn">Register</a>
    </div>

    <!-- About Section -->
    <div class="about">
        <h2>About Us</h2>
        <p>We believe that shoes are more than just footwear, they are an essential part of your journey. That’s why we have created a diverse collection of top footwear brands offering you an exclusive selection of high-quality shoes. From performance sneakers to everyday casuals, we offer affordable prices and a seamless shopping experience. Walk your best life with TreadSpot!</p>
        <img src="images/shoe_store.jpg" alt="About Our Store">
    </div>

    <!-- Featured Products -->
    <div class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-container">
            <div class="product">
                <img src="images/shoe_images/Shoes/ShoeBrands/Nike/JA 2 'Heart Eyes' EP.png" alt="Shoe 1">
                <p>JA 2 'Heart Eyes' EP<br>$49.99</p>
            </div>
            <div class="product">
                <img src="images/shoe_images/Shoes/ShoeBrands/Adidas/Adizero SL2 Running Shoes.png" alt="Shoe 2">
                <p>Adizero SL2<br>$39.99</p>
            </div>
            <div class="product">
                <img src="images/shoe_images/Shoes/ShoeBrands/Puma/Electrify NITRO 4.png" alt="Shoe 3">
                <p>Electrify NITRO 4<br>$59.99</p>
            </div>
        </div>
    </div>


    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

</body>

<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>

</html>
