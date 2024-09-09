<?php
session_start();
include_once __DIR__ . '/../../includes/database.php';

$db = new Database();
$pdo = $db->connect();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];
$itemId = $_POST['item_id'];
$newQuantity = $_POST['quantity'];

$query = 'UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND id = :item_id';
$stmt = $pdo->prepare($query);
$result = $stmt->execute([
    'quantity' => $newQuantity,
    'user_id' => $userId,
    'item_id' => $itemId
]);

if ($result) {
    // Fetch the price for the item
    $query = 'SELECT price FROM watches JOIN cart ON watches.id = cart.watch_id WHERE cart.id = :item_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['item_id' => $itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    $price = $item['price'];

    echo json_encode(['success' => true, 'price' => $price]);
} else {
    echo json_encode(['success' => false]);
}
?>
