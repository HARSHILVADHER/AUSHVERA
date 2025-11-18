<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please login to manage your cart'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get cart items for the logged-in user
        getCartItems($user_id);
        break;
        
    case 'POST':
        // Handle POST requests (add, update, remove)
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? '';
        
        switch($action) {
            case 'add':
                addToCart($user_id, $data);
                break;
            case 'update':
                updateCartItem($user_id, $data);
                break;
            case 'remove':
                removeFromCart($user_id, $data);
                break;
            case 'clear':
                clearCart($user_id);
                break;
            case 'get_cart':
                getCartItems($user_id);
                break;
            default:
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
        break;
}

// Function to get cart items
function getCartItems($user_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("
            SELECT c.id, c.quantity, p.id as product_id, p.name, p.price, p.description, p.images
            FROM cart c
            JOIN product p ON c.product_id = p.id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
        ");
        
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cart_items = [];
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = [
                'id' => $row['id'],
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'image' => $row['images'],
                'quantity' => $row['quantity']
            ];
        }
        
        echo json_encode([
            'success' => true,
            'items' => $cart_items,
            'count' => count($cart_items)
        ]);
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching cart items: ' . $e->getMessage()
        ]);
    }
}

// Function to add item to cart
function addToCart($user_id, $data) {
    global $conn;
    
    $product_id = $data['product_id'] ?? 0;
    $quantity = $data['quantity'] ?? 1;
            
    if (!$product_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Product ID is required'
        ]);
        return;
    }
    
    try {
        // Check if product exists
        $stmt = $conn->prepare("SELECT id, name, price FROM product WHERE id = ?");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
            return;
        }
        
        $product = $result->fetch_assoc();
        $stmt->close();
        
        // Check if item already exists in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing item
            $cart_item = $result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->bind_param('ii', $new_quantity, $cart_item['id']);
            $stmt->execute();
            
            echo json_encode([
                'success' => true,
                'message' => 'Cart updated successfully',
                'action' => 'updated',
                'quantity' => $new_quantity
            ]);
        } else {
            // Add new item
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $user_id, $product_id, $quantity);
            $stmt->execute();
            
            echo json_encode([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'action' => 'added',
                'quantity' => $quantity
            ]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error adding to cart: ' . $e->getMessage()
        ]);
    }
}

// Function to update cart item
function updateCartItem($user_id, $data) {
    global $conn;
    
    $cart_id = $data['cart_id'] ?? 0;
    $quantity = $data['quantity'] ?? 1;
    
    if (!$cart_id || $quantity < 1) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid cart ID or quantity'
        ]);
        return;
    }
    
    try {
        // Check if cart item belongs to user
        $stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $cart_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Cart item not found'
            ]);
            return;
        }
        
        // Update quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param('ii', $quantity, $cart_id);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Cart updated successfully',
            'quantity' => $quantity
        ]);
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error updating cart: ' . $e->getMessage()
        ]);
    }
}

// Function to remove item from cart
function removeFromCart($user_id, $data) {
    global $conn;
    
    $cart_id = $data['cart_id'] ?? 0;
    
    if (!$cart_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Cart ID is required'
        ]);
        return;
    }
    
    try {
        // Check if cart item belongs to user
        $stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $cart_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Cart item not found'
            ]);
            return;
        }
        
        // Remove item
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error removing from cart: ' . $e->getMessage()
        ]);
    }
}

// Function to clear entire cart
function clearCart($user_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error clearing cart: ' . $e->getMessage()
        ]);
    }
}

$conn->close();
?> 