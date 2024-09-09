<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'admin_auth.php';

require_once __DIR__ . '/../../includes/database.php'; 
require_once __DIR__ . '/../../controllers/AdminController.php'; 

// Check if user is authenticated
restrictAccess();


$orderAccept = $_SESSION['order_accepted'] ?? '';
unset($_SESSION['order_accepted']);

$orderDelete = $_SESSION['order_deleted'] ?? '';
unset($_SESSION['order_deleted']); 





$section = isset($_GET['section']) ? $_GET['section'] : 'overview'; // Default to 'overview'

?>


<!DOCTYPE html>
<html lang="en">
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/icons.css">
    <link rel="stylesheet" href="../assets/css/admin_panel.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    

    
    
    <title>ChronoLuxe Dashboard</title>
  </head>
  <body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="/admin_dashboard">ChronoLuxe Admin</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="?section=products">
                        <i class="lni lni-producthunt"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link <?php echo ($section === 'sales') ? 'active' : ''; ?>" href="?section=sales">
                        <i class="lni lni-target-revenue"></i>
                        <span>Sales</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link <?php echo ($section === 'users') ? 'active' : ''; ?>" href="?section=users">
                        <i class="lni lni-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link <?php echo ($section === 'pending-orders') ? 'active' : ''; ?>" href="?section=pending-orders">
                        <i class="lni lni-spinner-solid"></i>
                        <span>Pending Orders</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a class="sidebar-link" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                </form>
            </div>
        </aside>

    
        <div class="main p-3">
            
            <?php if ($section === 'overview'): ?> 

                <section id="overview">
                    <div class="text-center">
                        <h2>Overview</h2>
                        <p>Summary of Users, Products, Orders</p>
                    </div>

                    <div class="row">
                        <!-- Key Metrics -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text"><?php echo $data['totalProducts']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text"><?php echo $data['totalOrders']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Sales</h5>
                                    <p class="card-text">₱<?php echo number_format($data['totalSales'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Orders</h5>
                                    <p class="card-text"><?php echo $data['pendingOrderCount']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Top-Selling Products</h5>
                                    <ul class="list-group">
                                        <?php foreach ($data['topSellingProducts'] as $product): ?>
                                            <li class="list-group-item"><?php echo htmlspecialchars($product['model_name']); ?> - Sold: <?php echo $product['sold']; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent User Registrations</h5>
                                    <ul class="list-group">
                                        <?php foreach ($data['recentUserRegistrations'] as $user): ?>
                                            <li class="list-group-item">
                                                Username: <?php echo htmlspecialchars($user['username']); ?> - Name: <?php echo htmlspecialchars($user['full_name']); ?> - Registered: <?php echo $user['created_at']; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Recent Activities -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Orders</h5>
                                    <ul class="list-group">
                                        <?php foreach ($data['recentOrdersPending'] as $order): ?>
                                            <? var_dump($order) ?>
                                            <li class="list-group-item">
                                                Order ID: <?php echo $order['id']; ?> - Customer: <?php echo htmlspecialchars($order['customer_name']); ?> - Date: <?php echo $order['order_date']; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Tasks</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">Pending Orders: <?php echo $data['pendingOrderCount']; ?></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </section>

            <?php endif; ?>

            <?php if ($section == 'products'): ?>
                <section id="products">
                    <div class="text-center">
                        <h2>Products</h2>
                        <p>Manage your product listings here.</p>
                    </div>

                    <div class="mb-3">
                        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
                            <div class="alert alert-success">Product deleted successfully.</div>
                        <?php endif; ?>

                    <!-- Button to trigger Add Watch Modal -->
                        <a href="#addWatchModal" class="btn btn-primary btn-sm" data-bs-toggle="modal">Add New Product</a>
                        <input type="text" class="form-control mt-2" id="searchProduct" placeholder="Search products by Id...">
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Model Name</th>
                                            <th>Price</th>
                                            <th>Stock Quantity</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($data['watches']) && is_array($data['watches'])): ?>
                                            <?php foreach ($data['watches'] as $watch): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($watch['id']); ?></td>
                                                    <td>
                                                        <?php if (!empty($watch['image'])): ?>
                                                            <img src="data:image/jpeg;base64,<?= base64_encode($watch['image']) ?>" alt="Product Image" width="50px" />
                                                        <?php else: ?>
                                                            <img src="path/to/default/image.jpg" alt="Default Image" width="50px" />
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($watch['model_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($watch['price']); ?></td>
                                                    <td><?php echo htmlspecialchars($watch['stock_quantity']); ?></td>
                                                    <td><?= strlen($watch['description']) > 30 ? htmlspecialchars(substr($watch['description'], 0, 30)) . '...' : htmlspecialchars($watch['description']) ?></td>
                                                    <td><?php echo htmlspecialchars($watch['watch_category']); ?>
                                                    <td><?php echo htmlspecialchars($watch['status']); ?></td>

                                                    <td>
                                                        <div class="btn-group" style="gap: 5px;">
                                                            <!-- Edit Button -->
                                                            <a href="#editWatchModal" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                                data-id="<?php echo htmlspecialchars($watch['id']); ?>"
                                                                data-model-name="<?php echo htmlspecialchars($watch['model_name']); ?>"
                                                                data-price="<?php echo htmlspecialchars($watch['price']); ?>"
                                                                data-stock-quantity="<?php echo htmlspecialchars($watch['stock_quantity']); ?>"
                                                                data-description="<?php echo htmlspecialchars($watch['description']); ?>"
                                                                data-status="<?php echo htmlspecialchars($watch['status']); ?>"
                                                                data-category="<?php echo htmlspecialchars($watch['watch_category']); ?>"
                                                                data-image-src="data:image/jpeg;base64,<?= base64_encode($watch['image']) ?>">
                                                                Edit
                                                            </a>

                                                            <!-- Delete Button -->
                                                            <a href="#deleteProductModal" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-id="<?php echo htmlspecialchars($watch['id']); ?>" data-page="<?php echo htmlspecialchars($_GET['page'] ?? 1); ?>">
                                                                Delete
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8">No products found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Add Watch Modal -->
                    <div class="modal fade" id="addWatchModal" tabindex="-1" aria-labelledby="addWatchModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/admin_dashboard?section=products&action=add" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" id="current_page" name="current_page" value="<?php echo htmlspecialchars($_GET['page'] ?? 1); ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addWatchModalLabel">Add Watch</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form Fields -->
                                        <div class="mb-3">
                                            <label for="add_model_name" class="form-label">Model Name</label>
                                            <input type="text" class="form-control" id="add_model_name" name="model_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="add_price" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="add_price" name="price" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="add_stock_quantity" class="form-label">Stock Quantity</label>
                                            <input type="number" class="form-control" id="add_stock_quantity" name="stock_quantity" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="add_description" class="form-label">Description</label>
                                            <textarea class="form-control" id="add_description" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="add_status" class="form-label">Status</label>
                                            <select class="form-select" id="add_status" name="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="add_category" class="form-label">Category</label>
                                            <select class="form-select" id="add_category" name="category" required>
                                                <option value="Luxury Watch">Luxury Watch</option>
                                                <option value="Sport Watch">Sport Watch</option>
                                                <option value="Casual Watch">Casual Watch</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="add_image" class="form-label">Watch Image</label>
                                            <input type="file" class="form-control" id="add_image" name="image" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Edit Watch Modal -->
                    <div class="modal fade" id="editWatchModal" tabindex="-1" aria-labelledby="editWatchModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/admin_dashboard?section=products&action=edit" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" id="edit_watch_id" name="watch_id" value="">
                                    <input type="hidden" id="current_page" name="current_page" value="<?php echo htmlspecialchars($_GET['page'] ?? 1); ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editWatchModalLabel">Edit Watch</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form Fields -->
                                        <div class="mb-3">
                                            <label for="edit_model_name" class="form-label">Model Name</label>
                                            <input type="text" class="form-control" id="edit_model_name" name="model_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_price" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="edit_price" name="price" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_stock_quantity" class="form-label">Stock Quantity</label>
                                            <input type="number" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_description" class="form-label">Description</label>
                                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_category" class="form-label">Category</label>
                                            <select class="form-select" id="edit_category" name="category" required>
                                                <option value="Luxury Watch">Luxury Watch</option>
                                                <option value="Sport Watch">Sport Watch</option>
                                                <option value="Casual Watch">Casual Watch</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_status" class="form-label">Status</label>
                                            <select class="form-select" id="edit_status" name="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_image" class="form-label">Watch Image</label>
                                            <input type="file" class="form-control" id="edit_image" name="image">
                                            <div id="currentImage" class="mt-2">
                                                <!-- Placeholder for existing image, will be updated via JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Product Modal -->
                    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/admin_dashboard?section=products&action=delete" method="POST">
                                    <input type="hidden" id="delete_watch_id" name="watch_id" value="">
                                    <input type="hidden" id="current_page" name="current_page" value="<?php echo htmlspecialchars($_GET['page'] ?? 1); ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteProductLabel">Delete Watch</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this watch?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <nav>
                            <ul class="pagination">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?section=products&page=<?php echo $currentPage - 1; ?>">Previous</a></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                        <a class="page-link" href="?section=products&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?section=products&page=<?php echo $currentPage + 1; ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Edit Watch Modal
                            var editModal = document.getElementById('editWatchModal');
                            editModal.addEventListener('show.bs.modal', function (event) {
                                var button = event.relatedTarget;
                                document.getElementById('edit_watch_id').value = button.getAttribute('data-id');
                                document.getElementById('edit_model_name').value = button.getAttribute('data-model-name');
                                document.getElementById('edit_price').value = button.getAttribute('data-price');
                                document.getElementById('edit_stock_quantity').value = button.getAttribute('data-stock-quantity');
                                document.getElementById('edit_description').value = button.getAttribute('data-description');
                                document.getElementById('edit_status').value = button.getAttribute('data-status');
                                document.getElementById('edit_category').value = button.getAttribute('data-category');

                                var imageSrc = button.getAttribute('data-image-src');
                                var currentImageDiv = document.getElementById('currentImage');
                                if (imageSrc) {
                                    currentImageDiv.innerHTML = '<img src="' + imageSrc + '" alt="Product Image" width="100px" />';
                                } else {
                                    currentImageDiv.innerHTML = '';
                                }
                            });

                            // Delete Watch Modal
                            var deleteModal = document.getElementById('deleteProductModal');
                            deleteModal.addEventListener('show.bs.modal', function (event) {
                                var button = event.relatedTarget;
                                var watchId = button.getAttribute('data-id');
                                document.getElementById('delete_watch_id').value = watchId;
                            });
                        });
                    </script>
                    
                    <script>
                     document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('searchProduct');

                            searchInput.addEventListener('input', function () {
                                const query = searchInput.value.trim();

                                if (query.length > 0) {
                                    fetch(`/admin_dashboard?section=products&action=search&query=${encodeURIComponent(query)}`)
                                        .then(response => response.text()) // Get the response as plain text (HTML)
                                        .then(data => {
                                            updateTable(data); // Pass the HTML to the update function
                                        })
                                        .catch(error => console.error('Error:', error));
                                } else {
                                    window.location.href = '/admin_dashboard?section=products';
                                }
                            });

                            function updateTable(html) {
                                const tableBody = document.querySelector('table tbody');
                                tableBody.innerHTML = html; // Update the table body with the returned HTML
                            }
                        });

                    </script>


                    
                    
                </section>
            <?php endif; ?>

            <?php if ($section == 'sales'): ?>
                <section id="sales">
                    <div class="text-center">
                        <h2>Sales</h2>
                        <p>View and manage sales reports.</p>
                    </div>

                    <div class="row">
                        <!-- Sales Per Day Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3>Sales Per Day</h3>
                                    <canvas id="salesPerDayChart" width="300" height="200"></canvas> <!-- Set the canvas size -->
                                </div>
                            </div>
                        </div>

                        <!-- Top-Selling Products Pie Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3>Top-Selling Products</h3>
                                    <canvas id="topSellingProductsChart" width="300" height="200"></canvas> <!-- Set the canvas size -->
                                </div>
                            </div>
                        </div>
                    </div>


                    
                    <div class="card mt-2" id="orderstatus">
                        <div class="card-body">
                            <h3>Update Order Status</h3>
                            <?php if (isset($_SESSION['order_status_update'])): ?>
                                <div class="alert alert-info">
                                    <?php echo htmlspecialchars($_SESSION['order_status_update']); ?>
                                    <?php unset($_SESSION['order_status_update']); // Clear the message after displaying ?>
                                </div>
                            <?php endif; ?>
                            <table class="table table-responsive" >
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer Name</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        <th>Shipping Status</th>
                                        <th>Payment Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentOrders)): ?>
                                        <tr><td colspan="7">No Data.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($recentOrders as $order): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order['id']); ?></td>
                                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                                <td>₱<?php echo number_format($order['total_price'] ?? 0, 2); ?></td>
                                                <td>
                                                    <form action="/admin_dashboard?section=sales" method="POST">
                                                        <select name="shipping_status" class="form-select">
                                                            <option value="Pending" <?php if ($order['shipping_status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                                            <option value="Shipped" <?php if ($order['shipping_status'] === 'Shipped') echo 'selected'; ?>>Shipped</option>
                                                            <option value="Delivered" <?php if ($order['shipping_status'] === 'Delivered') echo 'selected'; ?>>Delivered</option>
                                                            <option value="Canceled" <?php if ($order['shipping_status'] === 'Canceled') echo 'selected'; ?>>Canceled</option>
                                                        </select>
                                                </td>
                                                <td>
                                                    <select name="payment_status" class="form-select">
                                                        <option value="Paid" <?php if ($order['payment_status'] === 'Paid') echo 'selected'; ?>>Paid</option>
                                                        <option value="Unpaid" <?php if ($order['payment_status'] === 'Unpaid') echo 'selected'; ?>>Unpaid</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                                    <button type="submit" class="btn btn-primary" href="#orderstatus">Update Status</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                

                    <script src="../assets/js/chart.js"></script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Sales Per Day Chart
                            const salesPerDayLabels = <?php echo json_encode(array_column($salesPerDay ?? [], 'date')); ?>;
                            const salesPerDayData = <?php echo json_encode(array_column($salesPerDay ?? [], 'sales')); ?>;

                            new Chart(document.getElementById('salesPerDayChart').getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: salesPerDayLabels,
                                    datasets: [{
                                        label: 'Sales Per Day',
                                        data: salesPerDayData,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        fill: true,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        },
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });

                            // Top-Selling Products Pie Chart
                            const topSellingProductLabels = <?php echo json_encode(array_column($topSellingProducts ?? [], 'model_name')); ?>;
                            const topSellingProductData = <?php echo json_encode(array_column($topSellingProducts ?? [], 'sold')); ?>;

                            new Chart(document.getElementById('topSellingProductsChart').getContext('2d'), {
                                type: 'pie',
                                data: {
                                    labels: topSellingProductLabels,
                                    datasets: [{
                                        label: 'Top-Selling Products',
                                        data: topSellingProductData,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                        ],
                                        borderWidth: 3
                                    }]
                                },
                                options: {
                                    responsive: true
                                }
                            });
                        });
                    </script>

                </section>
            <?php endif; ?>



            <?php if ($section == 'users'): ?>
                <section id="admin-users">
                    <div class="text-center">
                        <h2>Users</h2>
                        <p>Manage user accounts and permissions.</p>
                    </div>
                    
                    <div class="card">
                        <div class="card-body" style="height: 450px;">
                            <div class="card-title mb-3">
                                <h3>User List</h3>
                            </div>
                            <div class="card-text" style="overflow-x: auto;">
                                <input type="text" id="searchUser" class="form-control mb-3" placeholder="Search by username or email">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Registration Date</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['id']) ?></td>
                                                <td><?= htmlspecialchars($user['username']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                                <td><?= htmlspecialchars($user['is_admin'] ? 'Admin' : 'User') ?></td>
                                                <td>
                                                    <div class="btn-group" style="gap: 5px;">
                                                        <a href="#editUserModal<?= htmlspecialchars($user['id']) ?>" class="btn btn-warning btn-sm" data-bs-toggle="modal">Edit</a>
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            <?= $user['is_admin'] ? 'disabled' : '' ?>
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#confirmDeleteModal"
                                                            data-user-id="<?= htmlspecialchars($user['id']) ?>"
                                                            data-form-action="?section=users&action=delete"
                                                            data-username="<?= htmlspecialchars($user['username']) ?>"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Modals for Editing Users --> 
                        <?php foreach ($users as $user): ?>
                            <div class="modal fade" id="editUserModal<?= htmlspecialchars($user['id']) ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= htmlspecialchars($user['id']) ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel<?= htmlspecialchars($user['id']) ?>">Edit User - <?= htmlspecialchars($user['username']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form for Editing User -->
                                            <form method="POST" action="?section=users&action=update">
                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Role</label>
                                                    <select class="form-select" id="role" name="is_admin">
                                                        <option value="0" <?= !$user['is_admin'] ? 'selected' : '' ?>>User</option>
                                                        <option value="1" <?= $user['is_admin'] ? 'selected' : '' ?>>Admin</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>      
                        
                        <!-- Confirmation Modal -->
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="confirmDeleteMessage">Are you sure you want to delete this user?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form id="deleteForm" action="" method="POST">
                                            <input type="hidden" name="user_id" id="userIdInput">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var confirmDeleteModal = document.getElementById('confirmDeleteModal');
                            confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                                var button = event.relatedTarget; // Button that triggered the modal
                                var userId = button.getAttribute('data-user-id');
                                var formAction = button.getAttribute('data-form-action');
                                var username = button.getAttribute('data-username');

                                var deleteForm = document.getElementById('deleteForm');
                                var userIdInput = document.getElementById('userIdInput');
                                var messageElement = document.getElementById('confirmDeleteMessage');

                                deleteForm.action = formAction;
                                userIdInput.value = userId;
                                messageElement.textContent = 'Are you sure you want to delete user: ' + username + '?';
                            });
                        });
                    </script>

                    <script>
                       document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('searchUser');

                            searchInput.addEventListener('input', function () {
                                const query = searchInput.value.trim();

                                if (query.length > 0) {
                                    fetch(`/admin_dashboard?section=users&action=search&query=${encodeURIComponent(query)}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            updateTable(data);
                                        })
                                        .catch(error => console.error('Error:', error));
                                } else {
                                    window.location.href = '/admin_dashboard?section=users'; // Reset to the full list when the search query is cleared
                                }
                            });

                            function updateTable(data) {
                                const tableBody = document.querySelector('table tbody');
                                tableBody.innerHTML = ''; // Clear previous results

                                if (data.length > 0) {
                                    data.forEach(user => {
                                        const row = document.createElement('tr');
                                        row.innerHTML = `
                                            <td>${user.id}</td>
                                            <td>${user.username}</td>
                                            <td>${user.email}</td>
                                            <td>${user.created_at}</td>
                                            <td>${user.is_admin ? 'Admin' : 'User'}</td>
                                            <td>
                                                <a href="#editUserModal${user.id}" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editUserModal${user.id}"
                                                data-id="${user.id}" data-username="${user.username}" data-email="${user.email}">
                                                    Edit
                                                </a>
                                                <a href="#deleteUserModal" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteUserModal"
                                                data-id="${user.id}">
                                                    Delete
                                                </a>
                                            </td>
                                        `;
                                        tableBody.appendChild(row);
                                    });

                                    // Reinitialize modal functionality
                                    initializeModals();
                                } else {
                                    tableBody.innerHTML = '<tr><td colspan="6">No users found.</td></tr>';
                                }
                            }

                            function initializeModals() {
                                // Add event listeners for dynamically generated modals
                                document.querySelectorAll('[data-bs-toggle="modal"]').forEach(modalTrigger => {
                                    modalTrigger.addEventListener('click', function () {
                                        const userId = this.getAttribute('data-id');
                                        const username = this.getAttribute('data-username');
                                        const email = this.getAttribute('data-email');

                                        // Fill modal form for editing user
                                        if (userId && username && email) {
                                            const modal = document.getElementById(`editUserModal${userId}`);
                                            if (modal) {
                                                modal.querySelector('input[name="username"]').value = username;
                                                modal.querySelector('input[name="email"]').value = email;
                                            }
                                        }
                                    });
                                });
                            }

                            initializeModals(); // Initialize for the first load
                        });
                    </script>

                </section>
            <?php endif; ?>


            <?php if ($section == 'pending-orders'): ?>
                <section id="pending-orders">
                    <div class="text-center mb-4">
                        <h2>Pending Orders</h2>
                        <p>Review and process pending orders.</p>
                    </div>

                    <!-- Statistics Overview -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Pending Orders Count</h5>
                                    <p class="display-6"><?= htmlspecialchars($pendingOrderCount) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Total Pending Amount</h5>
                                    <p class="display-6">₱<?= number_format($totalPendingAmount, 2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchOrders" placeholder="Search by Order ID, Customer Name, or Email">
                        </div>
                    </div>

                   
                    <!-- Orders List -->
                    <div class="card">
                        <div class="card-body">
                            <?php if ($orderAccept): ?>
                                    <div class="alert alert-success">
                                        <?php echo htmlspecialchars($orderAccept); ?>
                                    </div>
                            <?php endif; ?>
                            <?php if ($orderDelete): ?>
                                <div class="alert alert-success">
                                    <?php echo htmlspecialchars($orderDelete); ?>
                                </div>
                            <?php endif; ?>
                            <h3>Orders List</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Email</th>
                                            <th>Order Date</th>
                                            <th>Total Amount</th>
                                            <th>Payment Status</th>
                                            <th>Shipping Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pendingOrders)): ?>
                                            <?php foreach ($pendingOrders as $order): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($order['id']) ?></td>
                                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                                <td><?= htmlspecialchars($order['email']) ?></td>
                                                <td><?= htmlspecialchars($order['order_date']) ?></td>
                                                <td>₱<?= number_format($order['total_price'], 2) ?></td>
                                                <td><?= htmlspecialchars($order['payment_status']) ?></td>
                                                <td><?= htmlspecialchars($order['shipping_status']) ?></td>
                                                <td>
                                                <button 
                                                    onclick="window.location.href='?section=pending-orders&order_id=<?= htmlspecialchars($order['id']) ?>#orderModal'"
                                                    class="btn btn-info btn-sm">
                                                    View Details
                                                </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="8">No pending orders found.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="orderDetailsContent">
                                <?php if (isset($orderDetails) && $orderDetails): ?>
                                    <h5>Order ID: <?= htmlspecialchars($orderDetails['order']['id']) ?></h5>
                                    <p>Customer Username: <?= htmlspecialchars($orderDetails['order']['username']) ?></p>
                                    <p>Email: <?= htmlspecialchars($orderDetails['order']['email']) ?></p>
                                    <p>Order Date: <?= htmlspecialchars($orderDetails['order']['created_at']) ?></p>
                                    <h5 class="mt-4">Shipping Address</h5>
                                    <p><?= htmlspecialchars($orderDetails['address']['address_line']) ?></p>
                                    <p><?= htmlspecialchars($orderDetails['address']['city']) ?> <?= htmlspecialchars($orderDetails['address']['state']) ?></p>
                                    <p><?= htmlspecialchars($orderDetails['address']['country']) ?></p>
                                    <h5 class="mt-4">Order Items</h5>
                                    <ul class="list-group">
                                        <?php foreach ($orderDetails['items'] as $item): ?>
                                            <li class="list-group-item">
                                                <div class="d-flex align-items-center">
                                                    <img src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>" alt="<?= htmlspecialchars($item['model_name']) ?>" class="img-thumbnail me-3" style="width: 120px;">
                                                    <div>
                                                        <h6><?= htmlspecialchars($item['model_name']) ?></h6>
                                                        <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                                                        <p>Price per item: ₱<?= number_format($item['price_at_order'], 2) ?></p>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Order details not found.</p>
                                <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <form id="orderActionForm" method="POST" action="/admin/order_action">
                                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($_GET['order_id'] ?? '') ?>">
                                        <button type="submit" name="action" value="accept" class="btn btn-success">Accept Order</button>
                                        <button type="submit" name="action" value="delete" class="btn btn-danger">Delete Order</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var orderModal = document.getElementById('orderModal');
                            orderModal.addEventListener('show.bs.modal', function(event) {
                                var button = event.relatedTarget; // Button that triggered the modal
                                var orderId = button.getAttribute('data-order-id'); // Extract info from data-* attributes

                                // Set the order_id value in the modal form
                                var modalForm = orderModal.querySelector('#orderActionForm input[name="order_id"]');
                                modalForm.value = orderId;

                                // Optionally, you could also load order details via AJAX here
                            });
                        });

                    </script>
                    <script>
                        document.addEventListener('hidden.bs.modal', function (event) {
                            var modal = event.target;
                            var backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) {
                                backdrop.remove();
                            }
                        });
                    </script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const deleteWatchModal = document.getElementById('deleteProductModal');

                            deleteWatchModal.addEventListener('show.bs.modal', function (event) {
                                const button = event.relatedTarget; // Button that triggered the modal
                                const watchId = button.getAttribute('data-id'); // Extract watch ID

                                // Update modal content with watch data
                                const modalWatchId = deleteWatchModal.querySelector('#delete_watch_id');
                                modalWatchId.value = watchId;

                                // Debugging output
                                console.log('Watch ID:', watchId);
                            });
                        });

                    </script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.location.hash === '#orderModal') {
                                var orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
                                orderModal.show();
                            }
                        });
                    </script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('searchOrders');

                            searchInput.addEventListener('keyup', function () {
                                const query = searchInput.value;

                                fetch('/admin_dashboard?section=pending-orders&action=search&query=' + encodeURIComponent(query))
                                    .then(response => response.json())
                                    .then(data => {
                                        const tableBody = document.querySelector('table tbody');
                                        tableBody.innerHTML = '';

                                        if (data.length > 0) {
                                            data.forEach(order => {
                                                const row = document.createElement('tr');
                                                row.innerHTML = `
                                                    <td>${order.id}</td>
                                                    <td>${order.customer_name}</td>
                                                    <td>${order.email}</td>
                                                    <td>${order.created_at}</td>
                                                    <td>${parseFloat(order.total_price).toFixed(2)}</td>
                                                    <td>${order.payment_status}</td>
                                                    <td>${order.shipping_status}</td>
                                                    <td>
                                                        <button 
                                                            onclick="window.location.href='?section=pending-orders&order_id=${order.id}#orderModal'"
                                                            class="btn btn-info btn-sm">
                                                            View Details
                                                        </button>
                                                    </td>
                                                `;
                                                tableBody.appendChild(row);
                                            });
                                        } else {
                                            const row = document.createElement('tr');
                                            row.innerHTML = '<td colspan="8">No pending orders found.</td>';
                                            tableBody.appendChild(row);
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            });
                        });
                    </script>

            <?php endif ?>

    


    
    
    
  

    <script src="../assets/js/jquery.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/admin_panel.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    
    
    
   
        
    
  </body>
</html>


