<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'ChronoLuxe'; 
ob_start();
?>

<div class="container mt-5" style="margin-bottom: 250px;">
    <div class="mb-3">
        <a href="/#shop" class="btn btn-info btn-outline btn-sm">Continue Shopping</a>
    </div>
    <h2>Your Orders</h2>

    <?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <!-- Order Details -->
                <h5 class="card-title">Order ID: <?= htmlspecialchars($order['id']) ?></h5>
                <?php if ($order['status'] == 'Pending') : ?>
                    <p class="card-text">Status: <?= htmlspecialchars($order['status']) ?></p>
                <?php else : ?>
                    <p class="card-text">Shipping Status: <?= htmlspecialchars($order['shipping_status']) ?></p>
                <?php endif; ?>

                <p class="card-text">Total Price: ₱<?= number_format($order['total_price'], 2) ?></p>
                <p class="card-text">Order Date: <?= htmlspecialchars($order['created_at']) ?></p>

                <!-- Address Details -->
                <h5 class="mt-4">Shipping Address</h5>
                <?php if (!empty($addresses)): ?>
                    <?php
                    // Assuming the latest address is used for display
                    $address = end($addresses);
                    $addressLine = htmlspecialchars($address['address_line'] ?? '');
                    $city = htmlspecialchars($address['city'] ?? '');
                    $state = htmlspecialchars($address['state'] ?? '');
                    $country = htmlspecialchars($address['country'] ?? '');

                    // Create an array of address components and filter out empty values
                    $addressComponents = array_filter([
                        $addressLine,
                        $city,
                        $state,
                        $country
                    ]);

                    // Join the components with a comma
                    $formattedAddress = implode(', ', $addressComponents);
                    ?>
                    <p><?= $formattedAddress ?></p>
                <?php else: ?>
                    <p>No address available.</p>
                <?php endif; ?>


                <!-- Order Items -->
                <h5 class="mt-4">Order Items</h5>
                <div class="row">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>" alt="<?= htmlspecialchars($item['model_name']) ?>" class="img-fluid" style="max-width: 150px; height: auto;">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="card-title"><?= htmlspecialchars($item['model_name']) ?></h5>
                                        <p class="card-text">Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                                        <p class="card-text">Price per item: ₱<?= number_format($item['price_at_order'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p>Your orders are empty.</p>
    <?php endif; ?>
</div>

<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
    }

    .card img {
        border-radius: 8px;
        max-width: 150px; /* Adjust width of image */
    }

    .card-title {
        font-size: 1.25rem;
    }

    .card-text {
        margin-bottom: 0.5rem; /* Adjust text spacing */
    }

    .btn-info {
        margin-bottom: 1rem; /* Adjust button spacing */
    }

    .alert {
        margin-bottom: 1rem; /* Adjust alert spacing */
    }
</style>



                        
                        














<?php
$content = ob_get_clean(); // Capture content into $content variable
include 'base.php'; // Include base template
?>
