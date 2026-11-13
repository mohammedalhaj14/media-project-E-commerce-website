<?php
// Step 2 of the payment: called by payment.php AFTER Stripe confirmed the card payment.
// It asks Stripe whether the money was really received, then closes the cart and reduces the stock.
session_start();
header('Content-Type: application/json');

function respond(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if (!isset($_SESSION['user'])) {
    respond(['error' => 'Please log in again.'], 401);
}
$userId = (int) $_SESSION['user'];

$input    = json_decode(file_get_contents('php://input'), true);
$saleId   = (is_array($input) && isset($input['saleID']) && ctype_digit((string) $input['saleID'])) ? (int) $input['saleID'] : 0;
$intentId = (is_array($input) && isset($input['paymentIntentId']) && is_string($input['paymentIntentId'])) ? $input['paymentIntentId'] : '';

if ($saleId < 1 || !preg_match('/^pi_[A-Za-z0-9_]+$/', $intentId)) {
    respond(['error' => 'Invalid request.'], 400);
}

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/includes/cart_helpers.php';

if (!file_exists(__DIR__ . '/stripe-config.php')) {
    error_log('stripe-config.php is missing');
    respond(['error' => 'Payments are not configured yet.'], 500);
}
$config = require __DIR__ . '/stripe-config.php';

require_once __DIR__ . '/vendor/autoload.php';

/* ---------- 1. ask Stripe, do not trust the browser ---------- */

try {
    \Stripe\Stripe::setApiKey($config['secret_key']);
    $intent = \Stripe\PaymentIntent::retrieve($intentId);
} catch (\Throwable $e) {
    error_log('Stripe error (retrieve): ' . $e->getMessage());
    respond(['error' => 'We could not verify the payment. Reference: ' . $intentId], 502);
}

$meta = $intent->metadata->toArray();
if ($intent->status !== 'succeeded'
    || (string) ($meta['sale_id'] ?? '') !== (string) $saleId
    || (string) ($meta['client_id'] ?? '') !== (string) $userId) {
    respond(['error' => 'This payment does not match your order.'], 400);
}

/* ---------- 2. close the cart and update the stock, all or nothing ---------- */

try {
    $pdo->beginTransaction();

    // Lock the sale row so two requests can not finish the same order twice.
    $stmt = $pdo->prepare("SELECT Opened FROM sales WHERE SaleID = :sale AND ClientID = :user FOR UPDATE");
    $stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
    $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sale) {
        $pdo->rollBack();
        respond(['error' => 'Order not found.'], 404);
    }

    if ((int) $sale['Opened'] === 0) {                 // already completed: answering again is safe
        $pdo->commit();
        $_SESSION['msg'] = 'Payment successful! Thank you for your order.';
        respond(['success' => true]);
    }

    $cart = load_open_cart($pdo, $saleId, $userId);
    if ($cart === null || $cart['totalCents'] !== (int) $intent->amount_received) {
        $pdo->rollBack();
        error_log("Order $saleId: paid amount does not match the cart (payment $intentId)");
        respond(['error' => 'Your cart changed after the payment started. Please contact us. Reference: ' . $intentId], 409);
    }

    $update = $pdo->prepare("UPDATE movies SET Quantity = Quantity - :qty WHERE MovieID = :movie AND Quantity >= :need");
    foreach ($cart['items'] as $item) {
        $update->bindValue(':qty', (int) $item['Qty'], PDO::PARAM_INT);
        $update->bindValue(':need', (int) $item['Qty'], PDO::PARAM_INT);
        $update->bindValue(':movie', (int) $item['MovieID'], PDO::PARAM_INT);
        $update->execute();
        if ($update->rowCount() === 0) {
            $pdo->rollBack();
            error_log("Order $saleId: not enough stock after payment $intentId");
            respond(['error' => 'Payment received but an item just sold out. Please contact us. Reference: ' . $intentId], 409);
        }
    }

    $close = $pdo->prepare("UPDATE sales SET Opened = 0 WHERE SaleID = :sale");
    $close->bindValue(':sale', $saleId, PDO::PARAM_INT);
    $close->execute();

    $pdo->commit();
} catch (\Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Finalize error: ' . $e->getMessage());
    respond(['error' => 'We could not complete your order. Please contact us. Reference: ' . $intentId], 500);
}

$_SESSION['msg'] = 'Payment successful! Thank you for your order.';
respond(['success' => true]);
