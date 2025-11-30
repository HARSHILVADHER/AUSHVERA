<?php

    include 'config.php';

    $reviews = [];
    
    $sql = "
        SELECT 
            r.*, 
            p.name AS product_name,
            p.price AS product_price,
            p.images AS product_image
        FROM reviews r
        JOIN product p ON r.product_id = p.id
        ORDER BY r.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data = $row;
        $reviews[] = $data;
    }
    echo json_encode($reviews);
?>

