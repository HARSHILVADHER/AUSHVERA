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
            o.items AS items_json,
            o.total_amount,
            o.status,
            o.created_at AS ordered_date
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN user_profile up ON u.id = up.user_id
        ORDER BY o.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];

    while ($row = $result->fetch_assoc()) {

        // Decode items JSON
        $items = json_decode($row["items_json"], true);

        if (!is_array($items)) {
            $items = [];
        }

        $orders[] = [
            "order_id" => $row["order_id"],
            "customer_name" => $row["customer_name"],
            "customer_email" => $row["customer_email"],
            "customer_phone" => $row["customer_phone"],
            "total_amount" => $row["total_amount"],
            "status" => $row["status"],
            "ordered_date" => $row["ordered_date"],
            "products" => $items
        ];
    }

    echo json_encode([
        "success" => true,
        "orders" => $orders
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching orders: " . $e->getMessage()
    ]);
}

$conn->close();
?>
