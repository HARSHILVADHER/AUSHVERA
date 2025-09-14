<?php
session_start();
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$address_id = intval($data['address_id']);
$payment_method = $data['payment_method'];
$cart_items = $data['cart_items'];

if (!$address_id || !$payment_method || !$cart_items || !is_array($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

foreach ($cart_items as $item) {
    $product_id = intval($item['id']);
    $quantity = intval($item['quantity']);
    $stmt = $conn->prepare("INSERT INTO orders (user_id, address_id, product_id, quantity, payment_method, status) VALUES (?, ?, ?, ?, ?, 'Not Delivered')");
    $stmt->bind_param("iiiis", $user_id, $address_id, $product_id, $quantity, $payment_method);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['success' => true]);
$conn->close();
?>
