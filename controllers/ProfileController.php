<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProfileController {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../includes/database.php';
        $this->pdo = (new Database())->connect();
    }
    
    public function profile() {
        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['return_url'] = urlencode($_SERVER['REQUEST_URI']);
            header('Location: /login');
            exit();
        }
        $homeController = new HomeController();
        $data = $homeController->getUserData();  // Reuse logic from HomeController
        
        // Pass data to the profile view
        include __DIR__ . '/../public/views/profile.php';
    }

    // Handle profile update
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $this->updateProfile();
            } elseif (isset($_POST['change_password'])) {
                $this->changePassword();
            }
        }
    }

    private function updateProfile() {
        // Sanitize and validate input
        $firstName = htmlspecialchars($_POST['first_name'] ?? '');
        $lastName = htmlspecialchars($_POST['last_name'] ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $phoneNumber = htmlspecialchars($_POST['phone_number'] ?? '');
        $sanitized_phone_number = preg_replace('/[^0-9\(\)\-\+\s]/', '', $phoneNumber);

        if (strlen($sanitized_phone_number) !== 11) {
            // Redirect with an error message
            $_SESSION['profile_update_error'] = 'Phone number must be exactly 11 digits.';
            header('Location: /profile?section=profile&error=1');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
            $_SESSION['profile_update_error'] = 'Invalid email address. It must end with @gmail.com.';
            header('Location: /profile?section=profile&error=1');
            exit();
        }

       
        

        
        // Address fields
        $addressLine = htmlspecialchars($_POST['address_line'] ?? '');
        $city = htmlspecialchars($_POST['city'] ?? '');
        $state = htmlspecialchars($_POST['state'] ?? '');
        $country = htmlspecialchars($_POST['country'] ?? '');
        $userId = $_SESSION['user_id'] ?? null;
        
        if ($userId) {
            $userModel = new User();

            $userModel->updateUserProfile($userId, $firstName, $lastName, $email, $sanitized_phone_number);
            $userModel->updateAddress($userId, $addressLine, $city, $state, $country);

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profile_image']['tmp_name'];
                $fileName = $_FILES['profile_image']['name'];
                $uploadPath = __DIR__ . '/../uploads/profile_images/' . $fileName;

                if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                    $sql = 'UPDATE users SET profile_image = :profile_image WHERE id = :user_id';
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([':profile_image' => $fileName, ':user_id' => $userId]);
                }
            }
            $_SESSION['profile_update'] = 'Profile Updated.';
            header('Location: /profile?section=profile&success=1');
            exit();
        }
    }

    private function changePassword() {
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;


        

        if ($userId && $newPassword === $confirmPassword) {
            $user = User::getUserById($userId);
            if (password_verify($oldPassword, $user['password_hash'])) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $sql = 'UPDATE users SET password_hash = :password_hash WHERE id = :user_id';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':password_hash' => $hashedNewPassword, ':user_id' => $userId]);
                $_SESSION['profile_success'] = 'Password Updated.';

                header('Location: /profile?section=change_password&success=1');
                exit();
            } else {
                $_SESSION['profile_error'] = 'Old password is incorrect.';
                header('Location: /profile?section=change_password');
                exit();
            }
        } else {
            $_SESSION['profile_error'] = 'Passwords do not match or user not found.';
            header('Location: /profile?section=change_password');
            exit();
        }
    }
}
?>
