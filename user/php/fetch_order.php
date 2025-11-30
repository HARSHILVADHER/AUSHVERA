<?php
session_start();
include 'config.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$user_id = intval($_SESSION['user_id']);

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {

    // decode product items (stored as JSON)
    $row['items'] = json_decode($row['items'], true);

    $orders[] = $row;
}

echo json_encode(["success" => true, "orders" => $orders]);
exit;
?>