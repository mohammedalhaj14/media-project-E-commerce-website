<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["categories"]) && !empty(trim($_POST["categories"]))){
    $categorie = trim($_POST["categories"]);
    require_once("../../connection.php");
    $sql = "INSERT INTO categories(CategoryName) VALUES(:categorie);" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":categorie" , $categorie);
    $stmt->execute();
    $_SESSION["addCategorie"] = true ;
    header("location:../categories.php");
}else{
    $_SESSION["addCategorie"] = false ;
    header("location:../categories.php");
}