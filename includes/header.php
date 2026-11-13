<?php
// Shared header for the public pages.
// Set $pageTitle and $activePage BEFORE including this file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../connection.php';   // must define $pdo

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$pageTitle  = $pageTitle  ?? 'Movie Store';
$activePage = $activePage ?? '';

$isLoggedIn   = isset($_SESSION['login']);
$hasOpenOrder = false;

if ($isLoggedIn && isset($_SESSION['user'])) {
    $stmt = $pdo->prepare("SELECT SaleID FROM sales WHERE ClientID = :clientID AND Opened = 1 LIMIT 1");
    $stmt->bindValue(':clientID', $_SESSION['user']);
    $stmt->execute();
    $hasOpenOrder = (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Movie Store</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-store sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-film brand-mark"></i> MOVIE <span class="brand-mark">STORE</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="index.php">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-md-center gap-md-2">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <?php if ($hasOpenOrder): ?>
                            <a class="nav-link cart-link" href="card.php" title="My cart"><i class="bi bi-cart3"></i></a>
                        <?php else: ?>
                            <span class="nav-link cart-link disabled" title="Your cart is empty"><i class="bi bi-cart3"></i></span>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary btn-sm" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === 'login' ? 'active' : '' ?>" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-accent btn-sm" href="registration.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
