<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);
    $name = trim($_POST['productName']);
    $category = trim($_POST['productCategory']);
    $description = trim($_POST['productDescription']);
    $price = floatval($_POST['productPrice']);
    $discount = !empty($_POST['productDiscount']) ? floatval($_POST['productDiscount']) : null;
    $sku = trim($_POST['productSKU']);
    $stock = intval($_POST['productStock']);
    $weight = !empty($_POST['productWeight']) ? floatval($_POST['productWeight']) : null;
    $status = trim($_POST['productStatus']);
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }
    
    // Get existing product data
    $stmt = $conn->prepare("SELECT images FROM product WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $existingProduct = $result->fetch_assoc();
    $existingImages = $existingProduct['images'];
    
    // Handle new image uploads
    $uploadDir = '../../user/php/uploads/';
    $newImagePaths = [];
    
    if (isset($_FILES['productImages']) && is_array($_FILES['productImages']['tmp_name'])) {
        foreach ($_FILES['productImages']['tmp_name'] as $index => $tmpName) {
            if ($tmpName && $_FILES['productImages']['error'][$index] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['productImages']['name'][$index]);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $uniqueName = uniqid("img_", true) . '.' . $extension;
                $targetPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $newImagePaths[] = $uniqueName;
                }
            }
        }
    }
    
    // Combine existing and new images
    $finalImages = $existingImages;
    if (!empty($newImagePaths)) {
        if (!empty($finalImages)) {
            $finalImages .= ',' . implode(',', $newImagePaths);
        } else {
            $finalImages = implode(',', $newImagePaths);
        }
    }
    
    // Process new data
    $productDetails = isset($_POST['productDetails']) ? json_encode($_POST['productDetails']) : null;
    $ingredientsHeading = isset($_POST['ingredientsHeading']) ? trim($_POST['ingredientsHeading']) : null;
    $howToUseHeading = isset($_POST['howToUseHeading']) ? trim($_POST['howToUseHeading']) : null;
    
    $ingredientsData = null;
    if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
        $ingredientsArray = [];
        for ($i = 0; $i < count($_POST['ingredients']); $i++) {
            if (!empty(trim($_POST['ingredients'][$i]))) {
                $ingredientsArray[] = [
                    'ingredient' => trim($_POST['ingredients'][$i]),
                    'ayurvedicName' => isset($_POST['ayurvedicNames'][$i]) ? trim($_POST['ayurvedicNames'][$i]) : '',
                    'keyBenefits' => isset($_POST['keyBenefits'][$i]) ? trim($_POST['keyBenefits'][$i]) : ''
                ];
            }
        }
        $ingredientsData = !empty($ingredientsArray) ? json_encode($ingredientsArray) : null;
    }
    
    $howToUseData = isset($_POST['howToUse']) ? json_encode($_POST['howToUse']) : null;
    
    // Update the product with new data
    $updateStmt = $conn->prepare("UPDATE product SET name = ?, category = ?, description = ?, price = ?, discount = ?, sku = ?, stock = ?, weight = ?, status = ?, images = ?, product_details = ?, ingredients_heading = ?, ingredients_data = ?, how_to_use_heading = ?, how_to_use_data = ? WHERE id = ?");
    $updateStmt->bind_param("sssddsdssssssssi", $name, $category, $description, $price, $discount, $sku, $stock, $weight, $status, $finalImages, $productDetails, $ingredientsHeading, $ingredientsData, $howToUseHeading, $howToUseData, $productId);
    
    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating product: ' . $updateStmt->error]);
    }
    
    $updateStmt->close();
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 