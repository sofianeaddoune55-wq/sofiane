<?php
require_once 'config.php';

if (isLoggedIn()) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email is already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed, $phone, $address]);

            setFlash('success', 'Registration successful! Please login.');
            redirect('login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TechStore</title>
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

<div class="auth-container">
    <div class="auth-form">
        <h2>Create Account</h2>

        <?php if ($error): ?>
            <div class="flash error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address"><?= htmlspecialchars($address ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-primary">Register</button>
        </form>

        <div class="switch-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>