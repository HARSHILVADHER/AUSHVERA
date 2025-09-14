<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productId = intval($_GET['id']);
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $product = $result->fetch_assoc();
    
    // Handle images - split if comma-separated
    $images = $product['images'];
    if (strpos($images, ',') !== false) {
        $product['imageArray'] = array_map('trim', explode(',', $images));
    } else {
        $product['imageArray'] = [$images];
    }
    
    // Decode JSON fields
    $product['productDetailsArray'] = json_decode($product['product_details'], true) ?: [];
    $product['ingredientsArray'] = json_decode($product['ingredients_data'], true) ?: [];
    $product['howToUseArray'] = json_decode($product['how_to_use_data'], true) ?: [];
    
    echo json_encode(['success' => true, 'product' => $product]);
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 