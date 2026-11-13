<?php
$pageTitle   = 'Client details';
$activeAdmin = 'clients';
require_once __DIR__ . '/includes/header.php';

$id = (isset($_GET['id']) && ctype_digit((string) $_GET['id'])) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare(
    "SELECT c.ClientID, c.FName, c.LName, c.Phone, c.Address, c.Username,
            g.GenderName, co.CountryName
     FROM clients c
     JOIN genders g    ON g.GenderID    = c.GenderID
     JOIN countries co ON co.CountryID  = c.CountryID
     WHERE c.ClientID = :id"
);
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

// Orders (Opened = 1 is a cart that was not paid yet)
$stmt = $pdo->prepare(
    "SELECT s.SaleID, s.saleDate, s.Opened, SUM(m.UnitPrice * sd.Qty) AS total
     FROM sales s
     JOIN saledetail sd ON sd.SaleID  = s.SaleID
     JOIN movies m      ON m.MovieID  = sd.MovieID
     WHERE s.ClientID = :id
     GROUP BY s.SaleID, s.saleDate, s.Opened
     ORDER BY s.SaleID DESC"
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="clients.php" class="text-decoration-none text-secondary d-inline-block mb-3">
    <i class="bi bi-arrow-left"></i> Back to clients
</a>
<h1 class="h2 fw-bold mb-4"><?= e($client['FName'] . ' ' . $client['LName']) ?></h1>

<div class="panel mb-4">
    <dl class="row mb-0">
        <dt class="col-sm-3 text-secondary">ID</dt>
        <dd class="col-sm-9"><?= (int) $client['ClientID'] ?></dd>

        <dt class="col-sm-3 text-secondary">Username</dt>
        <dd class="col-sm-9"><?= e($client['Username']) ?></dd>

        <dt class="col-sm-3 text-secondary">Gender</dt>
        <dd class="col-sm-9"><?= e($client['GenderName']) ?></dd>

        <dt class="col-sm-3 text-secondary">Phone</dt>
        <dd class="col-sm-9"><?= e($client['Phone']) ?></dd>

        <dt class="col-sm-3 text-secondary">Address</dt>
        <dd class="col-sm-9"><?= e($client['Address']) ?></dd>

        <dt class="col-sm-3 text-secondary">Country</dt>
        <dd class="col-sm-9"><?= e($client['CountryName']) ?></dd>
    </dl>
</div>

<h2 class="h4 fw-bold mb-3">Orders</h2>

<div class="panel p-0 overflow-hidden">
    <?php if (!$orders): ?>
        <p class="text-center text-secondary py-5 mb-0">This client has no orders.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr><th>Order</th><th>Date</th><th>Status</th><th class="text-end">Total</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><a href="orderDetails.php?id=<?= (int) $order['SaleID'] ?>">#<?= (int) $order['SaleID'] ?></a></td>
                            <td><?= e($order['saleDate']) ?></td>
                            <td>
                                <?php if ((int) $order['Opened'] === 1): ?>
                                    <span class="badge text-bg-warning">Cart (not paid)</span>
                                <?php else: ?>
                                    <span class="badge text-bg-success">Paid</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">$<?= number_format((float) $order['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
