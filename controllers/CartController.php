<?php
class CartController {
    private $db;
    private $cart;

    public function __construct() {
        include_once '../includes/database.php'; 
        include_once '../models/Cart.php'; 
        $database = new Database();
        $this->db = $database->connect();
        $this->cart = new Cart($this->db);
    }


    

    public function home() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['return_url'] = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit();
        }
        
        $userId = $_SESSION['user_id']; 
        
        $query = 'SELECT cart.*, watches.model_name, watches.price, watches.stock_quantity, watches.image
                  FROM cart
                  JOIN watches ON cart.watch_id = watches.id
                  WHERE cart.user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['quantity'] * $item['price'];
        }

        include __DIR__ . '/../public/views/cart.php';
    }

    public function updateCart() {
        // Get the POST data
        $data = json_decode(file_get_contents("php://input"), true);
        $cart_id = $data['cart_id'] ?? null;
        $action = $data['action'] ?? null;
    
        if ($cart_id && $action) {
            // Fetch the cart item using the cart_id
            $cart_item = Cart::find($cart_id);
            if ($cart_item) {
                // Fetch the related product to check stock
                $stmt = $this->db->prepare('SELECT * FROM watches WHERE id = :watch_id');
                $stmt->execute(['watch_id' => $cart_item->watch_id]);
                $product = $stmt->fetch(PDO::FETCH_OBJ);
    
                if ($product) {
                    if ($action == 'increase') {
                        // Increase quantity only if it's less than the available stock
                        if ($cart_item->quantity < $product->stock_quantity) {
                            $cart_item->quantity += 1;  // Increase the quantity
                            $cart_item->save();         // Save the updated quantity
    
                            echo json_encode(['success' => true]);
                            return;
                        } else {
                           
                            http_response_code(400);
                            return;
                        }
                    } elseif ($action == 'decrease') {
                        // Decrease quantity or remove the item if it reaches zero
                        if ($cart_item->quantity > 1) {
                            $cart_item->quantity -= 1;
                            $cart_item->save(); // Save the updated quantity
                            echo json_encode(['success' => true]);
                            return;
                        } else {
                            Cart::delete($cart_id);
                            echo json_encode(['success' => true]);
                            return;
                        }
                    }
                } else {
                    echo json_encode(['error' => 'Product not found']);
                    http_response_code(404);
                }
            } else {
                echo json_encode(['error' => 'Cart item not found']);
                http_response_code(404);
            }
        } else {
            echo json_encode(['error' => 'Invalid request']);
            http_response_code(400);
        }
    }
    

    

    public function removeCartItem() {
        // Get the POST data
        $data = json_decode(file_get_contents("php://input"), true);
        $cart_id = $data['cart_id'] ?? null;

        if ($cart_id) {
            // Fetch the cart item using the cart_id
            $cart_item = Cart::find($cart_id); // Assuming Cart is a model class
            if ($cart_item) {
                // Remove the cart item
                Cart::delete($cart_id); // Assuming delete() removes from DB
                echo json_encode(['success' => true]);
                return;
            }
        }

        echo json_encode(['error' => 'Item not found']);
        http_response_code(404);
    }

    public function cartCount() {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Ensure the user_id is set
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            http_response_code(403);
            return;
        }
    
        $user_id = $_SESSION['user_id']; // Get the current logged-in user's ID
    
        // Create a new Cart instance with the database connection
        $cart = new Cart($this->db); 
    
        // Count the unique items in the user's cart
        $cart_count = Cart::countItems($this->db, $user_id);
    
        echo json_encode(['cart_count' => $cart_count]);
    }

}
?>
