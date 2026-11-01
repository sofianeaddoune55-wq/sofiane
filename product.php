<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    setFlash('error', 'Product not found.');
    redirect('index.php');
}

$cartCount = isLoggedIn() ? getCartCount($pdo, $_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="logo"><span>Tech</span>Store</div>
    <nav>
        <a href="index.php">Home</a>
        <?php if (isLoggedIn()): ?>
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
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</div>

<?php $flash = getFlash(); if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="product-detail">
    <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="detail-image"
         onerror="this.src='https://via.placeholder.com/500x400?text=No+Image'">
    <div class="detail-info">
        <span class="category-badge"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></span>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <div class="price">$<?= number_format($product['price'], 2) ?></div>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if (isLoggedIn()): ?>
            <form class="add-cart-form" method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" max="99">
                <button type="submit">Add to Cart</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn-primary" style="display:inline-block;text-align:center;padding:14px 30px;">Login to Buy</a>
        <?php endif; ?>
    </div>
</div>

<div class="footer">
    &copy; <?= date('Y') ?> TechStore. All rights reserved.
</div>

<script src="script.js"></script>
</body>
</html>