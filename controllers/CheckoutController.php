<?php

class CheckoutController {
    private $db;

    public function __construct() {
        include_once '../includes/database.php'; 
        $database = new Database();
        $this->db = $database->connect(); // Establish PDO connection
    }

    // Method to fetch data and show the checkout page
    public function index($watchId = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // Store the current URL to redirect back after login
            $_SESSION['return_url'] = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit();
        }
    
        $data = [
            'user' => [],
            'cart_items' => [],
            'total_amount_to_pay' => 0
        ];
    
       
    
        $userId = $_SESSION['user_id'];
        $data['user'] = $this->getUserDetails($userId);

        if (!$watchId && isset($_GET['watch_id'])) {
            $watchId = $_GET['watch_id'];
        }
    
        if ($watchId) {
            // Direct checkout for a specific product (not from the cart)
            $data['cart_items'] = $this->getDirectCheckoutItem($watchId);

            
        } else {
            // Fetch cart items for standard checkout
            $data['cart_items'] = $this->getCartItems($userId);
        }
    
        if (empty($data['cart_items'])) {
            header('Location: /cart');
            exit();
        }
    
        $data['total_amount_to_pay'] = $this->calculateTotalAmount($data['cart_items']);
        include '../public/views/checkout.php';
    }
    
    
    // Example methods for fetching user details and cart items
    private function getUserDetails($userId) {
        try {
            // Prepare and execute the query to fetch user and address details
            $stmt = $this->db->prepare("
                SELECT u.email, u.first_name, u.last_name, u.phone_number,
                       a.address_line, a.city, a.state, a.country
                FROM users u
                LEFT JOIN addresses a ON u.id = a.user_id
                WHERE u.id = :userId
            ");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Fetch the result as an associative array
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Return the result
            return $result;
        } catch (PDOException $e) {
            echo 'Query Error: ' . $e->getMessage();
            return [];
        }
    }
    
    
    
    private function getCartItems($userId) {
        try {
            // Fetch all cart items for the user
            $stmt = $this->db->prepare("
                SELECT c.quantity, w.id AS product_id, w.model_name, w.price, w.image
                FROM cart c
                JOIN watches w ON c.watch_id = w.id
                WHERE c.user_id = :userId
            ");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Check if items are fetched
            if (empty($items)) {
                // Log empty cart or item not found
                error_log('Cart is empty for user ID: ' . $userId);
            }
            return $items;
        } catch (PDOException $e) {
            // Log error
            error_log('Query Error: ' . $e->getMessage());
            return [];
        }
    }

    private function getDirectCheckoutItem($watchId) {
        try {
            // Fetch only the specified item for direct checkout
            $stmt = $this->db->prepare("
                SELECT w.id AS product_id, w.model_name, w.price, w.image, 1 AS quantity
                FROM watches w
                WHERE w.id = :watchId
            ");
            $stmt->bindParam(':watchId', $watchId, PDO::PARAM_INT);
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($item) {
                return [$item]; // Return as an array since it's a single item
            } else {
                error_log('Item not found for watch ID: ' . $watchId);
                return [];
            }
        } catch (PDOException $e) {
            // Log error
            error_log('Query Error: ' . $e->getMessage());
            return [];
        }
    }
    
    
    
    
    
    
    
    private function calculateTotalAmount($cartItems) {
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }
        return $totalAmount;
    }

    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $watchId = isset($_POST['watch_id']) ? intval($_POST['watch_id']) : null; // Only use watch_id if submitted
    
        try {
            // Check if this is a direct checkout or regular cart checkout
            if ($watchId) {
                // Direct checkout for a specific watch
                $cartItems = $this->getDirectCheckoutItem($watchId);
            } else {
                // Regular checkout with cart items
                $cartItems = $this->getCartItems($userId);
            }
    
            if (empty($cartItems)) {
                echo json_encode(['error' => 'Cart is empty']);
                http_response_code(400);
                return;
            }
    
            $userDetails = $this->getUserDetails($userId);
            if (empty($userDetails['address_line'])) {
                echo json_encode(['error' => 'You must set an address first']);
                http_response_code(400);
                return;
            }

            if (empty($userDetails['phone_number'])) {
                echo json_encode(['error' => 'You must set an phone number first']);
                http_response_code(400);
                return;
            }
    
            // Calculate totals
            $totalAmount = $this->calculateTotalAmount($cartItems);
            $customerName = $userDetails['first_name'] . ' ' . $userDetails['last_name'];
            $orderDate = date('Y-m-d H:i:s');
    
            // Process the order in the database
            $this->db->beginTransaction();
    
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total_price, created_at, status, payment_status, shipping_status, customer_name)
                VALUES (:user_id, :total_price, :created_at, 'Pending', 'Pending', 'Pending', :customer_name)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':total_price' => $totalAmount,
                ':created_at' => $orderDate,
                ':customer_name' => $customerName
            ]);
    
            $orderId = $this->db->lastInsertId();
    
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, watch_id, quantity, price_at_order)
                VALUES (:order_id, :watch_id, :quantity, :price_at_order)
            ");
    
            foreach ($cartItems as $item) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':watch_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price_at_order' => $item['price']
                ]);
            }
    
            // If it was a regular cart checkout, clear the cart after purchase
            if (!$watchId) {
                $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = :user_id");
                $stmt->execute([':user_id' => $userId]);
            }
    
            $this->db->commit();
            echo json_encode(['success' => true, 'orderId' => $orderId]);
    
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            http_response_code(500);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
?>
