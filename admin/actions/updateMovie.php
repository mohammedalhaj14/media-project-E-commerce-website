<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id         = post_id('id');
$title      = post_text('title', 150);
$year       = post_int_range('year', 1888, 2100);
$price      = post_decimal('price');
$quantity   = post_int_range('quantite', 0, 999999);
$directorId = post_id('directorID');

if ($id === null || $title === '' || $year === null || $price === null
    || $quantity === null || $directorId === null) {
    flash_redirect('updateMovie', false, 'movies.php');
}

require_once __DIR__ . '/../../connection.php';

if (!all_exist($pdo, 'movies', 'MovieID', [$id])
    || !all_exist($pdo, 'directors', 'DirectorID', [$directorId])) {
    flash_redirect('updateMovie', false, 'movies.php');
}

$stmt = $pdo->prepare(
    "UPDATE movies
     SET Title = :title, ProduceYear = :year, UnitPrice = :price, Quantity = :quantity, DirectorID = :director
     WHERE MovieID = :id"
);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':year', $year, PDO::PARAM_INT);
$stmt->bindValue(':price', $price);
$stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
$stmt->bindValue(':director', $directorId, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

flash_redirect('updateMovie', true, 'movies.php');
