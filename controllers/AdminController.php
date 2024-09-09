<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../models/Watch.php';

include '../includes/database.php';

class AdminController {
    private $pdo;
    private $watchModel;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->connect();
        $this->watchModel = new Watch($this->pdo);
        
    }

    public function dashboard() {
        $section = $_GET['section'] ?? 'dashboard';
        $action = $_GET['action'] ?? null;

        

        
    
        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($section === 'users') {
                if ($action === 'update') {
                    $this->updateUser();
                } elseif ($action === 'delete') {
                    $this->deleteUser();
                }
            } elseif ($section === 'sales')  {
               $this->handleOrderStatus();
            } elseif ($section === 'products') {
                if ($action === 'delete') {
                    $this->deleteWatch();
                } elseif ($action === 'add') {
                    $this->addProduct();
                } elseif ($action === 'edit') {
                    if (isset($_POST['watch_id']) && !empty($_POST['watch_id'])) {
                        $this->editProduct();
                    }
                }
            }
            
        }
    
        // Fetch data based on section
        $data = [];
        switch ($section) {
            case 'products':
                if (isset($_GET['action']) && $_GET['action'] === 'search') {
                    $query = $_GET['query'] ?? '';
                    $data['watches'] = $this->searchWatches($query); // Implement searchWatches method in Watch model
                    echo json_encode($data['watches']); // Return JSON for AJAX
                    exit;
                } else {
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $itemsPerPage = 8;
                    $offset = ($currentPage - 1) * $itemsPerPage;
        
                    $data['watches'] = $this->watchModel->listWatches($itemsPerPage, $offset);
                    $totalWatches = $this->watchModel->countWatches();
                    $data['totalPages'] = ceil($totalWatches / $itemsPerPage);
                    $data['currentPage'] = $currentPage;
                }
                break;
            
            case 'pending-orders':
                if (isset($_GET['action']) && $_GET['action'] === 'search') {
                    $query = $_GET['query'] ?? '';
                    $data['pendingOrders'] = $this->searchOrders($query); // Implement searchOrders method in Order model
                    echo json_encode($data['pendingOrders']); // Return JSON for AJAX
                    exit;
                } else {
                    $data['pendingOrderCount'] = $this->getPendingOrderCount();
                    $data['totalPendingAmount'] = $this->getTotalPendingAmount();
                    $data['pendingOrders'] = $this->getPendingOrders();
                }
                break;
        
            case 'sales':
                $data['totalSales'] = $this->getTotalSales();
                $data['totalOrders'] = $this->getTotalOrders();
                $data['salesPerDay'] = $this->getSalesPerDay();
                $data['topSellingProducts'] = $this->getTopSellingProducts();
                $data['recentOrders'] = $this->getRecentOrders();
                
                break;
            
            case 'users':
                if (isset($_GET['action']) && $_GET['action'] === 'search') {
                    $query = $_GET['query'] ?? '';
                    $data['users'] = $this->searchUsers($query); // Implement searchUsers method in User model
                    echo json_encode($data['users']); // Return JSON for AJAX
                    exit;
                } else {
                    $data['users'] = $this->getUsers();
                }
                break;
            
            
            default:
            case 'overview':
                $data['totalProducts'] = $this->getTotalProducts();
                $data['totalOrders'] = $this->getTotalOrders();
                $data['totalSales'] = $this->getTotalSales();
                $data['pendingOrderCount'] = $this->getPendingOrderCount();
                $data['totalPendingAmount'] = $this->getTotalPendingAmount();
                $data['salesPerDay'] = $this->getSalesPerDay();
                $data['topSellingProducts'] = $this->getTopSellingProducts();
                $data['recentOrdersPending'] = $this->getRecentOrdersPending();
                $data['recentUserRegistrations'] = $this->getRecentUserRegistrations();
                
                break;
                // Overview implementation
                
        }
            
        
        
        // dont remove
        if (isset($_GET['order_id'])) {
            $orderId = (int)$_GET['order_id'];
            $data['orderDetails'] = $this->fetchOrderDetails($orderId);
        }
    
        

        extract($data);
        include '../admin/views/admin_dashboard.php';
    }

    private function getTotalSales() {
        $query = "SELECT SUM(total_price) AS total_sales 
                  FROM orders 
                  WHERE shipping_status = 'Delivered' AND payment_status = 'Paid'";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_sales'] ?? 0;
    }

    private function getTotalProducts() {
        $query = "SELECT COUNT(id) AS total_products FROM watches";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_products'] ?? 0;
    }
    
    

    private function getTotalOrders() {
        $query = "SELECT COUNT(id) AS total_orders FROM orders";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_orders'] ?? 0;
    }

    private function getSalesPerDay() {
        $query = "
            SELECT DATE(created_at) AS date, SUM(total_price) AS sales 
            FROM orders 
            WHERE shipping_status = 'Delivered' AND payment_status = 'Paid'
            GROUP BY DATE(created_at)
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRecentUserRegistrations() {
        $query = "
            SELECT id, username, CONCAT(first_name, ' ', last_name) AS full_name, created_at 
            FROM users 
            ORDER BY created_at DESC 
            LIMIT 10
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    

    private function getTopSellingProducts() {
        $query = "
            SELECT watch_id, COUNT(watch_id) AS sold 
            FROM order_items 
            JOIN orders ON order_items.order_id = orders.id
            WHERE orders.shipping_status = 'Delivered' AND orders.payment_status = 'Paid'
            GROUP BY watch_id 
            ORDER BY sold DESC 
            LIMIT 5
        ";
        $stmt = $this->pdo->query($query);
        $topSelling = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Fetch product details
        foreach ($topSelling as &$item) {
            $query = "SELECT model_name FROM watches WHERE id = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$item['watch_id']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $item['model_name'] = $product['model_name'] ?? 'Unknown';
        }
        return $topSelling;
    }
    
    
    

    public function getRecentOrders() {
        $sql = "SELECT o.id, 
                       CONCAT(u.first_name, ' ', u.last_name) AS customer_name, 
                       o.created_at AS order_date, 
                       o.total_price, 
                       o.status, 
                       o.shipping_status,
                       o.payment_status
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.status = 'Accepted'
                  AND o.shipping_status IN ('Pending', 'Shipped')
                ORDER BY o.created_at DESC
                LIMIT 10"; // Fetching the last 10 orders
    
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getRecentOrdersPending() {
        $sql = "SELECT o.id, 
                       CONCAT(u.first_name, ' ', u.last_name) AS customer_name, 
                       o.created_at AS order_date, 
                       o.total_price, 
                       o.status, 
                       o.shipping_status,
                       o.payment_status
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5"; // Fetching the last 10 orders
    
        return $this->pdo->query($sql)->fetchAll();
    }
    

    private function getPendingOrderCount() {
        $query = "SELECT COUNT(id) AS pending_orders FROM orders WHERE status = 'Pending'";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pending_orders'] ?? 0;
    }

    private function getTotalPendingAmount() {
        $query = "SELECT SUM(total_price) AS total_pending_amount FROM orders WHERE status = 'Pending'";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_pending_amount'] ?? 0;
    }

    public function getPendingOrders() {
        $query = "
            SELECT o.id, CONCAT(u.first_name, ' ', u.last_name) AS customer_name, u.email, o.created_at AS order_date, o.total_price, o.payment_status, o.shipping_status
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.status = 'Pending'
            ORDER BY o.created_at DESC
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function handleOrderStatus() {
        if (isset($_POST['order_id']) && isset($_POST['shipping_status']) && isset($_POST['payment_status'])) {
            $orderId = (int)$_POST['order_id'];
            $shippingStatus = htmlspecialchars($_POST['shipping_status']);
            $paymentStatus = htmlspecialchars($_POST['payment_status']);
    
            // Validate the status values
            $validShippingStatuses = ['Pending', 'Shipped', 'Delivered', 'Canceled'];
            $validPaymentStatuses = ['Paid', 'Unpaid'];
    
            if (in_array($shippingStatus, $validShippingStatuses) && in_array($paymentStatus, $validPaymentStatuses)) {
                $this->changeOrderStatus($orderId, $shippingStatus, $paymentStatus);
                $_SESSION['order_status_update'] = 'Order status updated successfully.';
            } else {
                $_SESSION['order_status_update'] = 'Invalid status.';
            }
        }
    }
    
    
    public function changeOrderStatus($orderId, $shippingStatus, $paymentStatus) {
        // Update order status
        $sql = "UPDATE orders SET shipping_status = ?, payment_status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$shippingStatus, $paymentStatus, $orderId]);
    
        // If the order is marked as Delivered and Paid, reduce the product stock
        if ($shippingStatus === 'Delivered' && $paymentStatus === 'Paid') {
            $this->reduceProductStock($orderId);
        }
    }
    
    // Reduce stock quantity based on the order items
    private function reduceProductStock($orderId) {
        // Fetch the order items for the given order
        $sql = "SELECT watch_id, quantity FROM order_items WHERE order_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$orderId]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Loop through each order item and reduce stock for each product
        foreach ($orderItems as $item) {
            $watchId = $item['watch_id'];
            $quantityOrdered = $item['quantity'];
    
            // Fetch the current stock quantity of the product
            $sql = "SELECT stock_quantity FROM watches WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$watchId]);
            $currentStock = $stmt->fetch(PDO::FETCH_ASSOC)['stock_quantity'];
    
            // Calculate the new stock quantity
            $newStock = $currentStock - $quantityOrdered;
    
            // Update the product's stock quantity
            $sql = "UPDATE watches SET stock_quantity = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$newStock, $watchId]);
        }
    }
    
    


    public function addProduct() {
        $currentPage = $_POST['current_page'] ?? 1;
        $imageBlob = $this->uploadImage($_FILES['image']);
        $data = $this->getProductDataFromPost();
        $data['image'] = $imageBlob;

        $this->watchModel->createWatch($data);
        $this->redirectToDashboard('products', $currentPage);
    }

    private function editProduct() {
        $watch_id = $_POST['watch_id'] ?? null;
        $currentPage = $_POST['current_page'] ?? 1;
        if (!$watch_id) {
            $this->sendError('Watch ID is missing');
        }

        $data = $this->getProductDataFromPost();
        $data['id'] = $watch_id;
        $data['image'] = $this->handleImageUpdate($watch_id);

        $this->watchModel->updateWatch($watch_id, $data);
        $this->redirectToDashboard('products', $currentPage);
    }

    private function deleteWatch() {
        $watchId = $_POST['watch_id'] ?? null;
        $currentPage = $_POST['current_page'] ?? 1;
        
        if ($watchId == null) {
            echo "Watch id is missing";
        }
    
        $this->watchModel->deleteWatch($watchId);
        $this->redirectToDashboard('products', $currentPage);
    }

    private function uploadImage($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            if ($file['size'] > 2097152) {
                $this->sendError('File size exceeds the 2MB limit.');
            }

            $validTypes = ['image/jpeg', 'image/png'];
            if (!in_array($file['type'], $validTypes)) {
                $this->sendError('Invalid file type. Only JPEG and PNG are allowed.');
            }

            return file_get_contents($file['tmp_name']);
        }

        return null;
    }

    private function handleImageUpdate($watch_id) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            return $this->uploadImage($_FILES['image']);
        } else {
            $existingWatch = $this->watchModel->getWatch($watch_id);
            return $existingWatch['image'];
        }
    }

    private function getProductDataFromPost() {
        return [
            'model_name' => $_POST['model_name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'stock_quantity' => $_POST['stock_quantity'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'Active',
            'watch_category' => $_POST['category']
        ];
    }

    private function redirectToDashboard($section, $page = null) {
        // Build the URL with the section and optionally the page parameter
        $url = "/admin_dashboard?section=" . urlencode($section);

        
        if ($page !== null) {
            $url .= "&page=" . urlencode($page);
            
        }
        
    
        header("Location: $url");
        
    }

    private function sendError($message) {
        die($message);
    }

    public function getUsers() {
        $query = "SELECT id, username, email, created_at, is_admin FROM users";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function updateUser() {
        $userId = $_POST['user_id'] ?? null;
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $isAdmin = isset($_POST['is_admin']) ? filter_var($_POST['is_admin'], FILTER_VALIDATE_BOOLEAN) : false;
    
        if (!$userId) {
            $this->sendError('User ID is missing');
        }
    
        $sql = "UPDATE users SET username = :username, email = :email, is_admin = :is_admin WHERE id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':is_admin' => $isAdmin,
            ':user_id' => $userId
        ]);
    
        $this->redirectToDashboard('users');
    }

    private function deleteUser() {
        $userId = $_POST['user_id'] ?? null;
        if (!$userId) {
            $this->sendError('User ID is missing');
        }
    
        $sql = "DELETE FROM users WHERE id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
    
        $this->redirectToDashboard('users');
    }

    public function handleOrderAction() {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is admin
        if (!isset($_SESSION['admin_id'])) {
            echo "Unauthorized access.";
            return;
        }

        $action = $_POST['action'] ?? null;
        $orderId = $_POST['order_id'] ?? null;

        if ($action && $orderId) {
            switch ($action) {
                case 'accept':
                    $this->acceptOrder($orderId);
                    break;
                case 'delete':
                    $this->deleteOrder($orderId);
                    break;
                default:
                    echo "Invalid action.";
                    break;
            }
        } else {
            echo "Action or Order ID missing.";
        }
    }

    

    public function fetchOrderDetails($orderId) {
        $orderDetails = $this->getOrderDetails($orderId);
        return $orderDetails;
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT orders.*, users.username, users.email
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.id = :order_id
        ");
        $stmt->execute([':order_id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $stmt = $this->pdo->prepare("SELECT * FROM addresses WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $order['user_id']]);
            $address = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->prepare("
                SELECT order_items.*, watches.model_name, watches.image
                FROM order_items
                JOIN watches ON order_items.watch_id = watches.id
                WHERE order_items.order_id = :order_id
            ");
            $stmt->execute([':order_id' => $orderId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'order' => $order,
                'address' => $address,
                'items' => $items
            ];
        } else {
            return null;
        }
    }


    private function acceptOrder($orderId) {
        // Update order status to accepted
        $stmt = $this->pdo->prepare("UPDATE orders SET status = 'Accepted' WHERE id = :order_id");
        $stmt->execute([':order_id' => $orderId]);
    
        // Set a success message
        $_SESSION['order_accepted'] = 'Order Accepted.';
    
        // Redirect back to the same page with order_id
        header('Location: /admin_dashboard?section=pending-orders');
    }
    
    private function deleteOrder($orderId) {
        // Delete order
        $stmt = $this->pdo->prepare("DELETE FROM orders WHERE id = :order_id");
        $stmt->execute([':order_id' => $orderId]);
    
        // Set a success message
        $_SESSION['order_deleted'] = 'Order Deleted.';
    
        // Redirect back to the same page with order_id
        header('Location: /admin_dashboard?section=pending-orders');
    }

    public function searchOrders($query) {
        $sql = "
            SELECT o.id, o.customer_name, o.created_at, o.total_price, o.payment_status, o.shipping_status, u.email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE (o.id = :query OR o.customer_name LIKE :queryLike)
            AND o.status = 'Pending'
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $queryParam = '%' . $query . '%';
        $stmt->bindParam(':query', $query, PDO::PARAM_INT);
        $stmt->bindParam(':queryLike', $queryParam, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($query) {
        $sql = "SELECT id, username, email, created_at, is_admin
                FROM users
                WHERE username LIKE :query OR email LIKE :query";
        
        $stmt = $this->pdo->prepare($sql);
        $queryParam = '%' . $query . '%';
        $stmt->bindParam(':query', $queryParam, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchWatches($query) {
        $sql = "SELECT * FROM watches WHERE id = :query";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();
        $watches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Generate HTML rows for watches
        $html = '';
        foreach ($watches as $watch) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($watch['id']) . '</td>';
            $html .= '<td>' . ($watch['image'] ? '<img src="data:image/jpeg;base64,' . base64_encode($watch['image']) . '" alt="Product Image" width="50px" />' : '<img src="path/to/default/image.jpg" alt="Default Image" width="50px" />') . '</td>';
            $html .= '<td>' . htmlspecialchars($watch['model_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($watch['price']) . '</td>';
            $html .= '<td>' . htmlspecialchars($watch['stock_quantity']) . '</td>';
            
            $html .= '<td>' . (strlen($watch['description']) > 30 ? htmlspecialchars(substr($watch['description'], 0, 30)) . '...' : htmlspecialchars($watch['description'])) . '</td>';
            $html .= '<td>' . htmlspecialchars($watch['status']) . '</td>';
            $html .= '<td>' . htmlspecialchars($watch['watch_category']) . '</td>';
            $html .= '<td>
                        <div class="btn-group" style="gap: 5px;">
                            <a href="#editWatchModal" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-id="' . htmlspecialchars($watch['id']) . '"
                                data-model-name="' . htmlspecialchars($watch['model_name']) . '"
                                data-price="' . htmlspecialchars($watch['price']) . '"
                                data-stock-quantity="' . htmlspecialchars($watch['stock_quantity']) . '"
                                data-description="' . htmlspecialchars($watch['description']) . '"
                                data-status="' . htmlspecialchars($watch['status']) . '"
                                data-category="' . htmlspecialchars($watch['watch_category']) . '"
                                data-image-src="' . ($watch['image'] ? 'data:image/jpeg;base64,' . base64_encode($watch['image']) : '') . '">
                                Edit
                            </a>
                            <a href="#deleteProductModal" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-id="' . htmlspecialchars($watch['id']) . '">
                                Delete
                            </a>
                        </div>
                      </td>';
            $html .= '</tr>';
        }
    
        // Return the HTML content directly
        echo $html;
        exit;
    }
    
    
    

    
    
    
    
    
    


    
    
    

}
?>
