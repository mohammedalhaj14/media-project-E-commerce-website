<?php
// Step 1 of the payment: create a Stripe PaymentIntent.
// The amount is calculated here from the database. The cart is NOT closed and the stock is NOT changed yet;
// that happens in finalize-payment.php after Stripe confirms that the money was received.
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

$input  = json_decode(file_get_contents('php://input'), true);
$saleId = (is_array($input) && isset($input['saleID']) && ctype_digit((string) $input['saleID'])) ? (int) $input['saleID'] : 0;
if ($saleId < 1) {
    respond(['error' => 'Invalid request.'], 400);
}

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/includes/cart_helpers.php';

$cart = load_open_cart($pdo, $saleId, $userId);
if ($cart === null) {
    respond(['error' => 'Your cart is empty or no longer available.'], 404);
}

$problem = cart_stock_problem($cart);
if ($problem !== null) {
    respond(['error' => 'Not enough stock for "' . $problem . '". Please update your cart.'], 409);
}

if ($cart['totalCents'] < 50) {                       // Stripe minimum charge
    respond(['error' => 'The order total is too small to be paid by card.'], 400);
}

if (!file_exists(__DIR__ . '/stripe-config.php')) {
    error_log('stripe-config.php is missing');
    respond(['error' => 'Payments are not configured yet.'], 500);
}
$config = require __DIR__ . '/stripe-config.php';

require_once __DIR__ . '/vendor/autoload.php';

try {
    \Stripe\Stripe::setApiKey($config['secret_key']);
    $intent = \Stripe\PaymentIntent::create([
        'amount'               => $cart['totalCents'],
        'currency'             => $config['currency'] ?? 'usd',
        'payment_method_types' => ['card'],
        'metadata'             => ['sale_id' => (string) $saleId, 'client_id' => (string) $userId],
    ]);
} catch (\Throwable $e) {
    error_log('Stripe error (create): ' . $e->getMessage());
    respond(['error' => 'The payment service is not available. Please try again later.'], 502);
}

respond(['client_secret' => $intent->client_secret, 'payment_intent_id' => $intent->id]);
