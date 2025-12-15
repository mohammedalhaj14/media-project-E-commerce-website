<?php
session_start();
if(isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["phone"]) && isset($_POST["address"])
&& isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["gender"]) && isset($_POST["country"])
&& !empty(trim($_POST["fname"])) && !empty(trim($_POST["lname"])) && !empty(trim($_POST["phone"]))
&& !empty(trim($_POST["address"])) && !empty(trim($_POST["username"])) && !empty(trim($_POST["password"]))
&& !empty(trim($_POST["gender"])) && !empty(trim($_POST["country"])) && is_numeric($_POST["phone"])
&& is_numeric($_POST["gender"]) && is_numeric($_POST["country"])){
    // Variables
    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $usernam = trim($_POST["username"]);
    $pass = trim($_POST["password"]);
    $gender = trim($_POST["gender"]);
    $country = trim($_POST["country"]);

    // connection 
    require_once("../connection.php");
    $hach_password = password_hash($pass , PASSWORD_BCRYPT);
    $sql = "INSERT INTO 
    `clients` 
    (`FName`, `LName`, `GenderID`, `Phone`, `Address`, `CountryID`, `Username`, `Password`) 
    VALUES 
    (:fname,:lname,:genderID,:phone,:address,:countryID,:username,:password);";
    $stmt = $pdo->prepare($sql) ;
    $stmt->bindParam(":fname" , $fname);
    $stmt->bindParam(":lname" , $lname);
    $stmt->bindParam(":genderID" , $gender);
    $stmt->bindParam(":phone" , $phone);
    $stmt->bindParam(":address" , $address);
    $stmt->bindParam(":countryID" , $country);
    $stmt->bindParam(":username" , $usernam);
    $stmt->bindParam(":password" , $hach_password);
    $stmt->execute();
    $_SESSION["login"] = true ;
    $_SESSION["user"] = $pdo->lastInsertId();
    header("location:../index.php");   
}else{
    $_SESSION["registration"] = false ;
    header("location:../registration.php"); 
}