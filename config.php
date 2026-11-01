<?php
session_start();

$host = 'localhost';
$dbname = 'techstore';
$username = 'root';
$password = 'sefisefi';
$dbError = null;
$dbReady = false;
$pdo = null;

try {
    if (class_exists('PDO')) {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbReady = true;
    }
} catch (PDOException $e) {
    $dbError = $e->getMessage();
    $dbReady = false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function dbReady() {
    global $dbReady;
    return $dbReady;
}

function dbErrorMessage() {
    global $dbError;
    return $dbError ?: 'Unable to connect to the database.';
}

function getCartCount($pdo, $user_id) {
    if (!$pdo) {
        return 0;
    }

    $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['count'] ? $row['count'] : 0;
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
