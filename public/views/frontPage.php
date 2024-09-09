<?php
$title = 'ChronoLuxe'; 
ob_start();
?>

<header class="text-white text-center py-5" style="background-image: url('assets/images/brand_bg.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container">
        <div class="hero mt-5">
            <h1 class="display-4 mb-4">CUSTOM WATCHES FOR ANY OCCASION</h1>
            <div class="d-flex justify-content-center gap-3">
                <a href="/shop#shop" class="btn btn-light btn-lg">Shop Now</a>
                <a href="#aboutus" class="btn btn-outline-light btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</header>

<section class="store py-5">
    <div class="container">
        <h2 class="display-5 text-center mb-4">Our Store</h2>
        <p class="lead text-center">
        Discover exceptional craftsmanship and precision with Rolex, the pinnacle of luxury watchmaking. 
        From timeless classics to cutting-edge innovations, our collection exudes sophistication and quality. 
        Visit our store to explore our exclusive selection and find the ideal Rolex to enhance your style.
        </p>
    </div>
</section>


<?php
$content = ob_get_clean(); // Capture content into $content variable
include 'base.php'; // Include base template
?>