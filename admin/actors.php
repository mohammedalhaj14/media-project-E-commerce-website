<?php
$pageTitle   = 'Actors';
$activeAdmin = 'actors';
require_once __DIR__ . '/includes/header.php';

$limit  = 5;
$page   = query_page();
$offset = ($page - 1) * $limit;
$search = query_string('search');
$like   = '%' . $search . '%';

$stmt = $pdo->prepare(
    "SELECT a.ActorID, a.ActorName, g.GenderName, n.NationalityName
     FROM actors a
     JOIN genders g ON g.GenderID = a.GenderID
     JOIN nationalities n ON n.NationalityID = a.NationalityID
     WHERE a.ActorName LIKE :search
     ORDER BY a.ActorID
     LIMIT :limit OFFSET :offset"
);
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$actors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM actors WHERE ActorName LIKE :search");
$stmt->bindValue(':search', $like, PDO::PARAM_STR);
$stmt->execute();
$totalPages = (int) ceil(((int) $stmt->fetchColumn()) / $limit);

$genders       = $pdo->query("SELECT GenderID, GenderName FROM genders")->fetchAll(PDO::FETCH_ASSOC);
$nationalities = $pdo->query("SELECT NationalityID, NationalityName FROM nationalities ORDER BY NationalityName")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 fw-bold mb-0">Actors</h1>
    <form class="d-flex gap-2" method="GET">
        <input class="form-control" type="search" name="search" value="<?= e($search) ?>" placeholder="Search actors">
        <button class="btn btn-accent" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<?php
flash('addActor', 'Actor added successfully');
flash('deleteActor', 'Actor deleted successfully');
?>

<div class="panel p-0 overflow-hidden mb-4">
    <?php if (!$actors): ?>
        <p class="text-center text-secondary py-5 mb-0">No actors found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr><th>ID</th><th>Actor</th><th>Gender</th><th>Nationality</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($actors as $actor): ?>
                        <tr>
                            <td><?= (int) $actor['ActorID'] ?></td>
                            <td><?= e($actor['ActorName']) ?></td>
                            <td><?= e($actor['GenderName']) ?></td>
                            <td><?= e($actor['NationalityName']) ?></td>
                            <td class="text-end">
                                <form action="actions/deleteActor.php" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this actor?');">
                                    <input type="hidden" name="id" value="<?= (int) $actor['ActorID'] ?>">
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

<div class="panel mt-4" style="max-width: 720px;">
    <h2 class="h5 fw-bold mb-3">Add new actor</h2>
    <form action="actions/addActor.php" method="POST">
        <div class="row g-3">
            <div class="col-12">
                <label for="actorName" class="form-label">Actor name</label>
                <input type="text" name="actor" class="form-control" id="actorName" required>
            </div>
            <div class="col-md-6">
                <label for="actorGender" class="form-label">Gender</label>
                <select name="gender" id="actorGender" class="form-select">
                    <?php foreach ($genders as $gender): ?>
                        <option value="<?= (int) $gender['GenderID'] ?>"><?= e($gender['GenderName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="actorNationality" class="form-label">Nationality</label>
                <select name="nationality" id="actorNationality" class="form-select">
                    <?php foreach ($nationalities as $nationality): ?>
                        <option value="<?= (int) $nationality['NationalityID'] ?>"><?= e($nationality['NationalityName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-accent mt-4">Add actor</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
