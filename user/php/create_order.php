<?php

    include 'r_config.php';
    header('Content-Type: application/json');

    // Read POSTed JSON (amount in rupees expected from client)
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data || !isset($data['amount'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $amount_rupees = floatval($data['amount']); // e.g. 499.00
    if ($amount_rupees <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid amount']);
        exit;
    }

    // Razorpay requires amount in paise (integer)
    $amount_paise = intval(round($amount_rupees * 100));

    // Optional: create your own receipt id to track (use order id from your DB if you want)
    $receipt_id = 'rcpt_' . time();

    // Prepare payload
    $payload = [
        'amount' => $amount_paise,
        'currency' => 'INR',
        'receipt'  => $receipt_id,
        'payment_capture' => 1 // auto-capture; set 0 if you want manual capture
    ];
    $payload_json = json_encode($payload);

    // call Razorpay Orders API using cURL (basic auth)
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload_json)
    ]);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        echo json_encode(['success' => false, 'message' => 'cURL error: ' . $curl_err]);
        exit;
    }

    $resp = json_decode($response, true);

    if ($httpcode >= 200 && $httpcode < 300 && isset($resp['id'])) {
        // Success: return order id and everything the client needs
        echo json_encode([
            'success' => true,
            'order' => $resp
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Razorpay error', 'raw' => $resp, 'http' => $httpcode]);
        exit;
    }

?>
