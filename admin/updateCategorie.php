<?php
$pageTitle   = 'Edit category';
$activeAdmin = 'categories';
require_once __DIR__ . '/includes/header.php';

// The category id arrives by POST from categories.php (GET is accepted too).
$rawId = $_POST['id'] ?? ($_GET['id'] ?? '');
$id    = (is_string($rawId) && ctype_digit($rawId)) ? (int) $rawId : 0;

$stmt = $pdo->prepare("SELECT CategoryID, CategoryName FROM categories WHERE CategoryID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    ?>
    <div class="panel text-center py-5">
        <h1 class="h4 mb-3">Category not found</h1>
        <a class="btn btn-accent" href="categories.php">Back to categories</a>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<a href="categories.php" class="text-decoration-none text-secondary d-inline-block mb-3">
    <i class="bi bi-arrow-left"></i> Back to categories
</a>

<div class="panel" style="max-width: 560px;">
    <h1 class="h3 fw-bold mb-4">Edit category</h1>

    <form action="actions/updateCategorie.php" method="POST">
        <input type="hidden" name="id" value="<?= (int) $category['CategoryID'] ?>">

        <div class="mb-4">
            <label for="categorie" class="form-label">Category name</label>
            <input type="text" name="categorie" id="categorie" class="form-control" maxlength="20"
                   value="<?= e($category['CategoryName']) ?>" required>
        </div>

        <button type="submit" class="btn btn-accent">Save changes</button>
        <a href="categories.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
