<?php
// Cart helpers shared by payment.php, process-payment.php and finalize-payment.php.
// The amount to charge is ALWAYS calculated here from the database, never taken from the browser.

// The open cart of a customer with its lines and the total in cents, or null when it does not exist / is empty.
function load_open_cart(PDO $pdo, int $saleId, int $userId): ?array
{
    $stmt = $pdo->prepare("SELECT SaleID FROM sales WHERE SaleID = :sale AND ClientID = :user AND Opened = 1");
    $stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
    $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
    $stmt->execute();
    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT m.MovieID, m.Title, m.UnitPrice, m.Quantity AS Stock, sd.Qty
         FROM saledetail sd
         JOIN movies m ON m.MovieID = sd.MovieID
         WHERE sd.SaleID = :sale
         ORDER BY m.Title"
    );
    $stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$items) {
        return null;
    }

    $totalCents = 0;
    foreach ($items as $index => $item) {
        $unitCents = (int) round(((float) $item['UnitPrice']) * 100);
        $items[$index]['UnitCents'] = $unitCents;
        $totalCents += $unitCents * (int) $item['Qty'];
    }

    return ['items' => $items, 'totalCents' => $totalCents];
}

// Title of the first movie that is not available in the requested quantity, or null when all is in stock.
function cart_stock_problem(array $cart): ?string
{
    foreach ($cart['items'] as $item) {
        if ((int) $item['Qty'] > (int) $item['Stock']) {
            return (string) $item['Title'];
        }
    }
    return null;
}
