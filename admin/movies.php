<?php
$pageTitle   = 'Movies';
$activeAdmin = 'movies';
require_once __DIR__ . '/includes/header.php';

$limit  = 10;
$page   = query_page();
$offset = ($page - 1) * $limit;
$search = query_string('search');
$like   = '%' . $search . '%';

$stmt = $pdo->prepare(
    "SELECT m.MovieID, m.Title, m.ProduceYear, m.UnitPrice, m.Quantity, d.DirectorName
     FROM movies m
     JOIN directors d ON d.DirectorID = m.DirectorID
     WHERE m.Title LIKE :search
     ORDER BY m.MovieID
     LIMIT :limit OFFSET :offset"
);
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM movies WHERE Title LIKE :search");
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->execute();
$totalPages = (int) ceil(((int) $stmt->fetchColumn()) / $limit);

// Data for the "add movie" form
$directors  = $pdo->query("SELECT DirectorID, DirectorName FROM directors ORDER BY DirectorName")->fetchAll(PDO::FETCH_ASSOC);
$actors     = $pdo->query("SELECT ActorID, ActorName FROM actors ORDER BY ActorName")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryName")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 fw-bold mb-0">Movies</h1>
    <form class="d-flex gap-2" method="GET">
        <input class="form-control" type="search" name="search" value="<?= e($search) ?>" placeholder="Search movies">
        <button class="btn btn-accent" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<?php
flash('deleteMovie', 'Movie deleted successfully');
flash('updateMovie', 'Movie updated successfully');
flash('addMovie', 'Movie added successfully');
?>

<div class="panel p-0 overflow-hidden mb-4">
    <?php if (!$movies): ?>
        <p class="text-center text-secondary py-5 mb-0">No movies found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>Title</th><th>Year</th><th>Price</th><th>Qty</th><th>Director</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><a href="movieDetail.php?id=<?= (int) $movie['MovieID'] ?>"><?= (int) $movie['MovieID'] ?></a></td>
                            <td><?= e($movie['Title']) ?></td>
                            <td><?= e($movie['ProduceYear']) ?></td>
                            <td>$<?= number_format((float) $movie['UnitPrice'], 2) ?></td>
                            <td><?= (int) $movie['Quantity'] ?></td>
                            <td><?= e($movie['DirectorName']) ?></td>
                            <td class="text-end text-nowrap">
                                <form action="updateMovie.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= (int) $movie['MovieID'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </form>
                                <form action="actions/deleteMovie.php" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this movie?');">
                                    <input type="hidden" name="id" value="<?= (int) $movie['MovieID'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php pagination($page, $totalPages, ['search' => $search]); ?>

<div class="panel mt-4">
    <h2 class="h5 fw-bold mb-3">Add new movie</h2>
    <form action="actions/addMovie.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="title" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="title" required>
            </div>
            <div class="col-md-2">
                <label for="year" class="form-label">Year</label>
                <input name="year" type="number" min="1888" max="2100" class="form-control" id="year" required>
            </div>
            <div class="col-md-2">
                <label for="price" class="form-label">Price</label>
                <input name="price" type="number" min="0" step="0.01" class="form-control" id="price" required>
            </div>
            <div class="col-md-2">
                <label for="quantite" class="form-label">Quantity</label>
                <input name="quantite" type="number" min="0" step="1" class="form-control" id="quantite" required>
            </div>

            <div class="col-md-6">
                <label for="director" class="form-label">Director</label>
                <select name="director" id="director" class="form-select">
                    <?php if (!$directors): ?>
                        <option value="">None</option>
                    <?php else: ?>
                        <?php foreach ($directors as $director): ?>
                            <option value="<?= (int) $director['DirectorID'] ?>"><?= e($director['DirectorName']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="file" class="form-label">Poster image</label>
                <input type="file" name="file" id="file" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6">
                <label for="select-actors" class="form-label">Actors</label>
                <select id="select-actors" name="actors[]" class="form-select">
                    <?php if (!$actors): ?>
                        <option value="">None</option>
                    <?php else: ?>
                        <?php foreach ($actors as $actor): ?>
                            <option value="<?= (int) $actor['ActorID'] ?>"><?= e($actor['ActorName']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div id="container-actors"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                        onclick="addSelect('select-actors', 'container-actors')">
                    <i class="bi bi-plus-lg"></i> Add another actor
                </button>
            </div>

            <div class="col-md-6">
                <label for="select-categories" class="form-label">Categories</label>
                <select id="select-categories" name="categories[]" class="form-select">
                    <?php if (!$categories): ?>
                        <option value="">None</option>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['CategoryID'] ?>"><?= e($category['CategoryName']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div id="container-categories"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                        onclick="addSelect('select-categories', 'container-categories')">
                    <i class="bi bi-plus-lg"></i> Add another category
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-accent mt-4">Add movie</button>
    </form>
</div>

<?php
$pageScript = <<<'JS'
<script>
// Clone a <select> (keeping name="...[]") and give it a remove button.
function addSelect(sourceId, containerId) {
    const clone = document.getElementById(sourceId).cloneNode(true);
    clone.removeAttribute('id');

    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex gap-2 mt-2';

    const remove = document.createElement('button');
    remove.type = 'button';
    remove.className = 'btn btn-outline-danger btn-sm';
    remove.textContent = '\u00d7';
    remove.addEventListener('click', function () { wrapper.remove(); });

    wrapper.appendChild(clone);
    wrapper.appendChild(remove);
    document.getElementById(containerId).appendChild(wrapper);
}
</script>
JS;

require_once __DIR__ . '/includes/footer.php';
