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

<div class="container mt-5">
    <div class="mb-3">
        <a href="/#shop" class="btn btn-info btn-outline btn-sm">Go Back</a>
    </div>
</div>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Product Image Box -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="product-image-box border rounded shadow-sm p-3 bg-white" style="width: 100%; height: 100%; position: relative; padding-top: 100%; overflow: hidden;">
                <?php if (!empty($watch['image'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($watch['image']) ?>" alt="<?= htmlspecialchars($watch['model_name']) ?>" 
                        class="img-fluid" 
                        style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                <?php else: ?>
                    <p class="text-muted text-center">No Image Available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Information Box -->
        <div class="col-lg-6">
            <div class="product-info-box bg-light rounded shadow-sm p-4">
                <h1 class="h3"><?= htmlspecialchars($watch['model_name']) ?></h1>

                <!-- Display Watch Category -->
                <p class="text-muted mb-2">Category: <?= htmlspecialchars($watch['watch_category']) ?></p>
                
                <p class="text-muted mb-3">
                    Price: ₱<?= number_format($watch['price'], 2) ?><br>
                    Stocks: <?= number_format($watch['stock_quantity']) ?>
                </p>


                <!-- Description with Spacing -->
                <p class="mb-4" style="white-space: pre-wrap;"><?= htmlspecialchars($watch['description']) ?></p>
                
                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-sm-row justify-content-between mb-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-primary btn-sm mb-2 mb-sm-0 me-sm-2" 
                                data-watch-id="<?= htmlspecialchars($watch['id']) ?>" 
                                onclick="addToCart(this)" style="width: 100%;">
                            Add to Cart
                        </button>
                    <?php else: ?>
                        <button class="btn btn-primary btn-sm mb-2 mb-sm-0 me-sm-2" onclick="redirectToLogin()" style="flex: 1;">Add to Cart</button>
                    <?php endif; ?>
                    
                </div>

                <!-- Buy Now Button -->
                <form action="/checkout" method="GET">
                    <input type="hidden" name="watch_id" value="<?= htmlspecialchars($watch['id']); ?>">
                    <div class="d-flex mb-3 mt-3">
                        <button id="proceed-checkout" class="btn btn-success w-100">Buy Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>










<script>
  function redirectToLogin() {
      window.location.href = '/login';
  }
</script>

<script>
   
    
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
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/cart_count', true); // Ensure this matches the route
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                updateCartCount(response.cartCount); // Set initial cart count
            } else {
                console.error(response.message);
            }
        } else {
            console.error('Error fetching cart count:', xhr.status);
        }
    };
    xhr.send(); // Send request to get the cart count on page load
});

</script>








<?php
$content = ob_get_clean(); // Capture content into $content variable
include 'base.php'; // Include base template
?>