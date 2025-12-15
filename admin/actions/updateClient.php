<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("location:../../index.php");
    die();
}

if (
    isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"]) &&
    isset($_POST["fname"]) && !empty(trim($_POST["fname"])) &&
    isset($_POST["lname"]) && !empty(trim($_POST["lname"])) &&
    isset($_POST["phone"]) && !empty(trim($_POST["phone"])) && is_numeric($_POST["phone"])
) {
    $id = trim($_POST["id"]);
    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $phone = trim($_POST["phone"]);

    require_once("../../connection.php");

    $sql = "UPDATE clients SET FName = :fname, LName = :lname, Phone = :phone WHERE ClientID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":fname", $fname, PDO::PARAM_STR);
    $stmt->bindParam(":lname", $lname, PDO::PARAM_STR);
    $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION["updateClient"]=true;
    header("location:../clients.php");

} else {
    $_SESSION["updateClient"]=false;
    header("location:../clients.php"); 
}

