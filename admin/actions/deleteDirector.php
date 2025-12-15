<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"])){
    $id = trim($_POST["id"]);
    require_once("../../connection.php");
    $sql = "SELECT DirectorID FROM `directors`" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $directorsID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $directorsArray = [] ;
    foreach($directorsID as $directorID){
        $directorsArray[] = $directorID["DirectorID"];
    }
    if(!in_array($id , $directorsArray)){
        die('error');
    }

    $sql = "DELETE FROM directors WHERE DirectorID=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["deleteDirector"] = true ;
    header("location:../directors.php");
}else{
    $_SESSION["deleteDirector"] = false ;
    header("location:../directors.php");
}