<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database configuration
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Debug: Log the received data
    error_log("Received form data - Name: $name, Email: $email, Phone: $phone, Subject: $subject, Message: $message");
    
    // Validate required fields
    if (empty($name)) {
        throw new Exception('Name is required');
    }
    
    if (empty($email)) {
        throw new Exception('Email is required');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }
    
    if (empty($message)) {
        throw new Exception('Message is required');
    }
    
    // Validate subject if it's from the detailed form
    if (empty($subject)) {
        $subject = 'General Inquiry'; // Default subject for simple form
    }
    
    // Sanitize inputs
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    // Debug: Log the sanitized data
    error_log("Sanitized data - Name: $name, Email: $email, Phone: $phone, Subject: $subject, Message: $message");
    
    // Prepare SQL statement
    $sql = "INSERT INTO contactus (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    
    // Execute the statement
    if ($stmt->execute()) {
        $inserted_id = $conn->insert_id;
        error_log("Successfully inserted contact form with ID: $inserted_id");
        
        $response = [
            'success' => true,
            'message' => 'Thank you for your message! We will get back to you soon.'
        ];
    } else {
        throw new Exception('Failed to save message: ' . $stmt->error);
    }
    
    // Close statement
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
} finally {
    // Close database connection
    if (isset($conn)) {
        $conn->close();
    }
}

// Return JSON response
echo json_encode($response);
?> 