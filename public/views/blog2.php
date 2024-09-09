<?php 

$title = 'Watch Maintenance Tips'; 
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
            background-image: url('../assets/images/blog2.png');
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
       
        
    </header>

    <!-- Blog Content -->
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
            
                <!-- Blog Card 1 -->
                <div class="card mb-4">
                
                    <div class="card-body">
                        <h1>Watch Maintenance Tips</h1>
                        <h5 class="card-title">1. Clean Your Watch Regularly</h5>
                        <p class="card-text">
                            Dust, dirt, and grime can build up over time. Use a microfiber cloth to gently wipe down your watch. For more thorough cleaning, use a soft brush and a mild soap solution to clean metal bands and watch cases. Avoid immersing non-waterproof watches in water.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 2 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">2. Avoid Extreme Temperatures</h5>
                        <p class="card-text">
                            Extreme heat or cold can affect your watch's internal mechanisms and battery life. Avoid leaving your watch in direct sunlight for prolonged periods or in freezing environments.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 3 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">3. Store Your Watch Properly</h5>
                        <p class="card-text">
                            When you're not wearing your watch, store it in a cool, dry place. Consider investing in a watch box or case to protect it from scratches, dust, and moisture. If you own an automatic watch, a watch winder can help maintain its functionality.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 4 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">4. Get Regular Servicing</h5>
                        <p class="card-text">
                            Just like any precision instrument, your watch needs regular servicing to keep it in top condition. It's recommended to have your watch serviced by a professional every 2-3 years to ensure it runs smoothly and any worn-out parts are replaced.
                        </p>
                    </div>
                </div>

                <!-- Blog Card 5 -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">5. Avoid Contact with Chemicals</h5>
                        <p class="card-text">
                            Perfumes, lotions, and cleaning products can cause damage to your watch, especially the leather strap or metal finish. Always remove your watch before applying any chemicals to avoid discoloration and degradation of materials.
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