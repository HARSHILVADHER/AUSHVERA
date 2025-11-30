<?php

    include 'config.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Not logged in"]);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    try{
        // Step 1: Fetch the user details
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            echo json_encode(["success" => false, "message" => "User not found"]);
            exit;
        }

        // Step 2: Insert into deleted_users

        $insert = "INSERT INTO deleted_users (id, name, username, email, password, role)
               VALUES (?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($insert);
        $stmt2->bind_param(
            "isssss",
            $user['id'],
            $user['name'],
            $user['username'],
            $user['email'],
            $user['password'],
            $user['role']
        );
        $stmt2->execute();

        // Step 3: Delete from users table

        $delete = "DELETE FROM users WHERE id = ?";
        $stmt3 = $conn->prepare($delete);
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();

        // Step 4: Logout user
        session_destroy();

        echo json_encode(["success" => true, "message" => "Account deleted successfully"]);
    
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
?>