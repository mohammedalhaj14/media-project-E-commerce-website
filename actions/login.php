<?php
require_once __DIR__ . '/_helpers.php';

$clientUsername = post_text('username', 30);
$clientPassword = post_text('password', 72);

if ($clientUsername === '' || $clientPassword === '') {
    $_SESSION['msg'] = 'Invalid Parameter';
    header('location:../login.php');
    exit;
}

require_once __DIR__ . '/../connection.php';

// Usernames are not unique in the old data, so check every account with that name.
$stmt = $pdo->prepare("SELECT ClientID, Password, type FROM clients WHERE Username = :user");
$stmt->bindValue(':user', $clientUsername);
$stmt->execute();

$matched = null;
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $client) {
    if (password_verify($clientPassword, $client['Password'])) {
        $matched = $client;
        break;
    }
}

if ($matched === null) {
    $_SESSION['msg'] = 'Incorrect Username Or Password';
    header('location:../login.php');
    exit;
}

// New session id after login (prevents session fixation).
session_regenerate_id(true);
unset($_SESSION['admin'], $_SESSION['user']);
$_SESSION['login'] = true;

// The old "remember me" cookie was never checked by the server and could be forged, so remove it.
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 3600, '/');
}

// (int) is required: depending on the PHP version the driver returns "1" (string) or 1 (int).
if ((int) $matched['type'] === 1) {
    $_SESSION['admin'] = true;
    header('location:../admin/adminDachboard.php');
    exit;
}

$_SESSION['user'] = (int) $matched['ClientID'];
header('location:../index.php');
exit;
