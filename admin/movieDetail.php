<?php
$pageTitle   = 'Movie details';
$activeAdmin = 'movies';
require_once __DIR__ . '/includes/header.php';

$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT MovieID, Title FROM movies WHERE MovieID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    ?>
    <div class="panel text-center py-5">
        <h1 class="h4 mb-3">Movie not found</h1>
        <a class="btn btn-accent" href="movies.php">Back to movies</a>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare(
    "SELECT c.CategoryName
     FROM moviecategories mc
     JOIN categories c ON c.CategoryID = mc.CategoryID
     WHERE mc.MovieID = :id"
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare(
    "SELECT a.ActorName
     FROM movieactors ma
     JOIN actors a ON a.ActorID = ma.ActorID
     WHERE ma.MovieID = :id"
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="movies.php" class="text-decoration-none text-secondary d-inline-block mb-3">
    <i class="bi bi-arrow-left"></i> Back to movies
</a>
<h1 class="h2 fw-bold mb-4"><?= e($movie['Title']) ?></h1>

<div class="row g-4">
    <div class="col-md-6">
        <div class="panel p-0 overflow-hidden">
            <table class="table table-admin table-hover">
                <thead><tr><th>Categories</th></tr></thead>
                <tbody>
                    <?php if (!$categories): ?>
                        <tr><td class="text-secondary">No categories</td></tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr><td><?= e($category['CategoryName']) ?></td></tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel p-0 overflow-hidden">
            <table class="table table-admin table-hover">
                <thead><tr><th>Actors</th></tr></thead>
                <tbody>
                    <?php if (!$actors): ?>
                        <tr><td class="text-secondary">No actors</td></tr>
                    <?php else: ?>
                        <?php foreach ($actors as $actor): ?>
                            <tr><td><?= e($actor['ActorName']) ?></td></tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
