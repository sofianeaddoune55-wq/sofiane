<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

setFlash('success', 'Item removed from cart.');
redirect('cart.php');