<?php
session_start();
require_once 'config.php';

// Test script for cart functionality
echo "<h2>Cart Functionality Test</h2>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>❌ User not logged in. Please login first.</p>";
    echo "<p>Session data: " . print_r($_SESSION, true) . "</p>";
    exit();
}

$user_id = $_SESSION['user_id'];
echo "<p style='color: green;'>✅ User logged in with ID: $user_id</p>";

// Test 1: Check if cart table exists
$result = $conn->query("SHOW TABLES LIKE 'cart'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Cart table exists</p>";
} else {
    echo "<p style='color: red;'>❌ Cart table does not exist</p>";
}

// Test 2: Check if product table exists
$result = $conn->query("SHOW TABLES LIKE 'product'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Product table exists</p>";
} else {
    echo "<p style='color: red;'>❌ Product table does not exist</p>";
}

// Test 3: Get some products to test with
$result = $conn->query("SELECT id, name FROM product LIMIT 3");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Found products in database:</p>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>- Product ID: {$row['id']}, Name: {$row['name']}</p>";
    }
} else {
    echo "<p style='color: red;'>❌ No products found in database</p>";
}

// Test 4: Check current cart items
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo "<p>Current cart items for user $user_id: {$row['count']}</p>";

// Test 5: Show cart items with product details
$stmt = $conn->prepare("
    SELECT c.id, c.quantity, p.name, p.price 
    FROM cart c 
    JOIN product p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Current Cart Items:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Cart ID</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $total = $row['price'] * $row['quantity'];
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>₹{$row['price']}</td>";
        echo "<td>{$row['quantity']}</td>";
        echo "<td>₹{$total}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No items in cart</p>";
}

$stmt->close();
$conn->close();

echo "<hr>";
echo "<h3>Test Instructions:</h3>";
echo "<ol>";
echo "<li>Make sure you are logged in</li>";
echo "<li>Go to any product page and click 'Add to Cart'</li>";
echo "<li>Check the cart page to see if items are added</li>";
echo "<li>Try updating quantities and removing items</li>";
echo "</ol>";
?> 