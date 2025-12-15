<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if( isset($_POST["title"]) && isset($_POST["year"]) && isset($_POST["price"]) 
&& isset($_POST["quantite"]) && isset($_POST["director"]) && isset($_POST["actors"])
&& isset($_POST["categories"]) && !empty(trim($_POST["title"])) && !empty(trim($_POST["year"])) 
&& !empty(trim($_POST["price"])) && !empty(trim($_POST["quantite"])) && !empty(trim($_POST["director"])) 
&& !empty($_POST["actors"]) && !empty($_POST["categories"]) && !empty($_FILES["file"]["name"]) && is_numeric($_POST["quantite"]) 
&& is_numeric($_POST["price"]) && is_numeric($_POST["director"])){

    // Variables
    $title = trim($_POST["title"]);
    $year = trim($_POST["year"]);
    $price = trim($_POST["price"]);
    $quantite = trim($_POST["quantite"]);
    $directorID = trim($_POST["director"]);
    $ActorsID = $_POST["actors"];  
    $categoriesID = $_POST["categories"];

    // files
    // print_r ($_FILES["file"]); echo "<br>";
    $ext = pathinfo($_FILES["file"]["name"] , PATHINFO_EXTENSION);
    // echo $ext . "<br>" ;
    if($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif'){
        die("this file is not a image");
    } 
    if(getimagesize($_FILES["file"]["tmp_name"]) == false){
        die("this file is not a image");
    }
    $target_file = "uploads/IMG_" . bin2hex(random_bytes(10)) . "." .$ext ;
    if(file_exists($target_file)){
        die("this file is already exists");
    }
    if($_FILES["file"]["size"] > 1000000){
        die("this file is too large");
    }
    if(move_uploaded_file($_FILES["file"]["tmp_name"] , "../../".$target_file)){
        
    }else{
        die("error");
    }

    // Validation director ID
    require_once("../../connection.php");
    $sql = "SELECT DirectorID FROM `directors`;" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $directorArray = [] ;
    foreach($directors as $director){
        $directorArray[] = $director["DirectorID"] ;
    }
    if(!in_array($directorID , $directorArray)){
        die("error 1");
    }

    // Validation Actors ID
    $sql = "SELECT ActorID FROM `actors` " ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $actorsArray = [];
    foreach($actors as $actor){
        $actorsArray[] = $actor["ActorID"];
    }
    for($i = 0 ; $i < count($ActorsID) ; $i++){
        if(!in_array($ActorsID[$i] , $actorsArray)){
            die("error 2");
        }
    }

    // Validation Categories ID
    $sql = "SELECT CategoryID FROM `categories`" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $categoriesArray = [];
    foreach($categories as $categorie){
        $categoriesArray[] = $categorie["CategoryID"];
    }
    for($i = 0 ; $i < count($categoriesID) ; $i++){
        if(!in_array($categoriesID[$i] , $categoriesArray)){
            die("error 3");
        }
    }

    $sql = "INSERT INTO `movies` (`Title`, `ProduceYear`, `UnitPrice`, `Quantity`, `DirectorID` , `images`) VALUES (:title, :year,:price, :quantite, :director , :image);" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":title" , $title);
    $stmt->bindParam(":year" , $year);
    $stmt->bindParam(":price" , $price);
    $stmt->bindParam(":quantite" , $quantite);
    $stmt->bindParam(":director" , $directorID);
    $stmt->bindParam(":image" , $target_file);
    $stmt->execute();

    $sql = "SELECT MAX(MovieID) as max FROM movies" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movieMaxId = $stmt->fetch(PDO::FETCH_ASSOC);
    $movieMaxId = $movieMaxId["max"];

    for($i = 0 ; $i < count($ActorsID) ; $i++){
        $sql = "INSERT INTO `movieactors` (`MovieID`, `ActorID`) VALUES (:movieID,:actorID );";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":movieID" , $movieMaxId);
        $stmt->bindParam(":actorID" , $ActorsID[$i]);
        $stmt->execute();
    }

    for($i = 0 ; $i < count($categoriesID) ; $i++){
        $sql = "INSERT INTO `moviecategories` (`MovieID`, `CategoryID`) VALUES (:movieID, :categoryID);" ;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":movieID" , $movieMaxId);
        $stmt->bindParam(":categoryID" , $categoriesID[$i]);
        $stmt->execute();
    }

    $_SESSION["addMovie"] = true ;
    header("location:../movies.php");

}else{
    $_SESSION["addMovie"] = false ;
    header("location:../movies.php");
}