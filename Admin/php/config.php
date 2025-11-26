<?php
// $host = 'sql207.infinityfree.com';
// $db   = 'if0_39067962_aushvera';
// $user = 'if0_39067962';
// $pass = 'Harshil532004';

// $host = 'localhost';
// $db   = 'aushvera';
// $user = 'root';
// $pass = '';

$host = 'sql200.infinityfree.com';
$db   = 'if0_40471593_aushvera';
$user = 'if0_40471593';
$pass = 'rajrathod8732t';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
