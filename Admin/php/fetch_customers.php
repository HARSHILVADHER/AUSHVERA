<?php
// php/fetch_customers.php
header('Content-Type: application/json');
include 'config.php';

try {
    $query = "
        SELECT 
            u.id, 
            u.name, 
            u.email,
            up.phone,
            up.date_of_birth,
            up.city
        FROM users u
        LEFT JOIN user_profile up ON u.id = up.user_id
        WHERE u.role = 'customer'
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $customers = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }

    echo json_encode([
        'success' => true,
        'customers' => $customers
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching customers: ' . $e->getMessage(),
        'customers' => []
    ]);
}

$conn->close();
?>
