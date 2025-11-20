<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /aushvera/user/login.html");
    exit();
}
?>