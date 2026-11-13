<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id   = post_id('id');
$name = post_text('categorie', 20);               // categories.CategoryName is varchar(20)

if ($id === null || $name === '') {
    flash_redirect('updateCategorie', false, 'categories.php');
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("UPDATE categories SET CategoryName = :name WHERE CategoryID = :id");
$stmt->bindValue(':name', $name);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// rowCount() is 0 when the name did not change, so check that the category exists instead.
$check = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE CategoryID = :id");
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->execute();

flash_redirect('updateCategorie', (int) $check->fetchColumn() > 0, 'categories.php');
