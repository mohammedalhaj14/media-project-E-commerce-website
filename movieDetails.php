<?php
session_start();            // nothing may be printed before this line
$movieId = (isset($_GET['movieID']) && is_numeric($_GET['movieID'])) ? (int) $_GET['movieID'] : 0;

$pageTitle  = 'Movie details';
$activePage = '';
require_once __DIR__ . '/includes/header.php';

// Movie + director
$stmt = $pdo->prepare(
    "SELECT m.*, d.DirectorName
     FROM movies m
     LEFT JOIN directors d ON d.DirectorID = m.DirectorID
     WHERE m.MovieID = :id"
);
$stmt->bindValue(':id', $movieId, PDO::PARAM_INT);
$stmt->execute();
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    ?>
    <div class="container py-5">
        <div class="panel text-center py-5">
            <h1 class="h4 mb-3">Movie not found</h1>
            <a class="btn btn-accent" href="index.php">Back to the store</a>
        </div>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Categories
$stmt = $pdo->prepare(
    "SELECT c.CategoryName
     FROM categories c
     JOIN moviecategories mc ON mc.CategoryID = c.CategoryID
     WHERE mc.MovieID = :id"
);
$stmt->bindValue(':id', $movieId, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Actors
$stmt = $pdo->prepare(
    "SELECT a.ActorName
     FROM actors a
     JOIN movieactors ma ON ma.ActorID = a.ActorID
     WHERE ma.MovieID = :id"
);
$stmt->bindValue(':id', $movieId, PDO::PARAM_INT);
$stmt->execute();
$actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
    <a href="index.php" class="text-decoration-none text-secondary d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Back to all movies
    </a>

    <div class="row g-4">
        <div class="col-md-4 col-lg-3">
            <img class="detail-poster" src="<?= e($movie['images']) ?>" alt="<?= e($movie['Title']) ?>"
                 onerror="this.onerror=null;this.src='uploads/1.JPEG'">
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="panel">
                <h1 class="fw-bold mb-1"><?= e($movie['Title']) ?></h1>
                <p class="text-secondary mb-3"><?= e($movie['ProduceYear']) ?></p>

                <div class="detail-price mb-4">$<?= number_format((float) $movie['UnitPrice'], 2) ?></div>

                <dl class="row mb-0">
                    <dt class="col-sm-3 text-secondary">Director</dt>
                    <dd class="col-sm-9"><?= e($movie['DirectorName'] ?? 'Unknown') ?></dd>

                    <dt class="col-sm-3 text-secondary">Categories</dt>
                    <dd class="col-sm-9">
                        <?php if (!$categories): ?>
                            <span class="text-secondary">None</span>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <span class="badge badge-soft me-1 mb-1"><?= e($category['CategoryName']) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-3 text-secondary">Actors</dt>
                    <dd class="col-sm-9">
                        <?php if (!$actors): ?>
                            <span class="text-secondary">None</span>
                        <?php else: ?>
                            <?php foreach ($actors as $actor): ?>
                                <span class="badge badge-soft me-1 mb-1"><?= e($actor['ActorName']) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </dd>
                </dl>

                <form action="actions/addToCard.php" method="POST" class="mt-4">
                    <input type="hidden" name="movieID" value="<?= (int) $movie['MovieID'] ?>">
                    <button type="submit" class="btn btn-accent btn-lg">
                        <i class="bi bi-cart-plus"></i> Add to cart
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
