<?php
// Shared header for every admin page.
// Set $pageTitle and $activeAdmin BEFORE including this file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only admins may see admin pages (redirect happens before any output).
if (!isset($_SESSION['admin'])) {
    header('location:../index.php');
    die();
}

require_once __DIR__ . '/../../connection.php';   // defines $pdo

/* ---------- helpers ---------- */

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

// Read a GET parameter as a trimmed string (arrays are ignored).
if (!function_exists('query_string')) {
    function query_string(string $key): string
    {
        $value = $_GET[$key] ?? '';
        return is_string($value) ? trim($value) : '';
    }
}

// Current page number from ?page=
if (!function_exists('query_page')) {
    function query_page(): int
    {
        $page = $_GET['page'] ?? 1;
        return (is_string($page) && ctype_digit($page) && (int) $page > 0) ? (int) $page : 1;
    }
}

// One-time message stored by an action file in $_SESSION[$key] (true = success).
if (!function_exists('flash')) {
    function flash(string $key, string $okMessage): void
    {
        if (!isset($_SESSION[$key])) {
            return;
        }
        $ok = ($_SESSION[$key] == true);
        unset($_SESSION[$key]);
        echo '<div class="alert alert-' . ($ok ? 'success' : 'danger') . ' alert-dismissible fade show" role="alert">'
            . e($ok ? $okMessage : 'Invalid parameter')
            . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

if (!function_exists('pagination')) {
    function pagination(int $page, int $totalPages, array $params = []): void
    {
        if ($totalPages <= 1) {
            return;
        }
        echo '<nav class="mt-4" aria-label="Pages"><ul class="pagination justify-content-center">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $query = http_build_query(array_merge($params, ['page' => $i]));
            echo '<li class="page-item ' . ($i === $page ? 'active' : '') . '">'
                . '<a class="page-link" href="?' . e($query) . '">' . $i . '</a></li>';
        }
        echo '</ul></nav>';
    }
}

/* ---------- navigation ---------- */

$pageTitle   = $pageTitle   ?? 'Admin';
$activeAdmin = $activeAdmin ?? '';

// key => [file, label, icon, table]
$adminLinks = [
    'clients'       => ['clients.php',     'Clients',       'bi-people',            'clients'],
    'categories'    => ['categories.php',  'Categories',    'bi-tags',              'categories'],
    'actors'        => ['actors.php',      'Actors',        'bi-person-video2',     'actors'],
    'directors'     => ['directors.php',   'Directors',     'bi-camera-reels',      'directors'],
    'movies'        => ['movies.php',      'Movies',        'bi-film',              'movies'],
];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-store sticky-top">
    <div class="container">
        <a class="navbar-brand" href="adminDachboard.php">
            <i class="bi bi-shield-lock brand-mark"></i> ADMIN <span class="brand-mark">PANEL</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($adminLinks as $key => $link): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeAdmin === $key ? 'active' : '' ?>" href="<?= e($link[0]) ?>"><?= e($link[1]) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="../index.php"><i class="bi bi-shop"></i> View shop</a>
                <a class="btn btn-outline-secondary btn-sm" href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">
