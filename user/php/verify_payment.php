<?php
session_start();
// verify_payment.php
require_once 'r_config.php';
require_once 'config.php';
header('Content-Type: application/json');

// read POST JSON from client (handler gave these keys)
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || !isset($data['razorpay_order_id']) || !isset($data['razorpay_payment_id']) || !isset($data['razorpay_signature'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

$razorpay_order_id = $data['razorpay_order_id'];
$razorpay_payment_id = $data['razorpay_payment_id'];
$razorpay_signature = $data['razorpay_signature'];

// Must match your stored order id
$order_db_id = $_SESSION['order_db_id']; 
$total_amount = $_SESSION['order_amount'];

if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature || !$order_db_id || !$total_amount) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

// Create expected signature: HMAC_SHA256(order_id + "|" + payment_id, secret)
$payload = $razorpay_order_id . '|' . $razorpay_payment_id;
$expected = hash_hmac('sha256', $payload, $keySecret);

// Compare
if (hash_equals($expected, $razorpay_signature)) {
    
    // Insert into payments table
    $stmt = $conn->prepare(
        "INSERT INTO payments (order_id, razorpay_order_id, razorpay_payment_id, razorpay_signature, amount, status)
        VALUES (?, ?, ?, ?, ?, 'successful')"
    );
    $stmt->bind_param("isssd", $order_db_id, $razorpay_order_id, $razorpay_payment_id, $razorpay_signature, $total_amount);
    $stmt->execute();

    // Update order status
    $stmt3 = $conn->prepare("
    UPDATE orders SET 
        payment_status='paid',
        razorpay_order_id = ? ,
        razorpay_payment_id = ?  
    WHERE id=?");
    $stmt3->bind_param("ssi", $razorpay_order_id, $razorpay_payment_id ,$order_db_id  );
    $stmt3->execute();

    // Clear sensitive session
    unset($_SESSION['razorpay_order_id']);
    unset($_SESSION['order_db_id']);
    unset($_SESSION['order_amount']);

    echo json_encode(['success' => true, 'message' => 'Payment verified']);
    exit;
} else {
    // verification failed
    echo json_encode(['success' => false, 'message' => 'Invalid signature']);
    exit;
}