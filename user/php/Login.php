<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for admin credentials first
    if ($email === 'aushveraadmin@gmail.com' && $password === 'adminaushvera') {
        $_SESSION['role'] = "admin";
        $_SESSION['user_id'] = 'admin';
        $_SESSION['email'] = $email;
        $_SESSION['name'] = 'Admin';
        $_SESSION['is_admin'] = true;
        echo "admin_success";
        exit;
    }

    // Fetch user for regular users
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Use password_verify for hashed passwords
        if (password_verify($password, $user['password'])) {
            $_SESSION['role'] = "user";
            $_SESSION['user_id'] = $user['id']; // Store user_id for multi-user logic
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['is_admin'] = false;
            echo "success";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
