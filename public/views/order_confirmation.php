<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include 'auth.php';
restrictAccess();


$title = 'ChronoLuxe'; 
ob_start();
?>


<div class="container-xl px-4 mt-4">
    <div class="mb-3">
        <a href="/" class="btn btn-info btn-outline btn-sm">Go Back</a>
    </div>
    <!-- Order Confirmation Card -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Thank you for your order, <?= htmlspecialchars($orderDetails['first_name']) ?>!</h5>
            <p class="card-text">Your order has been successfully placed. Below are the details of your order:</p>

            <!-- Order Details -->
            <div class="mb-3">
                <h6 class="text-muted">Order ID:</h6>
                <p class="fw-bold"><?= htmlspecialchars($orderDetails['id']) ?></p>
            </div>
            <div class="mb-3">
                <h6 class="text-muted">Order Date:</h6>
                <p class="fw-bold"><?= htmlspecialchars($orderDetails['created_at']) ?></p>
            </div>
            <div class="mb-3">
                <h6 class="text-muted">Order Status:</h6>
                <p class="fw-bold text-success"><?= htmlspecialchars($orderDetails['status']) ?></p>
            </div>

            <!-- Product List -->
            <div class="mb-3">
                <h6 class="text-muted">Order Items:</h6>
                <div class="list-group">
                    <?php foreach ($orderItems as $item): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <?php if ($item['image']): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>" alt="Product Image" width="50px" />
                            <?php else: ?>
                            <p>No Image</p>
                            <?php endif; ?>
                            <div class="ms-3">
                                <h6 class="mb-1"><?= htmlspecialchars($item['model_name']) ?></h6>
                                <p class="mb-1">Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                                <p class="mb-0">Price: ₱<?= htmlspecialchars($item['price_at_order']) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Total Price -->
            <div class="mb-3">
                <h6 class="text-muted">Total Price:</h6>
                <p class="fw-bold">₱<?= htmlspecialchars($orderDetails['total_price']) ?></p>
            </div>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'base.php'; 
?>

