<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();
require_once __DIR__ . '/../../connection.php';

/* ---------- 1. validate every field BEFORE touching the disk or the database ---------- */

$title       = post_text('title', 150);
$year        = post_int_range('year', 1888, 2100);
$price       = post_decimal('price');
$quantity    = post_int_range('quantite', 0, 999999);
$directorId  = post_id('director');
$actorIds    = post_id_list('actors');
$categoryIds = post_id_list('categories');

if ($title === '' || $year === null || $price === null || $quantity === null
    || $directorId === null || !$actorIds || !$categoryIds) {
    flash_redirect('addMovie', false, 'movies.php');
}

if (!all_exist($pdo, 'directors', 'DirectorID', [$directorId])
    || !all_exist($pdo, 'actors', 'ActorID', $actorIds)
    || !all_exist($pdo, 'categories', 'CategoryID', $categoryIds)) {
    flash_redirect('addMovie', false, 'movies.php');
}

/* ---------- 2. validate the uploaded image ---------- */

$file = $_FILES['file'] ?? null;
if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] <= 0 || $file['size'] > 2000000) {
    flash_redirect('addMovie', false, 'movies.php');
}

// The extension comes from the real image type, not from the file name sent by the browser.
$allowedTypes = [
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG  => 'png',
    IMAGETYPE_GIF  => 'gif',
    IMAGETYPE_WEBP => 'webp',
];
$info = @getimagesize($file['tmp_name']);
if ($info === false || !isset($allowedTypes[$info[2]])) {
    flash_redirect('addMovie', false, 'movies.php');
}

$target   = 'uploads/IMG_' . bin2hex(random_bytes(10)) . '.' . $allowedTypes[$info[2]];
$absolute = __DIR__ . '/../../' . $target;

if (!move_uploaded_file($file['tmp_name'], $absolute)) {
    flash_redirect('addMovie', false, 'movies.php');
}

/* ---------- 3. save everything in one transaction ---------- */

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO movies (Title, ProduceYear, UnitPrice, Quantity, DirectorID, images)
         VALUES (:title, :year, :price, :quantity, :director, :image)"
    );
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindValue(':director', $directorId, PDO::PARAM_INT);
    $stmt->bindValue(':image', $target);
    $stmt->execute();

    $movieId = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO movieactors (MovieID, ActorID) VALUES (:movie, :actor)");
    foreach ($actorIds as $actorId) {
        $stmt->execute([':movie' => $movieId, ':actor' => $actorId]);
    }

    $stmt = $pdo->prepare("INSERT INTO moviecategories (MovieID, CategoryID) VALUES (:movie, :category)");
    foreach ($categoryIds as $categoryId) {
        $stmt->execute([':movie' => $movieId, ':category' => $categoryId]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    @unlink($absolute);          // do not leave an orphan image behind
    flash_redirect('addMovie', false, 'movies.php');
}

flash_redirect('addMovie', true, 'movies.php');
