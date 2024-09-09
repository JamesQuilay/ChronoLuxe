<?php

class HomeController {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../includes/database.php';  // Ensure this path is correct
        $this->pdo = (new Database())->connect();  // Initialize PDO connection
    }
    
    public function index() {
        require_once __DIR__ . '/../models/Watch.php';
        $watchModel = new Watch($this->pdo);

        // Get the current page from the query string (default to page 1)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8;
        $offset = ($page - 1) * $limit;

        // Fetch the products and total count
        $watches = $watchModel->listWatches($limit, $offset);
        $totalWatches = $watchModel->getTotalWatches();

        // Calculate total pages
        $totalPages = ceil($totalWatches / $limit);

        $cartCount = 0;
        if (isset($_SESSION['user_id'])) {
            $cartCount = $this->getCartCount($_SESSION['user_id']);
        }

        // Include the view and pass variables
        include __DIR__ . '/../public/views/shop.php';
    }

    public function frontPage() {
        // Show featured products
        include __DIR__ . '/../public/views/frontPage.php';
    }

    public function getUserData() {
        $userId = $_SESSION['user_id'] ?? null;
        $user = [];
        $address = [];
        
        if ($userId) {
            require_once __DIR__ . '/../includes/database.php'; // Include your database connection
            
            // Create a PDO instance
            $pdo = (new Database())->connect();

            // Fetch user data
            $userSql = 'SELECT username, first_name, last_name, email, phone_number FROM users WHERE id = :user_id';
            $userStmt = $pdo->prepare($userSql);
            $userStmt->execute([':user_id' => $userId]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC) ?: ['username' => '', 'first_name' => '', 'last_name' => '', 'email' => '', 'phone_number' => ''];

            // Fetch address data
            $addressSql = 'SELECT address_line, city, state, country FROM addresses WHERE user_id = :user_id';
            $addressStmt = $pdo->prepare($addressSql);
            $addressStmt->execute([':user_id' => $userId]);
            $address = $addressStmt->fetch(PDO::FETCH_ASSOC) ?: ['address_line' => '', 'city' => '', 'state' => '', 'country' => ''];
        }

        return [
            'user' => $user,
            'address' => $address
        ];
    }

    public function profile() {
        $data = $this->getUserData();
        
        // Pass data to the view
        include __DIR__ . '/../views/profile.php';
    }

    public function productDetails($productId) {
        // Fetch the product details from the database
        $stmt = $this->pdo->prepare("SELECT * FROM watches WHERE id = :id");
        $stmt->execute([':id' => $productId]);
        $watch = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($watch) {
            include __DIR__ . '/../public/views/product_details.php';
        } else {
            echo "Product not found.";
        }
    }

    private function getProductIdFromUri($uri) {
        $parts = explode('/', $uri);
        return end($parts); // Last part should be the product ID
    }

    public function pageNotFound() {
        include __DIR__ . '/../public/views/404.php';
    }

    public function addToCart() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $watchId = isset($_POST['watch_id']) ? (int)$_POST['watch_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
        if ($watchId <= 0 || $quantity <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity.']);
            return;
        }
    
        try {
            $this->pdo->beginTransaction();
    
            // Fetch the available stock for the product
            $sql = "SELECT stock_quantity FROM watches WHERE id = :watch_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':watch_id' => $watchId]);
            $watch = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$watch) {
                echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
                $this->pdo->rollBack();
                return;
            }
    
            $availableStock = $watch['stock_quantity'];
    
            // Fetch the current quantity in the cart for this item
            $sql = "SELECT quantity FROM cart WHERE user_id = :user_id AND watch_id = :watch_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':watch_id' => $watchId
            ]);
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentQuantityInCart = $cartItem['quantity'] ?? 0;
    
            // Calculate the new quantity to be added to the cart
            $newQuantityInCart = $currentQuantityInCart + $quantity;
    
            // Check if requested quantity exceeds available stock
            if ($newQuantityInCart > $availableStock) {
                $this->pdo->rollBack();
                return;
            }
    
            // Add or update item in cart
            if ($currentQuantityInCart > 0) {
                // Update existing item in cart
                $sql = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND watch_id = :watch_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':quantity' => $newQuantityInCart,
                    ':user_id' => $userId,
                    ':watch_id' => $watchId
                ]);
            } else {
                // Insert new item into cart
                $sql = "INSERT INTO cart (user_id, watch_id, quantity)
                        VALUES (:user_id, :watch_id, :quantity)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userId,
                    ':watch_id' => $watchId,
                    ':quantity' => $quantity
                ]);
            }
    
            // Get updated quantity
            $sql = "SELECT quantity FROM cart WHERE user_id = :user_id AND watch_id = :watch_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':watch_id' => $watchId
            ]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            $itemQuantity = $item['quantity'] ?? 0;
    
            // Commit transaction
            $this->pdo->commit();
    
            // Return the updated cart count and item quantity
            $cartCount = $this->getCartCount($userId);
            echo json_encode([
                'status' => 'success',
                'cartCount' => $cartCount,
                'itemQuantity' => $itemQuantity
            ]);
    
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    
    private function getCartCount($userId) {
        $sql = "SELECT COUNT(DISTINCT watch_id) AS total FROM cart WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function blog1() {
        include __DIR__ . '/../public/views/blog1.php';

    }
    public function blog2() {
        include __DIR__ . '/../public/views/blog2.php';

    }
    public function blog3() {
        include __DIR__ . '/../public/views/blog3.php';

    }
    
    
}
