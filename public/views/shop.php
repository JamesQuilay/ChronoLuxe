<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'ChronoLuxe'; 
ob_start();
$isAuthenticated = isset($_SESSION['user_id']);


$watches = isset($watches) ? $watches : [];
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


<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../assets/images/luxury_watch.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Luxury Watch</h5>
                <p>Discover the pinnacle of sophistication with our Luxury Watch collection. Each timepiece is crafted with meticulous attention to detail.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="../assets/images/sport_watch.webp" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Sport Watch</h5>
                <p>Embrace your active lifestyle with our Sport Watch range. Engineered for precision and durability.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="../assets/images/casual_watch.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Casual Watch</h5>
                <p> Elevate your everyday look with our Casual Watch collection. Designed for versatility and comfort.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>



<section class="categories py-5">
    <div class="container">
        <h2 class="text-center mb-4">Explore Categories</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="../assets/images/luxury_watches.jpg" class="card-img-top" alt="Luxury Watches">
                    <div class="card-body">
                        <h5 class="card-title">Luxury Watches</h5>
                        <a href="#shop" class="btn btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="../assets/images/sport_watches.webp" class="card-img-top" alt="Sports Watches">
                    <div class="card-body">
                        <h5 class="card-title">Sports Watches</h5>
                        <a href="#shop" class="btn btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="../assets/images/casual_watches.jpg" class="card-img-top" alt="Casual Watches">
                    <div class="card-body">
                        <h5 class="card-title">Casual Watches</h5>
                        <a href="#shop" class="btn btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">What Our Customers Say</h2>
        <div id="carouselTestimonials" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="testimonial text-center">
                        <img src="../assets/images/john_doe.jpg" alt="Customer" class="rounded-circle mb-3" width="100" height="100">
                        <h5 class="mb-1">John Doe</h5>
                        <p class="text-muted">"Amazing collection! The watch I bought exceeded my expectations."</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="testimonial text-center">
                        <img src="../assets/images/jane_smith.jpg" alt="Customer" class="rounded-circle mb-3" width="100" height="100">
                        <h5 class="mb-1">Jane Smith</h5>
                        <p class="text-muted">"Excellent service and fast shipping. I love my new watch!"</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselTestimonials" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselTestimonials" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>
</section>

<section class="cta py-5 text-white text-center" style="background-color: #343a40;">
    <div class="container">
        <h2 class="mb-4">Exclusive Limited-Time Offer</h2>
        <p class="lead">Save up to 50% on select watches. Offer ends soon!</p>
        <a href="#shop" class="btn btn-warning btn-lg">Shop Now</a>
    </div>
</section>

<section class="shop py-5 mb-5" id="shop">
    <div class="container">
        <h2 class="text-center mb-4">Our Products</h2>



        <div class="row mt-4">
            <?php if (!empty($watches)): ?>
                <?php foreach ($watches as $watch): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 position-relative">
                            <!-- Add Sale Badge if applicable -->
                            <?php if ($watch['status'] == 'Active'): ?>
                                <div class="badge-sale">Sale</div>
                            <?php endif; ?>

                            <!-- Product Image -->
                            <div class="product-image">
                                <img src="data:image/jpeg;base64,<?= base64_encode($watch['image']) ?>" alt="Product Image" width="50px" />
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <!-- Product Title -->
                                <h5 class="card-title"><?= htmlspecialchars($watch['model_name']) ?></h5>
                                
                                <!-- Product Price -->
                                <p class="card-text text-muted">₱<?= htmlspecialchars($watch['price']) ?></p>
                                
                                <!-- Short Description -->
                                <p class="card-text small">
                                    <?= strlen($watch['description']) > 30 ? htmlspecialchars(substr($watch['description'], 0, 30)) . '...' : htmlspecialchars($watch['description']) ?>
                                </p>
                                
                                <!-- Star Rating -->
                                <div class="star-rating">★★★★☆</div>
                                
                                <!-- Action Buttons -->
                                <div class="mt-3 d-flex flex-column align-items-center">
                                    <?php if ($watch['status'] == 'Active'): ?>
                                        <?php if ($isAuthenticated): ?>
                                            <button class="btn btn-primary btn-sm mb-2 add-to-cart-btn" 
                                                    data-watch-id="<?= htmlspecialchars($watch['id']) ?>" 
                                                    onclick="addToCart(this)" style="width: 80%;">
                                                Add to Cart
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-primary btn-sm mb-2" 
                                                    onclick="redirectToLogin('<?= htmlspecialchars($watch['id']) ?>')" style="width: 80%;">
                                                Add to Cart
                                            </button>
                                        <?php endif; ?>
                                        <a href="/product_details?id=<?= htmlspecialchars($watch['id']) ?>" class="btn btn-secondary btn-sm" style="width: 80%;">View Details</a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm mb-2" disabled style="width: 80%;">Add to Cart</button>
                                        <a href="/product_details?id=<?= htmlspecialchars($watch['id']) ?>" class="btn btn-secondary btn-sm" style="width: 80%;">View Details</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No products available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<nav aria-label="Product pagination">
    <ul class="pagination justify-content-center">
         <!-- Previous Button -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= max(1, $page - 1) ?>#shop">Previous</a>
        </li>

        <!-- Page Number Links -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>#shop"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next Button -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>#shop">Next</a>
        </li>
    </ul>
