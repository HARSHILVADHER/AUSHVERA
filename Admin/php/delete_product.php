<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);
    
    // Debug: Log the received product ID
    error_log("Delete request for product ID: " . $productId);
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }
    
    // First, get the product to find its images
    $stmt = $conn->prepare("SELECT images FROM product WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $product = $result->fetch_assoc();
    $images = $product['images'];
    
    // Delete the product from database
    $deleteStmt = $conn->prepare("DELETE FROM product WHERE id = ?");
    $deleteStmt->bind_param("i", $productId);
    
    if ($deleteStmt->execute()) {
        // Check if any rows were affected
        if ($deleteStmt->affected_rows > 0) {
            // Delete image files from uploads directory
            if (!empty($images)) {
                $imageArray = explode(',', $images);
                $uploadDir = '../../user/php/uploads/';
                
                foreach ($imageArray as $image) {
                    $imagePath = $uploadDir . trim($image);
                    if (file_exists($imagePath)) {
                        if (unlink($imagePath)) {
                            error_log("Deleted image: " . $imagePath);
                        } else {
                            error_log("Failed to delete image: " . $imagePath);
                        }
                    }
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No product found with the given ID']);
        }
    } else {
        error_log("Delete query failed: " . $deleteStmt->error);
        echo json_encode(['success' => false, 'message' => 'Error deleting product: ' . $deleteStmt->error]);
    }
    
    $deleteStmt->close();
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 