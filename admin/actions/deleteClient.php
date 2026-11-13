<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id = post_id('id');
if ($id === null) {
    flash_redirect('deleteClient', false, 'clients.php');
}

require_once __DIR__ . '/../../connection.php';

// type = 0 means a normal customer; admin accounts (type = 1) can not be deleted from here.
$stmt = $pdo->prepare("DELETE FROM clients WHERE ClientID = :id AND type = 0");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

flash_redirect('deleteClient', $stmt->rowCount() > 0, 'clients.php');
