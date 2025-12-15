<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"])){
    $id = trim($_POST["id"]);
    require_once("../../connection.php");
    $sql = "SELECT ActorID FROM `movies`" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $actorsID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $actorsArray = [] ;
    foreach($actorsID as $actorID){
        $actorsArray[] = $actorID["ActorID"];
    }
    if(!in_array($id , $actorsArray)){
        die('error');
    }

    $sql = "DELETE FROM actprs WHERE ActorID=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["deleteActor"] = true ;
    header("location:../actors.php");
}else{
    $_SESSION["deleteActor"] = false ;
    header("location:../actors.php");
}