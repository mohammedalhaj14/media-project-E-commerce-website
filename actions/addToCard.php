<?php
require_once __DIR__ . '/_helpers.php';

// Only logged-in customers have a cart.
if (!isset($_SESSION['user'])) {
    header('location:../login.php');
    exit;
}
$userId = (int) $_SESSION['user'];

// Show a message on the home page and stop.
function cart_message(string $message): void
{
    $_SESSION['msg'] = $message;
    header('location:../index.php');
    exit;
}

$movieId = post_id('movieID');
if ($movieId === null) {
    cart_message('Invalid movie.');
}

require_once __DIR__ . '/../connection.php';

// The movie must exist and be in stock.
$stmt = $pdo->prepare("SELECT Quantity FROM movies WHERE MovieID = :movie");
$stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
$stmt->execute();
$stock = $stmt->fetchColumn();

if ($stock === false) {
    cart_message('This movie does not exist.');
}
if ((int) $stock < 1) {
    cart_message('Sorry, this movie is out of stock.');
}

// Find the open cart, or create one.
$stmt = $pdo->prepare("SELECT SaleID FROM sales WHERE ClientID = :user AND Opened = 1 LIMIT 1");
$stmt->bindValue(':user', $userId, PDO::PARAM_INT);
$stmt->execute();
$saleId = $stmt->fetchColumn();

if ($saleId === false) {
    $stmt = $pdo->prepare("INSERT INTO sales (ClientID, saleDate, Opened) VALUES (:user, CURRENT_TIMESTAMP, 1)");
    $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $saleId = $pdo->lastInsertId();
}
$saleId = (int) $saleId;

// Is the movie already in the cart?
$stmt = $pdo->prepare("SELECT Qty FROM saledetail WHERE SaleID = :sale AND MovieID = :movie");
$stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
$stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
$stmt->execute();
$currentQty = $stmt->fetchColumn();

if ($currentQty === false) {
    $stmt = $pdo->prepare("INSERT INTO saledetail (SaleID, MovieID, Qty) VALUES (:sale, :movie, 1)");
    $stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
    $stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
    $stmt->execute();
    cart_message('Movie added to your cart.');
}

if ((int) $currentQty + 1 > (int) $stock) {
    cart_message('You already have the maximum available quantity in your cart.');
}

$stmt = $pdo->prepare("UPDATE saledetail SET Qty = Qty + 1 WHERE SaleID = :sale AND MovieID = :movie");
$stmt->bindValue(':sale', $saleId, PDO::PARAM_INT);
$stmt->bindValue(':movie', $movieId, PDO::PARAM_INT);
$stmt->execute();

cart_message('Movie quantity updated in your cart.');
