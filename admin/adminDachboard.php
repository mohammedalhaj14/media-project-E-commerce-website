<?php
$pageTitle   = 'Dashboard';
$activeAdmin = '';
require_once __DIR__ . '/includes/header.php';

// Row counts (table names come from the fixed list in header.php, never from user input)
$counts = [];
foreach ($adminLinks as $key => $link) {
    $counts[$key] = (int) $pdo->query('SELECT COUNT(*) FROM `' . $link[3] . '`')->fetchColumn();
}
?>

<div class="mb-4">
    <h1 class="h2 fw-bold mb-1">Admin dashboard</h1>
    <p class="text-secondary mb-0">Manage the store content from one place.</p>
</div>

<div class="row g-4">
    <?php foreach ($adminLinks as $key => $link): ?>
        <div class="col-sm-6 col-lg-4">
            <a class="stat-card" href="<?= e($link[0]) ?>">
                <span class="icon"><i class="bi <?= e($link[2]) ?>"></i></span>
                <span>
                    <span class="count d-block"><?= $counts[$key] ?></span>
                    <span class="label"><?= e($link[1]) ?></span>
                </span>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
