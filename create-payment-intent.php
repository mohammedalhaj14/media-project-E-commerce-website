<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QJBGNK9JqRD1EchtmBO2ZRnfGcfK5RgGlNLwlR9ByElxLUeOIaioV5KzqYmFsWHE9DZMvfHWRX1MfgDUa37zzGc00QMl3Rqs6');

try {
    // إعداد المبلغ وعملة الدفع (مثلاً: 10.00 دولار أمريكي = 1000 سنت)
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 1000, // القيمة هنا بالسنتات
        'currency' => 'usd',
        'payment_method_types' => ['card'],
    ]);

    // إعادة client_secret إلى واجهة المستخدم
    header('Content-Type: application/json');
    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
