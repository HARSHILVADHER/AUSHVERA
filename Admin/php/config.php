<?php
$host = 'localhost';
$db = 'aushvera';
$user = 'root'; // change if not using XAMPP/MAMP
$pass = '';     // change if password is set

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
