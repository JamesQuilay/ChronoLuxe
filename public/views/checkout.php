<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'auth.php';

restrictAccess();

// Assuming data is passed from the controller
$user = $data['user']; // User information from the controller
$cartItems = $data['cart_items']; // Cart items from the controller
$totalAmountToPay = $data['total_amount_to_pay']; // Total amount from the controller

$addressLine = htmlspecialchars($user['address_line'] ?? '');
$city = htmlspecialchars($user['city'] ?? '');
$state = htmlspecialchars($user['state'] ?? '');
$country = htmlspecialchars($user['country'] ?? '');

// Create an array of address components and filter out empty values
$addressComponents = array_filter([
    $addressLine,
    $city,
    $state,
    $country
]);

// Join the components with a comma and a space
$formattedAddress = implode(', ', $addressComponents);




$title = 'ChronoLuxe'; 
ob_start();
?>

<div class="container mt-5 mb-5">
    <div class="alert alert-danger d-none" id="checkout-alert" role="alert">
        You must set an address first.
    </div>
    <div class="mb-3">
        <a href="/#shop" class="btn btn-info btn-outline btn-sm">Continue Shopping</a>
    </div>
    <div class="row">
        <!-- Product Information Section -->
        <div class="col-lg-7 p-0 pe-lg-4">
            <div class="bg-light p-4 rounded shadow-sm">
                <!-- Loop through the user's cart items -->
                <?php foreach ($cartItems as $item): ?>
                <div class="d-flex align-items-center mb-4">
                    <!-- Product Image -->
                    <?php if ($item['image']): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>" alt="Product Image" class="img-fluid" style="width: 100px; height: auto;">
                    <?php else: ?>
                        <img src="../static/images/default.jpg" alt="Default Image" class="img-thumbnail" style="width: 10px; height: auto;">
                    <?php endif; ?>
                    <div class="ms-3 flex-grow-1">
                        <h2 class="h5 font-weight-bold mb-3"><?php echo htmlspecialchars($item['model_name']); ?> (<?php echo htmlspecialchars($item['quantity']); ?>)</h2>
                        <div class="d-flex justify-content-between mb-2">
                            <p class="text-muted">Price</p>
                            <p class="h5">₱<?php echo htmlspecialchars($item['price']); ?></p>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <p class="text-muted">Total</p>
                            <p class="h5">₱<?php echo htmlspecialchars($item['quantity'] * $item['price']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <!-- Total Amount to Pay -->
                <div class="d-flex justify-content-between mt-4">
                    <h4 class="font-weight-bold">Total Amount to Pay</h4>
                    <h4 class="text-success">₱<?php echo htmlspecialchars($totalAmountToPay); ?></h4>
                </div>
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="col-lg-5 p-0 ps-lg-4">
            <div class="bg-light p-4 rounded shadow-sm">
                <h5 class="fw-bold">Payment Details</h5>
                <form action="/checkout" method="POST"> 
                <?php if ($watchId): ?> 
                    <input type="hidden" name="watch_id" value="<?= htmlspecialchars($watchId); ?>">
                <?php endif; ?>
                    <div class="mb-3">
                        <label for="emailAddress" class="form-label text-muted">Email Address</label>
                        <input type="email" id="emailAddress" name="emailAddress" class="form-control" placeholder="example@domain.com" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="buyerInfo" class="form-label text-muted">Buyer's Information</label>
                        <input type="text" id="buyerInfo" name="buyerInfo" class="form-control" placeholder="Your Name" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="buyerInfo" class="form-label text-muted">Buyer's Phone Number</label>
                        <input type="text" id="buyerInfo" name="buyerInfo" class="form-control" placeholder="Phone Number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label for="buyerInfo" class="form-label text-muted">Address</label>
                        <input type="text" id="buyerInfo" name="buyerInfo" class="form-control" placeholder="Phone Number" value="<?= $formattedAddress ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label text-muted">Payment Method</label>
                        <input type="text" id="paymentMethod" name="paymentMethod" class="form-control" value="Offline Payment" disabled>
                    </div>
                    <div class="d-flex mb-3 mt-3">
                        <button id="proceed-checkout" class="btn btn-primary w-100">Purchase</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('proceed-checkout').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission

    fetch('/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(new FormData(event.target.form))
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('checkout-alert').classList.remove('d-none');
            document.getElementById('checkout-alert').innerText = data.error;
        } else if (data.success) {
            // Proceed to payment or confirmation page
            window.location.href = '/order_confirmation?order_id=' + data.orderId;
        }
    })
    .catch(error => console.log(error));
});
</script>

<?php
$content = ob_get_clean();
include 'base.php';
?>
