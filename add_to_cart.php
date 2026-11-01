<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantity (AJAX)
    if (isset($_POST['update'])) {
        $cart_id = (int)$_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        if ($quantity < 1) $quantity = 1;

        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($quantity < 1) $quantity = 1;

    // Check if already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
        $stmt->execute([$quantity, $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
    }

    setFlash('success', 'Product added to cart!');
    redirect('cart.php');
}

redirect('index.php');