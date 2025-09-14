<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect all inputs safely
        $name = trim($_POST['productName']);
        $category = trim($_POST['productCategory']);
        $description = trim($_POST['productDescription']);
        $price = floatval($_POST['productPrice']);
        $discount = !empty($_POST['productDiscount']) ? floatval($_POST['productDiscount']) : null;
        $sku = trim($_POST['productSKU']);
        $stock = intval($_POST['productStock']);
        $weight = !empty($_POST['productWeight']) ? floatval($_POST['productWeight']) : null;
        $status = trim($_POST['productStatus']);

        // Handle multiple image uploads
        $uploadDir = '../../user/php/uploads/';
        $imagePaths = [];

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['productImages']) && is_array($_FILES['productImages']['tmp_name'])) {
            foreach ($_FILES['productImages']['tmp_name'] as $index => $tmpName) {
                if ($tmpName && $_FILES['productImages']['error'][$index] === UPLOAD_ERR_OK) {
                    $originalName = basename($_FILES['productImages']['name'][$index]);
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $uniqueName = uniqid("img_", true) . '.' . $extension;
                    $targetPath = $uploadDir . $uniqueName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $imagePaths[] = $uniqueName; // Store only the filename
                    }
                }
            }
        }

        // Convert image paths to comma-separated string
        $imageString = implode(',', $imagePaths);

        // Process extra fields
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

        // Insert into DB
        $stmt = $conn->prepare("
            INSERT INTO product 
            (name, category, description, price, discount, sku, stock, weight, status, images, product_details, ingredients_heading, ingredients_data, how_to_use_heading, how_to_use_data) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // s = string, d = double, i = integer
        $stmt->bind_param(
            "sssddsdssssssss",
            $name,
            $category,
            $description,
            $price,
            $discount,
            $sku,
            $stock,
            $weight,
            $status,
            $imageString,
            $productDetails,
            $ingredientsHeading,
            $ingredientsData,
            $howToUseHeading,
            $howToUseData
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting data: ' . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    $conn->close();
}
?>