</nav>


<section class="blog py-5">
  <div class="container">
    <h2 class="text-center mb-4">From Our Blog</h2>
    <div class="row">
      <!-- Blog Card 1: How to Choose the Perfect Watch -->
      <div class="col-md-4">
        <div class="card">
          <img src="../assets/images/blog.jpg" class="card-img-top" alt="How to Choose the Perfect Watch">
          <div class="card-body">
            <h5 class="card-title">How to Choose the Perfect Watch</h5>
            <p class="card-text">Learn tips and tricks for selecting the best watch that suits your style and budget.</p>
            <a href="/blog/1" class="btn btn-outline-primary">Read More</a>
          </div>
        </div>
      </div>

      <!-- Blog Card 2: Watch Maintenance Tips -->
      <div class="col-md-4">
        <div class="card">
          <img src="../assets/images/blog2.avif" class="card-img-top" alt="Watch Maintenance Tips">
          <div class="card-body">
            <h5 class="card-title">Watch Maintenance Tips</h5>
            <p class="card-text">Keep your watch looking brand new with these simple maintenance tips.</p>
            <a href="/blog/2" class="btn btn-outline-primary">Read More</a>
          </div>
        </div>
      </div>

      <!-- Blog Card 3: Luxury Watches for Special Occasions -->
      <div class="col-md-4">
        <div class="card">
          <img src="../assets/images/blog3.webp" class="card-img-top" alt="Luxury Watches for Special Occasions">
          <div class="card-body">
            <h5 class="card-title">Luxury Watches for Special Occasions</h5>
            <p class="card-text">Discover the finest luxury watches perfect for weddings, parties, and other important events.</p>
            <a href="/blog/3" class="btn btn-outline-primary">Read More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<section class="newsletter py-5 bg-dark text-white">
  <div class="container text-center">
    <h2 class="mb-4">Stay Updated</h2>
    <p>Subscribe to our newsletter and get the latest updates on new arrivals and exclusive deals.</p>
    <form class="form-inline justify-content-center">
      <input type="email" class="form-control mb-2 mr-sm-2" placeholder="Enter your email">
      <button type="submit" class="btn btn-primary mb-2">Subscribe</button>
    </form>
  </div>
</section>

<style>
    .shop {
        background-color: #f5f5f5; /* Dirty white background for the entire section */
        padding: 20px; /* Optional: add some padding to the section */
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #ffffff; /* White background for each card */
    }
    .badge-sale {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: red;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }
    .card-body {
        text-align: center;
    }
    .product-image {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .star-rating {
        color: gold;
        font-size: 1rem;
    }
    @media (max-width: 767.98px) {
        .card-img-top {
            height: 150px;
        }
    }
</style>

<style>
    .nav-tabs .nav-link {
    border-radius: 0.25rem;
    background-color: rgba(255, 255, 255, 0.2);
    color: #333;
    transition: background-color 0.3s, color 0.3s;
}

.nav-tabs .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.nav-tabs .nav-link.active {
    color: #007bff;
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid #dee2e6;
}
</style>


<script>
  function redirectToLogin(watchId) {
    var returnUrl = encodeURIComponent(window.location.pathname + '?watch_id=' + watchId);
    window.location.href = '/login?return_url=' + returnUrl;
}
</script>


<script>
    // Function to add an item to the cart
    
    function addToCart(button) {
    var watchId = button.getAttribute('data-watch-id');
    var quantity = 1; // Fixed quantity for adding to cart

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/add_to_cart', true); // Ensure this matches the route
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                updateCartCount(response.cartCount); // Update cart count
                updateButton(button); // Update button text
            } else {
                alert(response.message);
            }
        } else {
            alert('Error: ' + xhr.status);
        }
    };
    xhr.send('watch_id=' + encodeURIComponent(watchId) + '&quantity=' + encodeURIComponent(quantity));
}


// Function to update the button text to reflect item addition
function updateButton(button) {
    button.textContent = 'Added'; // Simple text update
}

// Function to update the cart count
function updateCartCount(count) {
    var cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(function(element) {
        element.textContent = count; // Update cart count in all relevant elements
    });
}

// Fetch the initial cart count when the page loads
document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var watchId = urlParams.get('watch_id');

    if (watchId) {
        // Check if user is authenticated
        var isAuthenticated = <?= json_encode($isAuthenticated) ?>; // Use PHP to pass the authentication status
        if (isAuthenticated) {
            var button = document.querySelector('[data-watch-id="' + watchId + '"]');
            if (button) {
                addToCart(button);
            }
        }
    }

    // Fetch the initial cart count when the page loads
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/cart_count', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                updateCartCount(response.cartCount);
            } else {
                console.error(response.message);
            }
        } else {
            console.error('Error fetching cart count:', xhr.status);
        }
    };
    xhr.send();
});



</script>



<?php
$content = ob_get_clean(); // Capture content into $content variable
include 'base.php'; // Include base template
?>

