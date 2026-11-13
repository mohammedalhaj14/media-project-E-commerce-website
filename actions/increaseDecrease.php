<?php
require_once __DIR__ . '/_helpers.php';

$userId  = require_user_json();           // 401 JSON when nobody is logged in
$movieId = post_id('movieID');
$saleId  = post_id('saleID');

if ($movieId === null || $saleId === null) {
    json_out(['errorOne' => 'error']);
}

require_once __DIR__ . '/../connection.php';

// The row must belong to THIS customer's OPEN cart. The quantities come from the database,
// not from the browser, so they can not be faked.
$stmt = $pdo->prepare(
    "SELECT sd.Qty AS Qty, m.Quantity AS Stock
     FROM saledetail sd
     JOIN sales  s ON s.SaleID  = sd.SaleID
     JOIN movies m ON m.MovieID = sd.MovieID
     WHERE sd.SaleID = :sale AND sd.MovieID = :movie AND s.ClientID = :user AND s.Opened = 1"
);
$stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
$stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
$stmt->bindValue(':user', $userId, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    json_out(['error' => 'error']);
}

$qty   = (int) $row['Qty'];
$stock = (int) $row['Stock'];

if (isset($_POST['increase'])) {
    if ($qty >= $stock) {
        json_out(['error' => 'error']);
    }
    $sql    = "UPDATE saledetail SET Qty = Qty + 1 WHERE SaleID = :sale AND MovieID = :movie";
    $answer = ['increase' => 'success increase'];
} else {
    if ($qty <= 1) {
        json_out(['error' => 'error']);
    }
    $sql    = "UPDATE saledetail SET Qty = Qty - 1 WHERE SaleID = :sale AND MovieID = :movie";
    $answer = ['decrease' => 'decrease success'];
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
$stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
$stmt->execute();

json_out($answer);
