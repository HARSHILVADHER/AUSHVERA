<?php
header('Content-Type: application/json');
include 'config.php';

try {
    $query = "
        SELECT 
            o.id AS order_id,
            u.name AS customer_name,
            u.email AS customer_email,
            up.phone AS customer_phone,
            p.name AS product_name,
            (p.price * o.quantity) AS total_amount,
            o.status,
            o.created_at AS ordered_date
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN user_profile up ON u.id = up.user_id
        LEFT JOIN product p ON o.product_id = p.id
        ORDER BY o.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching orders: ' . $e->getMessage(),
        'orders' => []
    ]);
}

$conn->close();
?>
