<?php
header("Content-Type: application/json");
if(isset($_POST["search"]) && !empty(trim($_POST["search"]))){
    $search = trim($_POST["search"]);
    $search = "%".$search."%";
    require_once("../connection.php");
    $sql = "SELECT Title,MovieID FROM movies WHERE Title LIKE :search" ; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":search" , $search);
    $stmt->execute();
    $searching = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$searching){
        echo json_encode(["empty" => "empty"]);
    }else{
        echo json_encode(["success" => $searching]);
    }    
    }else{
    echo json_encode(["error" => "error"]);
}
