<?php 
$isAuthenticated = isset($_SESSION['user_id']);
$title = 'Luxury Watches for Special Occasions'; 
ob_start();
$cartCount = isset($cartCount) ? $cartCount : 0;
?>




<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
  <div class="container">
 

  <ul class="nav nav-tabs justify-content-center" style="justify-content: center;">
      <?php if ($isAuthenticated): ?>
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/profile">
            <i class="fas fa-user" style="font-size: 14px; margin-right: 3px;"></i>
             <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
            </a>
        </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="/login">Account: Guest</a>
            </li>
        <?php endif; ?>
    </ul>
    

    <?php if ($isAuthenticated): ?>
        <a href="/cart" class="btn btn-success">
            <img src="../assets/images/cart-fill.svg" alt="Icon" style="width: 16px; height: 16px; filter: invert(1);">
            <span class="cart-count"><?= $cartCount ?></span> 
        </a>


    <?php else: ?>
        <a href="/login" class="btn btn-success">
            <img src="../assets/images/cart-fill.svg" alt="Icon" style="width: 16px; height: 16px; filter: invert(1);">
        </a>
    <?php endif; ?>
  </div>
</nav>



<style>
        .blog-header {
            background-image: url('../assets/images/blog3.webp');
            background-size: cover;
            background-position: center;
            height: 400px;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
        }
        .blog-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .blog-header p {
            font-size: 1.25rem;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .btn-finish {
            margin-top: 20px;
            width: 100%;
        }
    </style>
>

    <!-- Blog Header -->
    <header class="blog-header text-center">
        <h1>Luxury Watches for Special Occasions</h1>
        <p>Discover the finest luxury watches perfect for weddings, parties, and other important events.</p>
    </header>

    <!-- Blog Content -->
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Luxury Watches for Special Occasions</h5>
                        <p class="card-text">
                            When it comes to celebrating life's significant moments, a luxury watch can be the perfect accessory to complement your attire. Whether you’re attending a wedding, a high-profile party, or any other special event, a sophisticated timepiece can make a statement. In this guide, we will explore some of the finest luxury watches that are ideal for such occasions. From timeless classics to modern masterpieces, these watches are designed to add an extra touch of elegance and class to your look.
                        </p>
                        <p class="card-text">
                            <strong>Why Choose a Luxury Watch?</strong><br>
                            Luxury watches are more than just timekeepers; they are symbols of style and status. The craftsmanship, design, and quality materials used in luxury watches ensure that they not only look stunning but also last a lifetime. Investing in a luxury watch means owning a piece of art that can be passed down through generations.
                        </p>
                        <p class="card-text">
                            <strong>Top Picks for Special Occasions</strong><br>
                            Some renowned luxury watch brands known for their exquisite designs include Rolex, Patek Philippe, and Omega. Each brand offers a range of models that are perfect for different types of events. Whether you prefer a classic gold watch, a sleek stainless steel piece, or a watch adorned with diamonds, there’s a luxury watch that fits your style and occasion.
                        </p>
                        <p class="card-text">
                            <strong>Conclusion</strong><br>
                            Selecting the right luxury watch for a special occasion involves considering the event, your personal style, and the watch's overall design. With the right choice, you can enhance your look and make a lasting impression. Explore various options and choose a watch that will make your special moments even more memorable.
                        </p>
                        <a href="/shop" class="btn btn-primary btn-finish">Finish Reading</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
$content = ob_get_clean();
include 'base.php';
?>