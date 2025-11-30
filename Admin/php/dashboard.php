<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "config.php"; // your db connection

$response = [];

// 1. Total products
$p_query = "SELECT COUNT(*) AS total_products FROM product";
$p_result = mysqli_query($conn, $p_query);
$response['total_products'] = mysqli_fetch_assoc($p_result)['total_products'];

// 2. Total users
$u_query = "SELECT COUNT(*) AS total_users FROM users";
$u_result = mysqli_query($conn, $u_query);
$response['total_users'] = mysqli_fetch_assoc($u_result)['total_users'];

// 3. Total orders
$o_query = "SELECT COUNT(*) AS total_orders FROM orders";
$o_result = mysqli_query($conn, $o_query);
$response['total_orders'] = mysqli_fetch_assoc($o_result)['total_orders'];

// 4. Completed orders
$c_query = "SELECT COUNT(*) AS completed_orders FROM orders WHERE status='completed'";
$c_result = mysqli_query($conn, $c_query);
$response['completed_orders'] = mysqli_fetch_assoc($c_result)['completed_orders'];

// 5. Pending orders
$pen_query = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status='Not Delivered'";
$pen_result = mysqli_query($conn, $pen_query);
$response['pending_orders'] = mysqli_fetch_assoc($pen_result)['pending_orders'];

// 6. Total income (completed orders only)
$income_query = "SELECT SUM(total_amount) AS total_income FROM orders ";
$income_result = mysqli_query($conn, $income_query);
$response['total_income'] = mysqli_fetch_assoc($income_result)['total_income'] ?? 0;

$a_query = "SELECT count(*) AS active_products  FROM product where status = 'active' ";
$a_result = mysqli_query($conn, $a_query);
$response['active_products'] = mysqli_fetch_assoc($a_result)['active_products'];

$un_a_query = "SELECT count(*) AS unActive_products  FROM product where status = 'draft'";
$un_a_result = mysqli_query($conn, $un_a_query);
$response['unActive_products'] = mysqli_fetch_assoc($un_a_result)['unActive_products'];

$s_query = "SELECT count(*) AS out_of_stock FROM product where stock <= 0";
$s_result = mysqli_query($conn, $s_query);
$response['out_of_stock'] = mysqli_fetch_assoc($s_result)['out_of_stock'];

echo json_encode($response);