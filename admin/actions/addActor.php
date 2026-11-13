<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin();

$name          = post_text('actor', 30);          // actors.ActorName is varchar(30)
$genderId      = post_id('gender');
$nationalityId = post_id('nationality');

if ($name === '' || $genderId === null || $nationalityId === null) {
    flash_redirect('addActor', false, 'actors.php');
}

require_once __DIR__ . '/../../connection.php';

if (!all_exist($pdo, 'genders', 'GenderID', [$genderId])
    || !all_exist($pdo, 'nationalities', 'NationalityID', [$nationalityId])) {
    flash_redirect('addActor', false, 'actors.php');
}

$stmt = $pdo->prepare(
    "INSERT INTO actors (ActorName, GenderID, NationalityID) VALUES (:name, :gender, :nationality)"
);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':gender', $genderId, PDO::PARAM_INT);
$stmt->bindValue(':nationality', $nationalityId, PDO::PARAM_INT);
$stmt->execute();

flash_redirect('addActor', true, 'actors.php');
