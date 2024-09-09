<?php
// User.php
require_once '../includes/database.php';

class User {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../includes/database.php';
        $this->pdo = (new Database())->connect();
    }
    
    public static function getUserByEmail($email) {
        $pdo = (new Database())->connect();
        $sql = 'SELECT id, username, first_name, last_name, email, password_hash FROM users WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return associative array
    }

    public static function getUserById($userId) {
        $pdo = (new Database())->connect();
        $sql = 'SELECT id, password_hash FROM users WHERE id = :user_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return associative array
    }

    public static function createUser($email, $username, $firstName, $lastName, $password) {
        $pdo = (new Database())->connect();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = 'INSERT INTO users (email, username, first_name, last_name, password_hash) VALUES (:email, :username, :first_name, :last_name, :password)';
        $stmt = $pdo->prepare($sql);
        
        try {
            return $stmt->execute([
                ':email' => $email,
                ':username' => $username,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':password' => $hashedPassword,
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function updateUserProfile($userId, $firstName, $lastName, $email, $phoneNumber) {
        $sql = 'UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number WHERE id = :user_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':user_id' => $userId,
            ':phone_number' => $phoneNumber
        ]);
    }

    public function updateAddress($userId, $addressLine, $city, $state, $country) {
        try {
            $this->pdo->beginTransaction();

            $stmtCheck = $this->pdo->prepare('SELECT COUNT(*) FROM addresses WHERE user_id = :user_id');
            $stmtCheck->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            if ($count > 0) {
                $sqlAddress = 'UPDATE addresses SET address_line = :address_line, city = :city, state = :state, country = :country WHERE user_id = :user_id';
                $stmtAddress = $this->pdo->prepare($sqlAddress);
                $stmtAddress->execute([
                    ':address_line' => $addressLine,
                    ':city' => $city,
                    ':state' => $state,
                    ':country' => $country,
                    ':user_id' => $userId
                ]);
            } else {
                $sqlInsert = 'INSERT INTO addresses (user_id, address_line, city, state, country) VALUES (:user_id, :address_line, :city, :state, :country)';
                $stmtInsert = $this->pdo->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':user_id' => $userId,
                    ':address_line' => $addressLine,
                    ':city' => $city,
                    ':state' => $state,
                    ':country' => $country
                ]);
            }

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo 'Error: ' . $e->getMessage();
        }
    }

    public static function savePasswordResetToken($email, $token, $expiry) {
        $pdo = (new Database())->connect();
        $sql = 'UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':token' => $token,
            ':expiry' => date('Y-m-d H:i:s', $expiry), // Ensure correct format
            ':email' => $email
        ]);
    }
    
    
    
    
    public static function getUserByToken($token) {
        $pdo = (new Database())->connect();
        $sql = 'SELECT email, reset_token_expiry FROM users WHERE reset_token = :token';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public static function updatePassword($email, $hashedPassword) {
        $pdo = (new Database())->connect();
        $sql = 'UPDATE users SET password_hash = :password_hash WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':password_hash' => $hashedPassword,
            ':email' => $email
        ]);
    }
    
    public static function removePasswordResetToken($email) {
        $pdo = (new Database())->connect();
        $sql = 'UPDATE users SET reset_token = NULL, reset_token_expiry = NULL WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
    }
    
    

    
}
?>
