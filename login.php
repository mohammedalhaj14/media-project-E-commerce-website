<?php
session_start();            // nothing may be printed before this line
if (isset($_SESSION['login'])) {
    header('location:index.php');
    die();
}

$pageTitle  = 'Login';
$activePage = 'login';
require_once __DIR__ . '/includes/header.php';

$msg = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);
?>

<div class="container">
    <div class="panel auth-card">
        <h1 class="h3 fw-bold mb-1">Welcome back</h1>
        <p class="text-secondary mb-4">Log in to manage your cart and orders.</p>

        <?php if ($msg): ?>
            <div class="alert alert-warning" role="alert"><?= e($msg) ?></div>
        <?php endif; ?>

        <form action="actions/login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input name="username" type="text" class="form-control" id="username" placeholder="Enter username" autocomplete="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="password" class="form-control" id="password" placeholder="Enter password" autocomplete="current-password" required>
            </div>

            <button type="submit" class="btn btn-accent w-100 btn-lg">Login</button>
        </form>

        <p class="text-center text-secondary mt-4 mb-0">
            New here? <a href="registration.php">Create an account</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
