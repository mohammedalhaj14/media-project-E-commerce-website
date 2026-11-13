<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id = post_id('id');
if ($id === null) {
    flash_redirect('deleteMovie', false, 'movies.php');
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("DELETE FROM movies WHERE MovieID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

flash_redirect('deleteMovie', $stmt->rowCount() > 0, 'movies.php');
