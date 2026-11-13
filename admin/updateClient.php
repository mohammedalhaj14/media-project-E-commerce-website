<?php
$pageTitle   = 'Edit client';
$activeAdmin = 'clients';
require_once __DIR__ . '/includes/header.php';

// The client id arrives by POST from clients.php (GET is accepted too).
$rawId = $_POST['id'] ?? ($_GET['id'] ?? '');
$id    = (is_string($rawId) && ctype_digit($rawId)) ? (int) $rawId : 0;

// Values come from the database, not from the browser.
$stmt = $pdo->prepare("SELECT ClientID, FName, LName, Phone, Username FROM clients WHERE ClientID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    ?>
    <div class="panel text-center py-5">
        <h1 class="h4 mb-3">Client not found</h1>
        <a class="btn btn-accent" href="clients.php">Back to clients</a>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<a href="clients.php" class="text-decoration-none text-secondary d-inline-block mb-3">
    <i class="bi bi-arrow-left"></i> Back to clients
</a>

<div class="panel" style="max-width: 560px;">
    <h1 class="h3 fw-bold mb-1">Edit client</h1>
    <p class="text-secondary mb-4">Username: <?= e($client['Username']) ?></p>

    <form action="actions/updateClient.php" method="POST">
        <input type="hidden" name="id" value="<?= (int) $client['ClientID'] ?>">

        <div class="mb-3">
            <label for="fname" class="form-label">First name</label>
            <input type="text" name="fname" id="fname" class="form-control" maxlength="20"
                   value="<?= e($client['FName']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="lname" class="form-label">Last name</label>
            <input type="text" name="lname" id="lname" class="form-control" maxlength="20"
                   value="<?= e($client['LName']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" maxlength="20"
                   pattern="[0-9+\s\-]{5,20}" title="Digits, spaces, + and - only"
                   value="<?= e($client['Phone']) ?>" required>
        </div>

        <button type="submit" class="btn btn-accent">Save changes</button>
        <a href="clients.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
