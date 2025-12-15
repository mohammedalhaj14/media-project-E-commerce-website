<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["gender"]) && !empty(trim($_POST["gender"]))){
    $gender = trim($_POST["gender"]);
    require_once("../../connection.php");
    $sql = "INSERT INTO genders(GenderName) VALUES(:gender)" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":gender" , $gender) ; 
    $stmt->execute();
    $_SESSION["addGender"] = true ;
    header("location:../gender.php");
}else{
    $_SESSION["addGender"] = false ;
    header("location:../gender.php");
}