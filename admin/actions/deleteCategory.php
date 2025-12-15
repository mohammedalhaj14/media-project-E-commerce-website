<?php
header("Content-Type: application/json");
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"])){
    $id = trim($_POST["id"]);
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
        // die("error");
        echo json_encode(["error" => "error"]);
    }
    $sql = "DELETE FROM categories WHERE CategoryID=:id";
    $stmt = $pdo->prepare($sql) ;
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    // $_SESSION["deleteCategory"] = true ;
    // header("location:../categories.php");
    echo json_encode(["success" => "success Delete"]);
    die();
}else{ 
    // $_SESSION["deleteCategory"] = false ;
    // header("location:../categories.php");
    echo json_encode(["error" => "error"]);
}