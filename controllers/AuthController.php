<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../models/User.php';

class AuthController {
    private $pdo;
    private $user;

    public function __construct() {
        $this->user = new User();  // No need for $db here as User handles its own DB connection
        include_once '../includes/database.php'; 
        $database = new Database();
        $this->pdo = $database->connect(); // Establish the PDO connection
    }

    public function showLoginForm() {
        include '../public/views/login.php';
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
                include '../public/views/login.php';
                return;
            }
    
            $user = User::getUserByEmail($email);
    
            if ($user && password_verify($password, $user['password_hash'])) {
                // Password is correct, start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
    
                if (isset($_POST['remember'])) {
                    setcookie('user', $email, time() + 86400 * 30, '/'); // Remember user for 30 days
                }
    
                if (isset($_SESSION['return_url'])) {
                    $returnUrl = urldecode($_SESSION['return_url']);
                    unset($_SESSION['return_url']);
                    header('Location: ' . $returnUrl);
                    exit;
                } else {
                    header('Location: /frontPage'); // Default redirection if no return URL is set
                    exit;
                }
            } else {
                $error = 'Invalid email or password.';
                include '../public/views/login.php';
            }
        }
    }
    
    
    public function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();

        }

        setcookie('user', '', time() - 3600, '/'); // Remove cookie
        header('Location: /');
        exit;
    }

    public function showRegistrationForm() {
        include '../public/views/sign_up.php';
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $password = trim($_POST['password']);
            $password2 = trim($_POST['password2']);

            if ($password !== $password2) {
                $error = 'Passwords do not match.';
                include '../public/views/sign_up.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
                include '../public/views/sign_up.php';
                return;
            }

            if (strlen($password) < 8) {
                $error = 'Password must be at least 8 characters long.';
                include '../public/views/sign_up.php';
                return;
            }

            if (User::getUserByEmail($email)) {  // Call static method directly
                $error = 'Email already registered.';
                include '../public/views/sign_up.php';
                return;
            }

            $username = strtolower("{$firstName}.{$lastName}@" . bin2hex(random_bytes(6)));
            if (User::createUser($email, $username, $firstName, $lastName, $password)) {  // Call static method directly
                $_SESSION['success'] = 'Registration successful, you can now log in.';
                header('Location: /login');
                exit;
            } else {
                $error = 'Registration failed.';
                include '../public/views/sign_up.php';
            }
        }
    }

    public function showAdminLoginForm() {
        include '../admin/views/admin_login.php';
    }

    public function adminLogin() {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Check if the form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Fetch email and password from form submission
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
    
            // Sanitize input to prevent SQL injection
            $email = htmlspecialchars($email);
    
            // Validate the input
            if (!empty($email) && !empty($password)) {
                try {
                    // Query to find the user by email and check if they are an admin
                    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email AND is_admin = 1 LIMIT 1");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    

    
                    // Check if admin exists and verify password
                    if ($user && password_verify($password, $user['password_hash'])) {  // FIXED password verification
                       
                        
                        // Successful login, set session variables
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_email'] = $user['email'];
    
                        // Redirect to admin dashboard or wherever needed
                        header('Location: /admin_dashboard');
                        exit;
                    } else {
                    
                        // Invalid email, password, or not an admin
                        $_SESSION['login_error'] = 'Invalid credentials or you are not an admin.';
                    }
    
                } catch (PDOException $e) {
                    // Handle potential errors (e.g., database connection issues)
                    error_log('Database error: ' . $e->getMessage());
                    $_SESSION['login_error'] = 'An error occurred. Please try again later.';
                }
            } else {
                // Input validation failed
                $_SESSION['login_error'] = 'Please fill in both fields.';
            }
    
            // Redirect back to login form in case of errors
            header('Location: /adminLogin');
            exit;
        }
    }

    public function showForgotPasswordForm() {
        include '../public/views/forgot_password.php';
    }

    public function handleForgotPassword() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
                include '../public/views/forgot_password.php';
                return;
            }

            $user = User::getUserByEmail($email);

            if ($user && $user['first_name'] === $firstName && $user['last_name'] === $lastName) {
                $token = bin2hex(random_bytes(16));
                $expiry = time() + 3600; 

                User::savePasswordResetToken($email, $token, $expiry);

                // Redirect to reset password form with token
                header('Location: /reset-password?token=' . $token);
                exit;
            } else {
                $error = 'No matching user found.';
                include '../public/views/forgot_password.php';
            }
        }
    }

    public function showResetPasswordForm() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            echo 'Invalid token.';
            exit;
        }
        include '../public/views/reset_password.php';
    }

    public function handleResetPassword() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $token = $_POST['token'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
    
            if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'All fields are required.';
                include '../public/views/reset_password.php';
                exit;
            }
    
            if ($newPassword !== $confirmPassword) {
                $error = 'Passwords do not match.';
                include '../public/views/reset_password.php';
                exit;
            }
    
            $user = User::getUserByToken($token);
    
            if (!$user || $user['reset_token_expiry'] < time()) {
                $error = 'Invalid or expired token.';
                include '../public/views/reset_password.php';
                exit;
            }
    
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            User::updatePassword($user['email'], $hashedPassword);
            User::removePasswordResetToken($user['email']);
    
            header('Location: /login');
            exit;
        } else {
            header('Location: /');
            exit;
        }
    }
    

    
    
    

    
}
?>
