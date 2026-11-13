<?php
$pageTitle   = 'Clients';
$activeAdmin = 'clients';
require_once __DIR__ . '/includes/header.php';

$limit  = 10;
$page   = query_page();
$offset = ($page - 1) * $limit;
$search = query_string('search');
$like   = '%' . $search . '%';

$stmt = $pdo->prepare(
    "SELECT ClientID, FName, LName, Phone, Username, type
     FROM clients
     WHERE FName LIKE :s1 OR LName LIKE :s2
     ORDER BY ClientID
     LIMIT :limit OFFSET :offset"
);
$stmt->bindValue(':s1', $like, PDO::PARAM_STR);
$stmt->bindValue(':s2', $like, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE FName LIKE :s1 OR LName LIKE :s2");
$stmt->bindValue(':s1', $like, PDO::PARAM_STR);
$stmt->bindValue(':s2', $like, PDO::PARAM_STR);
$stmt->execute();
$totalPages = (int) ceil(((int) $stmt->fetchColumn()) / $limit);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 fw-bold mb-0">Clients</h1>
    <form class="d-flex gap-2" method="GET">
        <input class="form-control" type="search" name="search" value="<?= e($search) ?>" placeholder="Search by first or last name">
        <button class="btn btn-accent" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<?php
flash('deleteClient', 'Client deleted successfully');
flash('updateClient', 'Client updated successfully');
?>

<div class="panel p-0 overflow-hidden mb-4">
    <?php if (!$clients): ?>
        <p class="text-center text-secondary py-5 mb-0">No clients found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>First name</th><th>Last name</th><th>Username</th><th>Phone</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <?php $isAdminAccount = ((int) $client['type'] === 1); ?>
                        <tr>
                            <td><a href="clientInfo.php?id=<?= (int) $client['ClientID'] ?>"><?= (int) $client['ClientID'] ?></a></td>
                            <td><?= e($client['FName']) ?></td>
                            <td><?= e($client['LName']) ?></td>
                            <td>
                                <?= e($client['Username']) ?>
                                <?php if ($isAdminAccount): ?><span class="badge badge-soft ms-1">Admin</span><?php endif; ?>
                            </td>
                            <td><?= e($client['Phone']) ?></td>
                            <td class="text-end text-nowrap">
                                <form action="updateClient.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= (int) $client['ClientID'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </form>
                                <?php if ($isAdminAccount): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" disabled
                                            title="Admin accounts can not be deleted here">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                <?php else: ?>
                                    <form action="actions/deleteClient.php" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this client and all of their orders?');">
                                        <input type="hidden" name="id" value="<?= (int) $client['ClientID'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php pagination($page, $totalPages, ['search' => $search]); ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
