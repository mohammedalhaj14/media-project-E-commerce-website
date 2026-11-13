<?php
session_start();            // nothing may be printed before this line
if (!isset($_SESSION['user'])) {
    header('location:login.php');
    exit;
}
$userId = (int) $_SESSION['user'];

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/includes/cart_helpers.php';

// The cart id arrives by POST from the "Checkout" button in card.php.
$saleId = (isset($_POST['saleID']) && is_string($_POST['saleID']) && ctype_digit($_POST['saleID'])) ? (int) $_POST['saleID'] : 0;
$cart   = $saleId > 0 ? load_open_cart($pdo, $saleId, $userId) : null;

if ($cart === null) {
    $_SESSION['msg'] = 'Your cart is empty or no longer available.';
    header('location:card.php');
    exit;
}

$stripeReady  = file_exists(__DIR__ . '/stripe-config.php');
$stripeConfig = $stripeReady ? require __DIR__ . '/stripe-config.php' : [];
$stockProblem = cart_stock_problem($cart);
$total        = $cart['totalCents'] / 100;

$pageTitle  = 'Payment';
$activePage = 'cart';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-4">
    <a href="card.php" class="text-decoration-none text-secondary d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Back to my cart
    </a>
    <h1 class="h2 fw-bold mb-4">Payment</h1>

    <div class="row g-4">
        <!-- Order summary -->
        <div class="col-lg-7">
            <div class="panel p-0 overflow-hidden">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>Movie</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart['items'] as $item): ?>
                            <tr>
                                <td><?= e($item['Title']) ?></td>
                                <td class="text-end"><?= (int) $item['Qty'] ?></td>
                                <td class="text-end">$<?= number_format($item['UnitCents'] / 100, 2) ?></td>
                                <td class="text-end">$<?= number_format($item['UnitCents'] * (int) $item['Qty'] / 100, 2) ?></td>
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
        </div>

        <!-- Card form -->
        <div class="col-lg-5">
            <div class="panel">
                <h2 class="h5 fw-bold mb-3">Card details</h2>

                <?php if (!$stripeReady): ?>
                    <div class="alert alert-warning mb-0">Payments are not configured yet (stripe-config.php is missing).</div>
                <?php elseif ($stockProblem !== null): ?>
                    <div class="alert alert-warning">Not enough stock for "<?= e($stockProblem) ?>". Please update your cart.</div>
                    <a href="card.php" class="btn btn-accent w-100">Back to my cart</a>
                <?php else: ?>
                    <form id="payment-form">
                        <div id="card-element" class="form-control py-3 mb-2"></div>
                        <div id="card-errors" class="text-danger small mb-3" role="alert"></div>
                        <button type="submit" id="pay-button" class="btn btn-accent btn-lg w-100"
                                data-label="Pay $<?= number_format($total, 2) ?>">Pay $<?= number_format($total, 2) ?></button>
                    </form>
                    <p class="text-secondary small mt-3 mb-0">
                        <i class="bi bi-lock"></i> Test mode: use the card 4242 4242 4242 4242, any future date and any CVC.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
if ($stripeReady && $stockProblem === null) {
    $pageScript = '<script src="https://js.stripe.com/v3/"></script>'
        . '<script>const STRIPE_KEY = ' . json_encode($stripeConfig['publishable_key'] ?? '') . '; const SALE_ID = ' . $saleId . ';</script>'
        . <<<'JS'
<script>
(function () {
    const stripe = Stripe(STRIPE_KEY);
    const card = stripe.elements().create('card', {
        style: {
            base: { color: '#e8ebf2', fontSize: '16px', '::placeholder': { color: '#8f9bb0' } },
            invalid: { color: '#ff6b6b' }
        }
    });
    card.mount('#card-element');

    const form = document.getElementById('payment-form');
    const button = document.getElementById('pay-button');
    const errorBox = document.getElementById('card-errors');
    let paidIntentId = null;          // set once Stripe has taken the money, so we never charge twice

    card.on('change', function (event) {
        errorBox.textContent = event.error ? event.error.message : '';
    });

    function postJson(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        }).then(function (response) { return response.json(); });
    }

    function stop(message) {
        errorBox.textContent = message;
        button.disabled = false;
        button.textContent = paidIntentId ? 'Confirm my order again' : button.dataset.label;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        button.disabled = true;
        button.textContent = 'Processing...';
        errorBox.textContent = '';

        try {
            if (paidIntentId === null) {
                const created = await postJson('process-payment.php', { saleID: SALE_ID });
                if (!created.client_secret) { return stop(created.error || 'Could not start the payment.'); }

                const result = await stripe.confirmCardPayment(created.client_secret, { payment_method: { card: card } });
                if (result.error) { return stop(result.error.message); }
                if (!result.paymentIntent || result.paymentIntent.status !== 'succeeded') {
                    return stop('The payment was not completed.');
                }
                paidIntentId = result.paymentIntent.id;
            }

            const done = await postJson('finalize-payment.php', { saleID: SALE_ID, paymentIntentId: paidIntentId });
            if (done.success) {
                window.location.href = 'index.php';
            } else {
                stop(done.error || 'Payment received, but the order could not be completed.');
            }
        } catch (error) {
            console.error(error);
            stop('Something went wrong. Please try again.');
        }
    });
})();
</script>
JS;
}

require_once __DIR__ . '/includes/footer.php';
