<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"])){
    $id = trim($_POST["id"]);
    require_once("../../connection.php");
    $sql = "SELECT NationalityID FROM `nationalities`" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $nationalitiesID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $nationalitiesArray = [] ;
    foreach($nationalitiesID as $nationalityID){
        $nationalitiesArray[] = $nationalityID["NationalityID"];
    }
    if(!in_array($id , $nationalitiesArray)){
        die('error');
    }

    $sql = "DELETE FROM nationalities WHERE NationalityID=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["deleteNationality"] = true ;
    header("location:../nationality.php");
}else{
    $_SESSION["deleteNationality"] = false ;
    header("location:../nationality.php");
}