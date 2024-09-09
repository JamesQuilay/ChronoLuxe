<?php
class Cart {
    private $db;
    public $id;
    public $watch_id;
    public $quantity;

    public function __construct($db) {
        $this->db = $db;
    }

    // Method to find a cart item by cart_id
    public static function find($cart_id) {
        $db = (new Database())->connect();
        $stmt = $db->prepare('SELECT * FROM cart WHERE id = :id');
        $stmt->execute(['id' => $cart_id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result) {
            $cart = new self($db);
            $cart->id = $result->id;
            $cart->watch_id = $result->watch_id;
            $cart->quantity = $result->quantity;
            return $cart;
        } else {
            return null;
        }
    }

    // Method to delete a cart item by cart_id
    public static function delete($cart_id) {
        $db = (new Database())->connect();
        $stmt = $db->prepare('DELETE FROM cart WHERE id = :id');
        $stmt->execute(['id' => $cart_id]);
    }

    // Method to update the quantity of a cart item
    public function save() {
        try {
            $stmt = $this->db->prepare('UPDATE cart SET quantity = :quantity WHERE id = :id');
            $stmt->execute(['quantity' => $this->quantity, 'id' => $this->id]);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
        }
    }

    public static function countItems($db, $user_id) {
        try {
            $stmt = $db->prepare('SELECT COUNT(*) AS count FROM cart WHERE user_id = :user_id');
            $stmt->execute(['user_id' => $user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return 0;
        }
    }
}
?>
