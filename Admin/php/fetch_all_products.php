<?php
include 'config.php';

// Set proper headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $sql = "SELECT * FROM product ORDER BY id DESC";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $products = [];
    $totalProducts = 0;
    $activeProducts = 0;
    $outOfStock = 0;

    while ($row = $result->fetch_assoc()) {
        // Handle the images field - it might be comma-separated or single image
        $image = $row['images'];
        if (strpos($image, ',') !== false) {
            $images = explode(',', $image);
            $image = trim($images[0]); // Use first image
        }
        
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'category' => $row['category'],
            'image' => $image,
            'stock' => $row['stock'],
            'weight' => $row['weight'] ?? null,
            'status' => $row['status'],
            'sku' => $row['sku'] ?? '',
            'discount_price' => $row['discount'] ?? null,
            'product_details' => json_decode($row['product_details'] ?? '[]', true),
            'ingredients_heading' => $row['ingredients_heading'] ?? '',
            'ingredients_data' => json_decode($row['ingredients_data'] ?? '[]', true),
            'how_to_use_heading' => $row['how_to_use_heading'] ?? '',
            'how_to_use_data' => json_decode($row['how_to_use_data'] ?? '[]', true)
        ];

        // Count statistics
        $totalProducts++;
        if ($row['status'] == 'active') {
            $activeProducts++;
        }
        if ($row['stock'] <= 0) {
            $outOfStock++;
        }
    }

    $response = [
        'products' => $products,
        'statistics' => [
            'total' => $totalProducts,
            'active' => $activeProducts,
            'outOfStock' => $outOfStock
        ]
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?> 