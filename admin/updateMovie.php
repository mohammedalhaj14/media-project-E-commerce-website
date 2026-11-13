<?php
$pageTitle   = 'Edit movie';
$activeAdmin = 'movies';
require_once __DIR__ . '/includes/header.php';

// The movie id arrives by POST from movies.php (GET is accepted too).
$rawId = $_POST['id'] ?? ($_GET['id'] ?? '');
$id    = (is_string($rawId) && ctype_digit($rawId)) ? (int) $rawId : 0;

// Values are read from the database, not trusted from the browser.
$stmt = $pdo->prepare(
    "SELECT MovieID, Title, ProduceYear, UnitPrice, Quantity, DirectorID
     FROM movies WHERE MovieID = :id"
);
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

$directors = $pdo->query("SELECT DirectorID, DirectorName FROM directors ORDER BY DirectorName")->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="movies.php" class="text-decoration-none text-secondary d-inline-block mb-3">
    <i class="bi bi-arrow-left"></i> Back to movies
</a>

<div class="panel" style="max-width: 720px;">
    <h1 class="h3 fw-bold mb-4">Edit movie</h1>

    <form action="actions/updateMovie.php" method="POST">
        <input type="hidden" name="id" value="<?= (int) $movie['MovieID'] ?>">

        <div class="row g-3">
            <div class="col-12">
                <label for="title" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="title" value="<?= e($movie['Title']) ?>" required>
            </div>
            <div class="col-md-4">
                <label for="year" class="form-label">Year</label>
                <input name="year" type="number" min="1888" max="2100" class="form-control" id="year" value="<?= e($movie['ProduceYear']) ?>" required>
            </div>
            <div class="col-md-4">
                <label for="price" class="form-label">Price</label>
                <input name="price" type="number" min="0" step="0.01" class="form-control" id="price" value="<?= e($movie['UnitPrice']) ?>" required>
            </div>
            <div class="col-md-4">
                <label for="quantite" class="form-label">Quantity</label>
                <input name="quantite" type="number" min="0" step="1" class="form-control" id="quantite" value="<?= e($movie['Quantity']) ?>" required>
            </div>
            <div class="col-12">
                <label for="directorID" class="form-label">Director</label>
                <select name="directorID" id="directorID" class="form-select">
                    <?php if (!$directors): ?>
                        <option value="">None</option>
                    <?php else: ?>
                        <?php foreach ($directors as $director): ?>
                            <option value="<?= (int) $director['DirectorID'] ?>"
                                <?= (int) $director['DirectorID'] === (int) $movie['DirectorID'] ? 'selected' : '' ?>>
                                <?= e($director['DirectorName']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-accent mt-4">Save changes</button>
        <a href="movies.php" class="btn btn-outline-secondary mt-4">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
