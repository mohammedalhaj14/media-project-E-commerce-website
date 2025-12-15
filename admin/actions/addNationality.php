<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["nationality"]) && !empty(trim($_POST["nationality"]))){
    $nationality = trim($_POST["nationality"]);
    require_once("../../connection.php");
    $sql = "INSERT INTO nationalities(NationalityName) VALUES(:gender)" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":gender" , $nationality) ; 
    $stmt->execute();
    $_SESSION["addNationality"] = true ;
    header("location:../nationality.php");
}else{
    $_SESSION["addNationality"] = false ;
    header("location:../nationality.php");
}