<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"])){
    $id = trim($_POST["id"]);
    require_once("../../connection.php");
    $sql = "SELECT MovieID FROM `movies`" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $moviesID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $moviesArray = [] ;
    foreach($moviesID as $movieID){
        $moviesArray[] = $movieID["MovieID"];
    }
    if(!in_array($id , $moviesArray)){
        die('error');
    }

    $sql = "DELETE FROM movies WHERE MovieID=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["deleteMovie"] = true ;
    header("location:../movies.php");
}else{
    $_SESSION["deleteMovie"] = false ;
    header("location:../movies.php");
}