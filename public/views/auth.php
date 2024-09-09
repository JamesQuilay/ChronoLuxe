<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAuthenticated() {
    // Check if the user is authenticated based on session variable
    return isset($_SESSION['user_id']);
}

function restrictAccess() {
    if (!isAuthenticated()) {
        // Redirect to login page if the user is not authenticated
        header('Location: /'); // Update the path to your login page
        exit();
    }
}
?>
