<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

$cartCount = getCartCount($pdo, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TechStore</title>
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

<div class="cart-container">
    <h2>Shopping Cart</h2>

    <?php if (empty($cartItems)): ?>
        <div class="empty-state">
            <h3>Your cart is empty</h3>
            <p>Start shopping to add items to your cart!</p>
            <br>
            <a href="index.php" class="btn-continue">Browse Products</a>
        </div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt=""
                                 onerror="this.src='https://via.placeholder.com/60x60?text=N/A'">
                        </td>
                        <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <input type="number" value="<?= $item['quantity'] ?>" min="1" style="width:60px;padding:6px;border:1px solid #ddd;border-radius:4px;"
                                   onchange="updateCartQuantity(<?= $item['id'] ?>, this.value)">
                        </td>
                        <td><strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                        <td>
                            <a href="remove_from_cart.php?id=<?= $item['id'] ?>" class="remove-btn"
                               onclick="return confirmDelete('Remove this item from cart?')">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-total">
            Total: <span>$<?= number_format($total, 2) ?></span>
        </div>

        <div class="cart-actions">
            <a href="index.php" class="btn-continue">Continue Shopping</a>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<div class="footer">
    &copy; <?= date('Y') ?> TechStore. All rights reserved.
</div>

<script src="script.js"></script>
</body>
</html>