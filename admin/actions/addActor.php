<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
if(isset($_POST["actor"]) && isset($_POST["gender"]) && isset($_POST["nationality"])
&& !empty(trim($_POST["actor"])) && !empty(trim($_POST["gender"])) && !empty(trim($_POST["nationality"]))
&& is_numeric($_POST["gender"]) && is_numeric($_POST["nationality"])
&& $_POST["gender"] > 0 && $_POST["nationality"] > 0){

    $actor = trim($_POST["actor"]);
    $genderID = trim($_POST["gender"]);
    $nationalityID = trim($_POST["nationality"]);

    // validation Gender Id
    require_once("../../connection.php");
    $sql = "SELECT GenderID FROM genders;" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $gneders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $genderArray = [] ;
    foreach($gneders as $gneder){
        $genderArray[] = $gneder["GenderID"];
    }
    if(!in_array($genderID , $genderArray)){
        die("error");
    }

      // validation Nationality Id
      require_once("../../connection.php");
      $sql = "SELECT NationalityID FROM nationalities;" ;
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $nationalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $nationalitieArray = [] ;
      foreach($nationalities as $nationalitie){
          $nationalitieArray[] = $nationalitie["NationalityID"];
      }
      if(!in_array($nationalityID , $nationalitieArray)){
          die("error 2");
      }

    //Insert Into actor
    $sql = "INSERT INTO `actors` (`ActorName`, `GenderID`, `NationalityID`) VALUES (:actor, :gender , :nationality);" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":actor" , $actor);
    $stmt->bindParam(":gender" , $genderID);
    $stmt->bindParam(":nationality" , $nationalityID);
    $stmt->execute();
    $_SESSION["addActor"] = true ;
    header("location:../actors.php");

}else{
    $_SESSION["addActor"] = false ;
    header("location:../actors.php");
}