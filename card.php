<?php
session_start();            // nothing may be printed before this line
if (!isset($_SESSION['user'])) {
    header('location:login.php');
    exit;
}
$userId = (int) $_SESSION['user'];

$pageTitle  = 'My cart';
$activePage = 'cart';
require_once __DIR__ . '/includes/header.php';

$flash = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);

// The open cart (Opened = 1) of this customer
$stmt = $pdo->prepare("SELECT SaleID, saleDate FROM sales WHERE ClientID = :user AND Opened = 1 ORDER BY SaleID LIMIT 1");
$stmt->bindValue(':user', $userId, PDO::PARAM_INT);
$stmt->execute();
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

$items = [];
if ($sale) {
    $stmt = $pdo->prepare(
        "SELECT m.MovieID, m.Title, m.ProduceYear, m.UnitPrice, m.images, m.Quantity AS Stock,
                sd.Qty, d.DirectorName
         FROM saledetail sd
         JOIN movies m    ON m.MovieID    = sd.MovieID
         JOIN directors d ON d.DirectorID = m.DirectorID
         WHERE sd.SaleID = :sale
         ORDER BY m.Title"
    );
    $stmt->bindValue(':sale', (int) $sale['SaleID'], PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$total = 0.0;
foreach ($items as $item) {
    $total += (float) $item['UnitPrice'] * (int) $item['Qty'];
}
?>

<div class="container py-4">
    <h1 class="h2 fw-bold mb-1">My cart</h1>
    <?php if ($sale && $items): ?>
        <p class="text-secondary mb-4">Basket #<?= (int) $sale['SaleID'] ?> &middot; created <?= e($sale['saleDate']) ?></p>
    <?php else: ?>
        <div class="mb-4"></div>
    <?php endif; ?>

    <?php if ($flash): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($flash) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div id="cartMessage" class="alert alert-warning d-none" role="alert"></div>

    <?php if (!$sale || !$items): ?>
        <div class="panel text-center py-5">
            <i class="bi bi-cart-x display-4 text-secondary"></i>
            <h2 class="h4 mt-3">Your cart is empty</h2>
            <p class="text-secondary">Find something to watch and add it to your cart.</p>
            <a class="btn btn-accent" href="index.php">Browse movies</a>
        </div>
    <?php else: ?>
        <div class="panel p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Movie</th><th>Director</th><th class="text-end">Price</th>
                            <th class="text-center">Qty</th><th class="text-end">Subtotal</th><th></th>
                        </tr>
                    </thead>
                    <tbody id="cartRows">
                        <?php foreach ($items as $item): ?>
                            <tr data-movie="<?= (int) $item['MovieID'] ?>" data-price="<?= e($item['UnitPrice']) ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= e($item['images']) ?>" alt="" width="44" height="64"
                                             style="object-fit: cover; border-radius: 6px;"
                                             onerror="this.onerror=null;this.src='uploads/1.JPEG'">
                                        <div>
                                            <div class="fw-semibold"><?= e($item['Title']) ?></div>
                                            <div class="text-secondary small"><?= e($item['ProduceYear']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= e($item['DirectorName']) ?></td>
                                <td class="text-end">$<?= number_format((float) $item['UnitPrice'], 2) ?></td>
                                <td class="text-center text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="decrease" aria-label="Decrease">&minus;</button>
                                    <span class="qty d-inline-block text-center fw-bold" style="min-width: 2rem;"><?= (int) $item['Qty'] ?></span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="increase" aria-label="Increase">+</button>
                                </td>
                                <td class="text-end line-total">$<?= number_format((float) $item['UnitPrice'] * (int) $item['Qty'], 2) ?></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-action="remove" aria-label="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th class="text-end fs-5" style="color: var(--accent);" id="cartTotal">$<?= number_format($total, 2) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <form action="payment.php" method="POST">
                <input type="hidden" name="saleID" value="<?= (int) $sale['SaleID'] ?>">
                <button type="submit" class="btn btn-accent btn-lg">
                    <i class="bi bi-credit-card"></i> Checkout
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php
if ($sale && $items) {
    $pageScript = '<script>const SALE_ID = ' . (int) $sale['SaleID'] . ';</script>' . <<<'JS'
<script>
(function () {
    const rows = document.getElementById('cartRows');
    const totalCell = document.getElementById('cartTotal');
    const messageBox = document.getElementById('cartMessage');

    function post(url, params) {
        return fetch(url, { method: 'POST', body: new URLSearchParams(params) })
            .then(function (response) { return response.json(); });
    }

    function money(value) { return '$' + value.toFixed(2); }

    function showMessage(text) {
        messageBox.textContent = text;
        messageBox.classList.toggle('d-none', text === '');
    }

    // Recalculate line totals and the total from what is on screen (the server already confirmed each change).
    function recalc() {
        let total = 0;
        rows.querySelectorAll('tr').forEach(function (row) {
            const line = parseFloat(row.dataset.price) * parseInt(row.querySelector('.qty').textContent, 10);
            row.querySelector('.line-total').textContent = money(line);
            total += line;
        });
        totalCell.textContent = money(total);
    }

    rows.addEventListener('click', async function (event) {
        const button = event.target.closest('button[data-action]');
        if (!button) { return; }

        const row = button.closest('tr');
        const movieId = row.dataset.movie;
        const qtyEl = row.querySelector('.qty');
        const qty = parseInt(qtyEl.textContent, 10);
        const action = button.dataset.action;

        showMessage('');
        row.querySelectorAll('button').forEach(function (b) { b.disabled = true; });

        try {
            if (action === 'remove') {
                const result = await post('actions/deleteRow.php', { delete: 'true', saleID: SALE_ID, movieID: movieId, qty: qty });
                if (result.delete === 'success') {
                    row.remove();
                    if (!rows.querySelector('tr')) { window.location.reload(); return; }
                } else {
                    showMessage('Could not remove this movie. Please try again.');
                }
            } else {
                const params = { saleID: SALE_ID, movieID: movieId, qty: qty };
                if (action === 'increase') { params.increase = 'increase'; }
                const result = await post('actions/increaseDecrease.php', params);

                if (result.increase) {
                    qtyEl.textContent = qty + 1;
                } else if (result.decrease) {
                    qtyEl.textContent = qty - 1;
                } else if (action === 'increase') {
                    showMessage('No more copies of this movie are available.');
                }
            }
            recalc();
        } catch (error) {
            console.error(error);
            showMessage('Something went wrong. Please try again.');
        } finally {
            row.querySelectorAll('button').forEach(function (b) { b.disabled = false; });
        }
    });
})();
</script>
JS;
}

require_once __DIR__ . '/includes/footer.php';
