<?php
include 'config.php';

// Optionally, use session for user_id if available
session_start();
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

// Fetch the latest Buy Now order for this user (or for guest, just latest entry)
if ($user_id) {
    $sql = "SELECT b.*, p.name, p.price, p.images, p.description FROM buynow b JOIN product p ON b.product_id = p.id WHERE b.user_id = ? ORDER BY b.created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} else {
    $sql = "SELECT b.*, p.name, p.price, p.images, p.description FROM buynow b JOIN product p ON b.product_id = p.id ORDER BY b.created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // Decode images if stored as JSON, else split by comma
    if (isset($row['images'])) {
        $images = json_decode($row['images'], true);
        if (!$images) {
            $images = explode(',', $row['images']);
        }
        $row['images'] = $images;
    }
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'No Buy Now order found.']);
}
$stmt->close();
$conn->close();
?> 