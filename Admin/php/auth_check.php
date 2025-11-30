<?php
session_start();

if ($_SESSION['role'] !== "admin") {
    header("Location: ../user/login.html");
    exit();
}
?>