<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cartCount = getCartCount($pdo, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - TechStore</title>
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
        <?php if (isAdmin()): ?>
            <a href="admin/index.php">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
    </nav>
</div>

<?php $flash = getFlash(); if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="orders-container">
    <h2>My Orders</h2>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <h3>No orders yet</h3>
            <p>Start shopping to place your first order!</p>
            <br>
            <a href="index.php" class="btn-continue">Browse Products</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
            $stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi 
                                   JOIN products p ON oi.product_id = p.id 
                                   WHERE oi.order_id = ?");
            $stmt->execute([$order['id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="order-card">
                <div class="order-header">
                    <h3>Order #<?= $order['id'] ?></h3>
                    <div>
                        <span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                    </div>
                </div>
                <div style="color:#888;font-size:0.85rem;margin-bottom:10px;">
                    Placed on <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?>
                </div>
                <ul class="order-items-list">
                    <?php foreach ($items as $item): ?>
                        <li>
                            <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                            <span>$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="order-total">Total: $<?= number_format($order['total'], 2) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="footer">
    &copy; <?= date('Y') ?> TechStore. All rights reserved.
</div>

<script src="script.js"></script>
</body>
</html>