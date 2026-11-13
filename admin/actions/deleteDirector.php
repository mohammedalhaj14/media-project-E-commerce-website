<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id = post_id('id');
if ($id === null) {
    flash_redirect('deleteDirector', false, 'directors.php');
}

require_once __DIR__ . '/../../connection.php';

// Note: the database is set to ON DELETE CASCADE, so this also removes the director's movies.
$stmt = $pdo->prepare("DELETE FROM directors WHERE DirectorID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

flash_redirect('deleteDirector', $stmt->rowCount() > 0, 'directors.php');
