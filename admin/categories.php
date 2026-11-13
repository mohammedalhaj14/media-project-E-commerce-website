<?php
$pageTitle   = 'Categories';
$activeAdmin = 'categories';
require_once __DIR__ . '/includes/header.php';

$limit  = 5;
$page   = query_page();
$offset = ($page - 1) * $limit;
$search = query_string('search');
$like   = '%' . $search . '%';

$stmt = $pdo->prepare(
    "SELECT CategoryID, CategoryName FROM categories
     WHERE CategoryName LIKE :search
     LIMIT :limit OFFSET :offset"
);
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE CategoryName LIKE :search");
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->execute();
$totalPages = (int) ceil(((int) $stmt->fetchColumn()) / $limit);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 fw-bold mb-0">Categories</h1>
    <form class="d-flex gap-2" method="GET">
        <input class="form-control" type="search" name="search" value="<?= e($search) ?>" placeholder="Search categories">
        <button class="btn btn-accent" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<?php flash('addCategorie', 'Category saved successfully'); ?>

<div class="panel p-0 overflow-hidden mb-4">
    <?php if (!$categories): ?>
        <p class="text-center text-secondary py-5 mb-0">No categories found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr><th>ID</th><th>Category name</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr data-cat="<?= (int) $category['CategoryID'] ?>">
                            <td><?= (int) $category['CategoryID'] ?></td>
                            <td><?= e($category['CategoryName']) ?></td>
                            <td class="text-end">
                                <form action="updateCategorie.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= (int) $category['CategoryID'] ?>">
                                    <input type="hidden" name="categorie" value="<?= e($category['CategoryName']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteCategory(<?= (int) $category['CategoryID'] ?>)">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
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
    <h2 class="h5 fw-bold mb-3">Add new category</h2>
    <form action="actions/addCategories.php" method="POST" class="d-flex gap-2">
        <input name="categories" type="text" class="form-control" placeholder="Category name" required>
        <button type="submit" class="btn btn-accent">Add</button>
    </form>
</div>

<?php
$pageScript = <<<'JS'
<script>
function deleteCategory(id) {
    if (!confirm('Delete this category?')) { return; }

    const body = new URLSearchParams();
    body.append('id', id);

    fetch('actions/deleteCategory.php', { method: 'POST', body: body })
        .then(function (response) { return response.json(); })
        .then(function (result) {
            if (result.success) {
                const row = document.querySelector('tr[data-cat="' + id + '"]');
                if (row) { row.remove(); }
            } else {
                alert('Failed to delete category');
            }
        })
        .catch(function (error) { console.error(error); alert('Failed to delete category'); });
}
</script>
JS;

require_once __DIR__ . '/includes/footer.php';
