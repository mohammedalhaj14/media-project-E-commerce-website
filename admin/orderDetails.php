<?php
$pageTitle   = 'Order details';
$activeAdmin = 'clients';
require_once __DIR__ . '/includes/header.php';

$id = (isset($_GET['id']) && ctype_digit((string) $_GET['id'])) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare(
    "SELECT s.SaleID, s.saleDate, s.Opened, s.ClientID, c.FName, c.LName
     FROM sales s
     JOIN clients c ON c.ClientID = s.ClientID
     WHERE s.SaleID = :id"
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    ?>
    <div class="panel text-center py-5">
        <h1 class="h4 mb-3">Order not found</h1>
        <a class="btn btn-accent" href="clients.php">Back to clients</a>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare(
    "SELECT m.Title, sd.Qty, m.UnitPrice
     FROM saledetail sd
     JOIN movies m ON m.MovieID = sd.MovieID
     WHERE sd.SaleID = :id"
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0.0;
foreach ($items as $item) {
    $total += (float) $item['UnitPrice'] * (int) $item['Qty'];
}
?>

<a href="clientInfo.php?id=<?= (int) $order['ClientID'] ?>" class="text-decoration-none text-secondary d-inline-block mb-3">
    <i class="bi bi-arrow-left"></i> Back to <?= e($order['FName'] . ' ' . $order['LName']) ?>
</a>

<div class="d-flex flex-wrap align-items-center gap-3 mb-4">
    <h1 class="h2 fw-bold mb-0">Order #<?= (int) $order['SaleID'] ?></h1>
    <?php if ((int) $order['Opened'] === 1): ?>
        <span class="badge text-bg-warning">Cart (not paid)</span>
    <?php else: ?>
        <span class="badge text-bg-success">Paid</span>
    <?php endif; ?>
    <span class="text-secondary"><?= e($order['saleDate']) ?></span>
</div>

<div class="panel p-0 overflow-hidden">
    <?php if (!$items): ?>
        <p class="text-center text-secondary py-5 mb-0">This order has no items.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead>
                    <tr><th>Movie</th><th class="text-end">Qty</th><th class="text-end">Unit price</th><th class="text-end">Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['Title']) ?></td>
                            <td class="text-end"><?= (int) $item['Qty'] ?></td>
                            <td class="text-end">$<?= number_format((float) $item['UnitPrice'], 2) ?></td>
                            <td class="text-end">$<?= number_format((float) $item['UnitPrice'] * (int) $item['Qty'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end" style="color: var(--accent);">$<?= number_format($total, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
