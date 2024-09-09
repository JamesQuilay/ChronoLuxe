<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include 'auth.php';


restrictAccess();




$title = 'ChronoLuxe'; 
ob_start();
?>

<div class="container mt-5" style="margin-bottom: 250px;">
    <div class="alert alert-danger d-none" id="checkout-alert" role="alert">
        You must set an address first.
    </div>

    <?php if (!empty($cartItems)): ?>
    <div  class="container my-4">
        <h2 class="text-2xl font-semibold mb-4">Your Shopping Cart</h2>
        <div class="row">
            <?php foreach ($cartItems as $item): ?>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>" alt="Product Image" class="img-fluid rounded" style="height: 120px; width: auto;">
                                <?php else: ?>
                                    <div class="placeholder-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title"><?= htmlspecialchars($item['model_name']) ?></h5>
                                <p class="card-text">Price: ₱<?= htmlspecialchars($item['price']) ?></p>
                                <p class="card-text">Stock: <?= htmlspecialchars($item['stock_quantity']) ?></p>
                            </div>
                            <div class="col-md-3 text-right">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(<?= $item['id'] ?>, 'decrease')">-</button>
                                    <span class="p-2"><?php echo $item['quantity']; ?></span>
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(<?= $item['id'] ?>, 'increase')">+</button>
                                </div>
                                <p class="card-text mt-2">Total: ₱<?= htmlspecialchars($item['quantity'] * $item['price']) ?></p>
                                <button class="btn btn-danger" onclick="removeItem(<?= htmlspecialchars($item['id']) ?>)">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="/#shop" class="btn btn-secondary">Continue Shopping</a>
            <div class="d-flex align-items-center">
                <h4 class="mb-0 me-3">Total Price: ₱<?= htmlspecialchars($totalPrice) ?></h4>
                <a href="/checkout" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container my-4">
        <p>Your cart is empty.</p>
        <a href="/#shop" class="btn btn-secondary">Continue Shopping</a>
    </div>
<?php endif; ?>

</div>

<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
    }

    .card img {
        border-radius: 8px;
    }

    .card-title {
        font-size: 1.25rem;
    }

    .input-group {
        margin-left: 4.9rem;
        height: 2.9rem;
        
    }

    
    .input-group .form-control {
        text-align: center;
        font-size: 1rem; 
        border: 1px solid #ced4da; 
        border-radius: 4px; 
        
        
    }

    
</style>

<script>
function updateQuantity(cartId, action) {
    fetch('/update_cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cart_id: cartId, action: action })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload(); // Refresh the page to update the cart display
          } else if (data.error) {
              alert(data.error); // Show error if stock is exceeded or another issue occurs
          }
      });
}

function removeItem(cartId) {
    fetch('/remove_cart_item', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cart_id: cartId })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload();
          } else {
              alert(data.error);
          }
      });
}


</script>

<?php
$content = ob_get_clean();
include 'base.php'; 
?>
