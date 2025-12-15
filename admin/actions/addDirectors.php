<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["directors"]) && !empty(trim($_POST["directors"]))){
    $director = trim($_POST["directors"]);
    require_once("../../connection.php");
    $sql = "INSERT INTO directors(DirectorName) VALUES(:director)" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":director" , $director) ; 
    $stmt->execute();
    $_SESSION["addDirectors"] = true ;
    header("location:../directors.php");
}else{
    $_SESSION["addDirectors"] = false ;
    header("location:../directors.php");
}