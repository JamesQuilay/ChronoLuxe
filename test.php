<?php
include 'includes/database.php';

if (isset($_GET['id'])) {
    $watch_id = intval($_GET['id']);
    // Your existing code to handle the image request
} else {
    http_response_code(400);
    echo "Invalid request: Missing ID";
}

$database = new Database();
$pdo = $database->connect();

$watch_id = intval($_GET['id']); // Ensure to sanitize and validate input

$query = "SELECT image FROM watches WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $watch_id]);

$image = $stmt->fetchColumn();

if ($image) {
    // Debugging: Save the image to a file
    file_put_contents('debug_image.jpg', $image); // Check the saved file
    header("Content-Type: image/jpeg");
    echo $image;
} else {
    http_response_code(404);
    echo "Image not found";
}
?>
