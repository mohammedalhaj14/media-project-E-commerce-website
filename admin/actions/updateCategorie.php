<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
 if(isset($_POST["id"]) && isset($_POST["categorie"]) && !empty(trim($_POST["id"]))
 && !empty(trim($_POST["categorie"])) && is_numeric($_POST["id"])){
     
    $id = trim($_POST["id"]);
    $categorieName = trim($_POST["categorie"]);
    require_once("../../connection.php");
    $sql = "SELECT CategoryID FROM `categories` " ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $categoriesArray = [] ;
    foreach($categories as $categorie){
        $categoriesArray[] = $categorie["CategoryID"] ;
    }
    if(!in_array($id , $categoriesArray)){
        die("error");
    }
    $sql = "UPDATE categories SET CategoryName=:categorie WHERE CategoryID=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":categorie" , $categorieName);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["updateCategorie"] = true;
    header("location:../categories.php");
}else{
    $_SESSION["updateCategorie"] = false;
    header("location:../categories.php");
}