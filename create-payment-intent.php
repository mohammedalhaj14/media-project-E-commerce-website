<?php
require 'vendor/autoload.php';
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
