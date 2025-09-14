<?php
include 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Validate product ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'No product ID provided']);
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM product WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    // Handle image paths (comma-separated in DB)
    $images = [];
    if (!empty($row['images'])) {
        $images = explode(',', $row['images']);
        $images = array_map('trim', $images);
    }
    $row['images'] = $images;

    // Decode JSON fields from database
    $row['product_details'] = json_decode($row['product_details'], true) ?: [];
    $row['ingredients_data'] = json_decode($row['ingredients_data'], true) ?: [];
    $row['how_to_use_data'] = json_decode($row['how_to_use_data'], true) ?: [];
    
    // Ensure weight field is included
    $row['weight'] = $row['weight'] ?? null;

    // Add default mock reviews for frontend use
    $row['reviews'] = [
        ["name" => "Neha S.", "date" => "June 10, 2025", "rating" => 4.5, "text" => "Amazing product! Helped with digestion."],
        ["name" => "Amit R.", "date" => "June 20, 2025", "rating" => 5, "text" => "Highly recommended. Pure and effective."]
    ];

    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Product not found']);
}

$stmt->close();
$conn->close();
?>
