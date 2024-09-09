<?php

// Function to sanitize user input to prevent SQL injection and XSS attacks
function sanitizeInput($data) {
    $data = trim($data);               // Remove extra spaces, tabs, etc.
    $data = stripslashes($data);       // Remove slashes
    $data = htmlspecialchars($data);   // Convert special characters to HTML entities
    return $data;
}

// Function to redirect to a specific URL
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);  // Check if the 'user' session variable is set
}

// Function to format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);  // Format as a US dollar amount
}

// Function to check if an email is valid
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to generate a random token (useful for password reset, CSRF tokens, etc.)
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));  // Generate a random token of the given length
}

// Function to calculate the total cart amount
function calculateCartTotal($cart_items) {
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];  // Assuming each item has price and quantity
    }
    return $total;
}

// Function to display session messages
function displaySessionMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);  // Clear the message after displaying
    }
}

// Function to set a session message
function setSessionMessage($message) {
    $_SESSION['message'] = $message;
}

// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);  // Bcrypt hashing for passwords
}

// Function to verify password
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}




?>
