<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$name = post_text('directors', 60);               // directors.DirectorName is varchar(60)
if ($name === '') {
    flash_redirect('addDirectors', false, 'directors.php');
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("INSERT INTO directors (DirectorName) VALUES (:name)");
$stmt->bindValue(':name', $name);
$stmt->execute();

flash_redirect('addDirectors', true, 'directors.php');
