<?php

class OrderController {
    private $db;

    public function __construct() {
        include_once '../includes/database.php';
        $database = new Database();
        $this->db = $database->connect();
    }

    // Method to fetch order confirmation details
    public function confirmation($orderId) {
        // Start the session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user_id is set in the session
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user_id'];

        // Fetch the order details
        $orderDetails = $this->getOrderDetails($orderId, $userId);

        // If order not found, redirect to error or another page
        if (empty($orderDetails)) {
            include  '../public/views/404.php';
            return;
        }

        // Fetch the order items
        $orderItems = $this->getOrderItems($orderId);

        // Pass data to the view
        include '../public/views/order_confirmation.php';
    }

    // Method to get the order details
    private function getOrderDetails($orderId, $userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT o.id, o.total_price, o.created_at, o.status, u.first_name, u.last_name
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.id = :order_id AND o.user_id = :user_id
            ");
            $stmt->execute([':order_id' => $orderId, ':user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Method to get the order items
    private function getOrderItems($orderId) {
        try {
            $stmt = $this->db->prepare("
                SELECT oi.quantity, oi.price_at_order, w.model_name, w.image
                FROM order_items oi
                JOIN watches w ON oi.watch_id = w.id
                WHERE oi.order_id = :order_id
            ");
            $stmt->execute([':order_id' => $orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    public function status() {
        // Start the session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Check if user_id is set in the session
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['return_url'] = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit();
        }
    
        $userId = $_SESSION['user_id'];
    
        // Fetch user's orders
        $stmt = $this->db->prepare("
            SELECT * FROM orders 
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Fetch user's addresses
        $stmt = $this->db->prepare("
            SELECT * FROM addresses 
            WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Fetch order items with product details for each order
        foreach ($orders as &$order) {
            $stmt = $this->db->prepare("
                SELECT oi.*, w.model_name, w.image
                FROM order_items oi
                JOIN watches w ON oi.watch_id = w.id
                WHERE oi.order_id = :order_id
            ");
            $stmt->execute([':order_id' => $order['id']]);
            $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Include the view
        include __DIR__ . '/../public/views/order_status.php';
    }
    
    
}
