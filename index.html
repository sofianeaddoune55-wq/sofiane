<?php
require_once 'config.php';

// Get search/filter params
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$price_sort = isset($_GET['price_sort']) ? $_GET['price_sort'] : '';

// Build query
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_filter > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_filter;
}

if ($price_sort === 'low') {
    $sql .= " ORDER BY p.price ASC";
} elseif ($price_sort === 'high') {
    $sql .= " ORDER BY p.price DESC";
} else {
    $sql .= " ORDER BY p.created_at DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$cartCount = isLoggedIn() ? getCartCount($pdo, $_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Electronics</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
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

<!-- Hero -->
<div class="hero">
    <h1>Welcome to TechStore</h1>
    <p>Your one-stop shop for the latest electronics and gadgets</p>
</div>

<!-- Flash message -->
<?php $flash = getFlash(); if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<!-- Search & Filter -->
<div class="search-section">
    <form class="search-form" method="GET" action="index.php">
        <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="0">All Categories</option>
            <?php foreach ($cats as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="price_sort">
            <option value="">Sort by</option>
            <option value="low" <?= $price_sort === 'low' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="high" <?= $price_sort === 'high' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<!-- Products -->
<h2 class="section-title">
    <?= $search ? 'Search Results for "' . htmlspecialchars($search) . '"' : 'Our Products' ?>
</h2>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <h3>No products found</h3>
        <p>Try adjusting your search or filters.</p>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image"
                     onerror="this.src='https://via.placeholder.com/300x220?text=No+Image'">
                <div class="product-info">
                    <div class="product-category"><?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></div>
                    <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                    <div class="product-price">$<?= number_format($p['price'], 2) ?></div>
                    <a href="product.php?id=<?= $p['id'] ?>" class="btn">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="footer">
    &copy; <?= date('Y') ?> TechStore. All rights reserved.
</div>

<script src="script.js"></script>
</body>
</html>