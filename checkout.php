<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

// Get cart items
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    setFlash('error', 'Your cart is empty.');
    redirect('cart.php');
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);

    if (empty($address)) {
        $error = 'Please enter a shipping address.';
    } else {
        try {
            $pdo->beginTransaction();

            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, address, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $total, $address]);
            $order_id = $pdo->lastInsertId();

            // Add order items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            }

            // Clear cart
            $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$_SESSION['user_id']]);

            $pdo->commit();

            setFlash('success', 'Order #' . $order_id . ' placed successfully!');
            redirect('my_orders.php');
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Something went wrong. Please try again.';
        }
    }
}

$cartCount = getCartCount($pdo, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="logo"><span>Tech</span>Store</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="cart.php" class="cart-link">Cart
            <?php if ($cartCount > 0): ?>
                <span class="cart-count"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="my_orders.php">My Orders</a>
        <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
    </nav>
</div>

<div class="checkout-container">
    <div class="checkout-form">
        <h2>Checkout</h2>

        <?php if ($error): ?>
            <div class="flash error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Order Summary -->
        <div style="background:#f8f9fa;padding:15px;border-radius:8px;margin-bottom:20px;">
            <h3 style="margin-bottom:10px;color:#1e3a5f;">Order Summary</h3>
            <?php foreach ($cartItems as $item): ?>
                <div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid #eee;">
                    <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                    <strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong>
                </div>
            <?php endforeach; ?>
            <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:1.2rem;">
                <strong>Total:</strong>
                <strong style="color:#e53935;">$<?= number_format($total, 2) ?></strong>
            </div>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" value="<?= htmlspecialchars($user['name']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Shipping Address *</label>
                <textarea name="address" required placeholder="Enter your full shipping address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn-primary">Place Order</button>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>