<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id = post_id('id');
if ($id === null) {
    flash_redirect('deleteActor', false, 'actors.php');
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("DELETE FROM actors WHERE ActorID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

flash_redirect('deleteActor', $stmt->rowCount() > 0, 'actors.php');
