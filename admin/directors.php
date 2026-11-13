<?php
$pageTitle   = 'Directors';
$activeAdmin = 'directors';
require_once __DIR__ . '/includes/header.php';

$limit  = 5;
$page   = query_page();
$offset = ($page - 1) * $limit;
$search = query_string('search');
$like   = '%' . $search . '%';

$stmt = $pdo->prepare(
    "SELECT DirectorID, DirectorName FROM directors
     WHERE DirectorName LIKE :search
     LIMIT :limit OFFSET :offset"
);
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$directors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM directors WHERE DirectorName LIKE :search");
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->execute();
$totalPages = (int) ceil(((int) $stmt->fetchColumn()) / $limit);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 fw-bold mb-0">Directors</h1>
    <form class="d-flex gap-2" method="GET">
        <input class="form-control" type="search" name="search" value="<?= e($search) ?>" placeholder="Search directors">
        <button class="btn btn-accent" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<?php
flash('addDirectors', 'Director added successfully');
flash('deleteDirector', 'Director deleted successfully');
?>

<div class="panel p-0 overflow-hidden mb-4">
    <?php if (!$directors): ?>
        <p class="text-center text-secondary py-5 mb-0">No directors found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr><th>ID</th><th>Director name</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($directors as $director): ?>
                        <tr>
                            <td><?= (int) $director['DirectorID'] ?></td>
                            <td><?= e($director['DirectorName']) ?></td>
                            <td class="text-end">
                                <form action="actions/deleteDirector.php" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this director?');">
                                    <input type="hidden" name="id" value="<?= (int) $director['DirectorID'] ?>">
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

<div class="panel mt-4" style="max-width: 560px;">
    <h2 class="h5 fw-bold mb-3">Add new director</h2>
    <form action="actions/addDirectors.php" method="POST" class="d-flex gap-2">
        <input name="directors" type="text" class="form-control" placeholder="Director name" required>
        <button type="submit" class="btn btn-accent">Add</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
