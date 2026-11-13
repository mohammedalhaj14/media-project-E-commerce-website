<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$name = post_text('categories', 20);              // categories.CategoryName is varchar(20)
if ($name === '') {
    flash_redirect('addCategorie', false, 'categories.php');
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("INSERT INTO categories (CategoryName) VALUES (:name)");
$stmt->bindValue(':name', $name);
$stmt->execute();

flash_redirect('addCategorie', true, 'categories.php');
