<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["id"]) && isset($_POST["title"]) && isset($_POST["year"]) && isset($_POST["price"])
&& isset($_POST["quantite"]) && isset($_POST["directorID"]) && !empty(trim($_POST["id"])) && !empty(trim($_POST["title"]))
&& !empty(trim($_POST["year"])) && !empty(trim($_POST["price"])) && !empty(trim($_POST["quantite"])) &&
!empty(trim($_POST["directorID"])) && is_numeric($_POST["id"])  && is_numeric($_POST["price"])
&& is_numeric($_POST["year"])  && is_numeric($_POST["quantite"])  && is_numeric($_POST["directorID"])){

    $id = trim($_POST["id"]);
    $title = trim($_POST["title"]);
    $year = trim($_POST["year"]);
    $price = trim($_POST["price"]);
    $quantite = trim($_POST["quantite"]);
    $directorID = trim($_POST["directorID"]);

    require_once("../../connection.php"); 

    $sql = "SELECT MovieID FROM movies" ;
    $stmt = $pdo->prepare($sql) ;
    $stmt->execute();
    $moviesID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $moviesArray = [] ;
    foreach($moviesID as $movieID){
        $moviesArray[] = $movieID["MovieID"];
    }
    if(!in_array($id , $moviesArray)){
        die('error') ; 
    }

    $sql = "SELECT DirectorID FROM directors" ;
    $stmt = $pdo->prepare($sql) ;
    $stmt->execute();
    $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $directorArray = [] ;
    foreach($directors as $director){
        $directorArray[] = $director["DirectorID"];
    }
    if(!in_array($directorID , $directorArray)){
        die('error') ; 
    }
    $sql = "UPDATE movies 
            set Title=:title,UnitPrice=:price,ProduceYear=:year,Quantity=:quantite,DirectorID=:directorID 
            WHERE MovieID=:id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":title" , $title);
    $stmt->bindParam(":price" , $price);
    $stmt->bindParam(":year" , $year);
    $stmt->bindParam(":quantite" , $quantite);
    $stmt->bindParam(":directorID" , $directorID);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $_SESSION["updateMovie"] = true;
    header("location:../movies.php");

}else{
    $_SESSION["updateMovie"] = false;
    header("location:../movies.php");
}