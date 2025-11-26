<?php
session_start();
include 'config.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}
$user_id = intval($_SESSION['user_id']);

// Get JSON input
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Validate input
if (
    !isset($data['address_id']) ||
    !isset($data['total_amount']) ||
    !isset($data['payment_method']) ||
    !isset($data['cart_items']) ||
    !is_array($data['cart_items']) ||
    count($data['cart_items']) == 0
) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$address_id = intval($data['address_id']);
$payment_method = $data['payment_method'];
$cart_items = $data['cart_items'];
$total_amount = $data['total_amount'];
$order_items = [];

// Build items array with price from DB
foreach ($cart_items as $item) {
    $product_id = intval($item['product_id']);
    $quantity = intval($item['quantity']);

    // get price
    $p = $conn->prepare("SELECT name, price FROM product WHERE id = ?");
    $p->bind_param("i", $product_id);
    $p->execute();
    $result = $p->get_result()->fetch_assoc();
    $p->close();

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID: ' . $product_id]);
        exit;
    }
    $name = $result['name'];
    $price = $result['price'];

    $item_total = $price * $quantity;

    // ---------- UPDATE PRODUCT STOCK ----------
    $update = $conn->prepare("UPDATE product SET stock = stock - ? WHERE id = ? AND stock >= ?");
    $update->bind_param("iii", $quantity, $product_id, $quantity);
    $update->execute();

    if ($update->affected_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock for product ID: ' . $product_id]);
        exit;
    }
    $update->close();


    // add item to order array
    $order_items[] = [
        "id" => $product_id,
        "name" => $name,
        "price" => $price,
        "quantity" => $quantity,
        "total" => $item_total
    ];
}

// JSON encode the items
$items_json = json_encode($order_items);

$stmt = $conn->prepare("
    INSERT INTO orders (user_id, address_id, items, total_amount, payment_method, status)
    VALUES (?, ?, ?, ?, ?, 'Not Delivered')
");
$stmt->bind_param("iisis", $user_id, $address_id, $items_json, $total_amount, $payment_method);
$stmt->execute();
$order_db_id = $stmt->insert_id;

// store in session so verify_payment.php can update it
$_SESSION['order_db_id'] = $order_db_id;
$_SESSION['order_amount'] = $total_amount;

$stmt->close();

echo json_encode(['success' => true]);

$conn->close();
?>
