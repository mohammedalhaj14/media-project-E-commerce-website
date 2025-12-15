<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"])){
    $id = trim($_POST["id"]);
    require_once("../../connection.php");
    $sql = "SELECT ClientID FROM `clients`" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientsID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $clientsArray = [] ;
    foreach($clientsID as $clientID){
        $clientsArray[] = $clientID["ClientID"];
    }
    if(!in_array($id , $clientsArray)){
        die('error');
    }

    $sql = "DELETE FROM clients WHERE ClientID=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["deleteClient"] = true ;
    header("location:../clients.php");
}else{
    $_SESSION["deleteClient"] = false ;
    header("location:../clients.php");
}