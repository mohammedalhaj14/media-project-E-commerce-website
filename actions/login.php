<?php
session_start();
if(isset($_POST["username"]) && isset($_POST["password"]) && !empty(trim($_POST["username"]))
&& !empty(trim($_POST["password"]))){
    // Variables
    $user = trim($_POST["username"]);
    $pass = trim($_POST["password"]);
    require_once("../connection.php");

    $sql = "SELECT * FROM clients WHERE Username=:user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":user" , $user);
    // $stmt->bindParam(":pass" , $pass);
    $stmt->execute();
    $user1 = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user1){
        $_SESSION['msg'] = "Incorrect Username Or Password";
        header("location:../login.php");
        die();
    }else if(password_verify($pass , $user1["Password"])){
        $_SESSION["login"] = true ;
        if($user1["type"] === 1){
            $_SESSION["admin"] = true ;
            header("location:../admin/adminDachboard.php");
            die();
        }else{
            $_SESSION["user"] = $user1["ClientID"];
            if(isset($_POST["keep-me"])){
                setcookie("user" , $user1["ClientID"] , time() + (30 * 24 * 60 * 60) , "/");
            }
            header("location:../index.php");
            die();
        }
    }else{
        $_SESSION['msg'] = "Incorrect Username Or Password";
        header("location:../login.php");
        die();
    }
}else{
    $_SESSION['msg'] = "Invalid Parameter";
    header("location:../login.php");
}