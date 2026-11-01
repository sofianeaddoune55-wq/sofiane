<?php
require_once 'config.php';

if (isLoggedIn()) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        setFlash('success', 'Welcome back, ' . $user['name'] . '!');

        if ($user['role'] === 'admin') {
            redirect('admin/index.php');
        } else {
            redirect('index.php');
        }
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="logo"><span>Tech</span>Store</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</div>

<?php $flash = getFlash(); if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="flash error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>

        <div class="switch-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>

        <div class="switch-link" style="margin-top:10px; font-size:0.85rem; color:#999;">
            Admin login: admin@techstore.com / admin123
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>