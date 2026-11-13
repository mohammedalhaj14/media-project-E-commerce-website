<?php
session_start();            // nothing may be printed before this line
$pageTitle  = 'Home';
$activePage = 'home';
require_once __DIR__ . '/includes/header.php';

// One-time message (set by other pages through $_SESSION["msg"])
$flash = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);

// Categories for the sidebar
$stmt = $pdo->prepare("SELECT CategoryID, CategoryName FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Selected category (optional)
$categoryId = null;
if (isset($_GET['categorieID']) && trim($_GET['categorieID']) !== '' && is_numeric($_GET['categorieID'])) {
    $categoryId = (int) $_GET['categorieID'];
}

// Movies
if ($categoryId === null) {
    $stmt = $pdo->prepare("SELECT MovieID, Title, ProduceYear, UnitPrice, images FROM movies");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        "SELECT m.MovieID, m.Title, m.ProduceYear, m.UnitPrice, m.images
         FROM movies m
         JOIN moviecategories mc ON mc.MovieID = m.MovieID
         WHERE mc.CategoryID = :id"
    );
    $stmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
}
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">

    <?php if ($flash): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($flash) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Search -->
    <div class="search-wrap mb-4">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
            <input type="text" id="search" class="form-control" placeholder="Search movies..." autocomplete="off">
        </div>
        <div id="searching" class="search-results d-none"></div>
    </div>

    <div class="row g-4">
        <!-- Categories -->
        <aside class="col-lg-3">
            <div class="panel p-3">
                <h2 class="h6 text-uppercase text-secondary mb-3">Categories</h2>
                <div class="list-group category-list">
                    <a href="index.php" class="list-group-item list-group-item-action <?= $categoryId === null ? 'active' : '' ?>">All movies</a>
                    <?php if (!$categories): ?>
                        <span class="list-group-item text-secondary">No categories yet</span>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <a href="index.php?categorieID=<?= (int) $category['CategoryID'] ?>"
                               class="list-group-item list-group-item-action <?= $categoryId === (int) $category['CategoryID'] ? 'active' : '' ?>">
                                <?= e($category['CategoryName']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <!-- Movies -->
        <section class="col-lg-9">
            <?php if (!$movies): ?>
                <div class="panel text-center text-secondary py-5">No movies found.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($movies as $movie): ?>
                        <div class="col-6 col-md-4 col-xl-3">
                            <a class="movie-card" href="movieDetails.php?movieID=<?= (int) $movie['MovieID'] ?>">
                                <div class="poster">
                                    <img src="<?= e($movie['images']) ?>" alt="<?= e($movie['Title']) ?>" loading="lazy"
                                         onerror="this.onerror=null;this.src='uploads/1.JPEG'">
                                </div>
                                <div class="info">
                                    <h3><?= e($movie['Title']) ?></h3>
                                    <div class="meta">
                                        <span><?= e($movie['ProduceYear']) ?></span>
                                        <span class="price">$<?= number_format((float) $movie['UnitPrice'], 2) ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php
// Live search script (relative URL, so it works locally and on the host)
$pageScript = <<<'JS'
<script>
(function () {
    const input = document.getElementById('search');
    const box = document.getElementById('searching');
    let timer;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const query = input.value.trim();
        if (query === '') { box.classList.add('d-none'); box.innerHTML = ''; return; }
        timer = setTimeout(function () { search(query); }, 250);
    });

    document.addEventListener('click', function (event) {
        if (!box.contains(event.target) && event.target !== input) { box.classList.add('d-none'); }
    });

    function search(query) {
        const body = new URLSearchParams();
        body.append('search', query);

        fetch('actions/search.php', { method: 'POST', body: body })
            .then(function (response) { return response.json(); })
            .then(render)
            .catch(function (error) { console.error(error); });
    }

    function render(result) {
        box.innerHTML = '';
        if (result && result.success && result.success.length) {
            result.success.forEach(function (movie) {
                const link = document.createElement('a');
                link.href = 'movieDetails.php?movieID=' + encodeURIComponent(movie.MovieID);
                link.textContent = movie.Title;
                box.appendChild(link);
            });
        } else {
            const empty = document.createElement('div');
            empty.className = 'p-3 text-secondary';
            empty.textContent = 'No movies found';
            box.appendChild(empty);
        }
        box.classList.remove('d-none');
    }
})();
</script>
JS;

require_once __DIR__ . '/includes/footer.php';
