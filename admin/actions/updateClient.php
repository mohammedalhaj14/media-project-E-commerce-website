<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$id        = post_id('id');
$firstName = post_text('fname', 20);              // clients.FName is varchar(20)
$lastName  = post_text('lname', 20);              // clients.LName is varchar(20)
$phone     = post_text('phone', 20);              // clients.Phone is varchar(20)

// Digits, spaces, "+" and "-" only (the old is_numeric() check rejected numbers like "+961 81 901 814").
if ($id === null || $firstName === '' || $lastName === '' || !preg_match('/^[0-9+\s\-]{5,20}$/', $phone)) {
    flash_redirect('updateClient', false, 'clients.php');
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("UPDATE clients SET FName = :fname, LName = :lname, Phone = :phone WHERE ClientID = :id");
$stmt->bindValue(':fname', $firstName);
$stmt->bindValue(':lname', $lastName);
$stmt->bindValue(':phone', $phone);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$check = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE ClientID = :id");
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->execute();

flash_redirect('updateClient', (int) $check->fetchColumn() > 0, 'clients.php');
