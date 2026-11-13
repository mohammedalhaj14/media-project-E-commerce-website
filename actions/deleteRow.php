<?php
require_once __DIR__ . '/_helpers.php';

$userId = require_user_json();            // 401 JSON when nobody is logged in

if (($_POST['delete'] ?? '') !== 'true') {
    json_out(['error' => 'Missing parameters.']);
}

$saleId  = post_id('saleID');
$movieId = post_id('movieID');
if ($saleId === null || $movieId === null) {
    json_out(['error' => 'Missing parameters.']);
}

require_once __DIR__ . '/../connection.php';

// Only a row in this customer's own OPEN cart can be removed.
$stmt = $pdo->prepare(
    "DELETE sd FROM saledetail sd
     JOIN sales s ON s.SaleID = sd.SaleID
     WHERE sd.SaleID = :sale AND sd.MovieID = :movie AND s.ClientID = :user AND s.Opened = 1"
);
$stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
$stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
$stmt->bindValue(':user', $userId, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    json_out(['delete' => 'success', 'message' => 'Item deleted successfully.']);
}

json_out(['error' => "Item not found or couldn't be deleted."]);
