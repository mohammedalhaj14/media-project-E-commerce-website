<?php
require_once __DIR__ . '/_helpers.php';

// Stop with an error message on the registration page.
function registration_fail(string $message = 'Invalid parameters. Please check your data and try again.'): void
{
    $_SESSION['registration']       = false;
    $_SESSION['registration_error'] = $message;

    // Give the typed values back to the form (the password is never kept).
    $_SESSION['registration_old'] = [];
    foreach (['fname', 'lname', 'phone', 'address', 'username', 'gender', 'country'] as $field) {
        $value = $_POST[$field] ?? '';
        $_SESSION['registration_old'][$field] = is_string($value) ? mb_substr($value, 0, 150) : '';
    }

    header('location:../registration.php');
    exit;
}

$fname    = post_text('fname', 20);      // clients.FName    varchar(20)
$lname    = post_text('lname', 20);      // clients.LName    varchar(20)
$phone    = post_text('phone', 20);      // clients.Phone    varchar(20)
$address  = post_text('address', 150);   // clients.Address  varchar(150)
$clientUsername = post_text('username', 30);   // clients.Username varchar(30)
$clientPassword = post_text('password', 72);   // bcrypt only uses the first 72 bytes
$genderId  = post_id('gender');
$countryId = post_id('country');

if ($fname === '' || $lname === '' || $address === '' || $clientUsername === ''
    || $genderId === null || $countryId === null) {
    registration_fail();
}
if (!preg_match('/^[0-9+\s\-]{5,20}$/', $phone)) {
    registration_fail('Please enter a valid phone number.');
}
if (strlen($clientPassword) < 6 || strlen($clientPassword) > 72) {
    registration_fail('The password must be between 6 and 72 characters.');
}

require_once __DIR__ . '/../connection.php';

// Gender and country must exist.
$stmt = $pdo->prepare("SELECT (SELECT COUNT(*) FROM genders WHERE GenderID = :g) + (SELECT COUNT(*) FROM countries WHERE CountryID = :c)");
$stmt->bindValue(':g', $genderId, PDO::PARAM_INT);
$stmt->bindValue(':c', $countryId, PDO::PARAM_INT);
$stmt->execute();
if ((int) $stmt->fetchColumn() !== 2) {
    registration_fail();
}

// The username must be free.
$stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE Username = :user");
$stmt->bindValue(':user', $clientUsername);
$stmt->execute();
if ((int) $stmt->fetchColumn() > 0) {
    registration_fail('The username "' . $clientUsername . '" is already taken. Please choose another one.');
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO clients (FName, LName, GenderID, Phone, Address, CountryID, Username, Password)
         VALUES (:fname, :lname, :gender, :phone, :address, :country, :username, :password)"
    );
    $stmt->bindValue(':fname', $fname);
    $stmt->bindValue(':lname', $lname);
    $stmt->bindValue(':gender', $genderId, PDO::PARAM_INT);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':address', $address);
    $stmt->bindValue(':country', $countryId, PDO::PARAM_INT);
    $stmt->bindValue(':username', $clientUsername);
    $stmt->bindValue(':password', password_hash($clientPassword, PASSWORD_BCRYPT));
    $stmt->execute();
} catch (PDOException $e) {
    registration_fail();
}

// Log the new customer in with a fresh session id.
session_regenerate_id(true);
$_SESSION['login'] = true;
$_SESSION['user']  = (int) $pdo->lastInsertId();

header('location:../index.php');
exit;
