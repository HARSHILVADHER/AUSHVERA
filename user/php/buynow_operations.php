<?php
session_start();
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$product_id = intval($data['product_id']);
$quantity = intval($data['quantity']);

if (!$product_id || !$quantity) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get user_id from session if logged in
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

$sql = "
    INSERT INTO buynow (user_id, product_id, quantity)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $product_id, $quantity);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB error']);
}
$stmt->close();
$conn->close();
?>
