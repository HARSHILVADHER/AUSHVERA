<?php
include 'config.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Fetch all feedback entries from contactus table
    $query = "SELECT id, name, email, message, created_at FROM contactus ORDER BY created_at DESC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $feedback = [];
    while ($row = $result->fetch_assoc()) {
        $feedback[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'message' => $row['message'],
            'rating' => rand(3, 5), // Dummy rating if not stored in DB
            'date' => $row['created_at']
        ];
    }

    echo json_encode([
        'success' => true,
        'feedback' => $feedback
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
