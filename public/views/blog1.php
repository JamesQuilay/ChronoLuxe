<?php 

$title = 'How to Choose the Perfect Watch'; 
ob_start();

$isAuthenticated = isset($_SESSION['user_id']);
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
            background-image: url('../assets/images/blog.png');
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
            font-size: 1.25rem;
        }
        .btn-finish {
            margin-top: 20px;
            width: 100%;
        }
    </style>


    <!-- Blog Header -->
    <header class="blog-header text-center">
        <h1>How to Choose the Perfect Watch</h1>
        
    </header>

    <!-- Blog Content -->
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- Blog Card 1 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Define Your Budget</h5>
                        <p class="card-text">
                            Before starting your search, it's important to set a budget. Watches come in various price ranges, so determining how much you're willing to spend will narrow down your choices.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 2 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Choose Your Style</h5>
                        <p class="card-text">
                            Watches come in different styles such as casual, luxury, dress, and sports. Think about your lifestyle and when you will wear the watch the most to find one that complements your style.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 3 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Watch Movements</h5>
                        <p class="card-text">
                            Understanding the type of movement, whether it's automatic, quartz, or mechanical, will help you make an informed decision based on the functionality you prefer.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 4 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Material and Durability</h5>
                        <p class="card-text">
                            Consider the materials used in the watch, including the strap and case. Stainless steel, leather, and sapphire crystal are common, durable materials for watches.
                        </p>
                    </div>
                </div>

                <a href="/shop" class="btn btn-primary btn-finish">Finished Reading</a>
            </div>
        </div>
    </section>

<?php
$content = ob_get_clean();
include 'base.php';
?>