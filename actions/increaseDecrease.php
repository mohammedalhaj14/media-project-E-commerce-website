<?php
header("Content-Type: application/json");
if(isset($_POST["qty"]) && !empty(trim($_POST["qty"])) && is_numeric($_POST["qty"])
&& isset($_POST["movieID"]) && !empty(trim($_POST["movieID"])) && is_numeric($_POST["movieID"])
&& isset($_POST["saleID"]) && !empty(trim($_POST["saleID"])) && is_numeric($_POST["saleID"])){
    $qty = trim($_POST["qty"]);
    $movieID = trim($_POST["movieID"]);
    $saleID = trim($_POST["saleID"]);
    require_once("../connection.php");
    $sql = "SELECT MovieID FROM movies" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arrayMovies = [];
    foreach($movies as $movie){
        $arrayMovies[] = $movie["MovieID"];
    }
    if(!in_array($movieID , $arrayMovies)){
        // die("error");
        echo json_encode(["error" => "error"]);
    }
    $sql = "SELECT SaleID FROM sales" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arraysales = [];
    foreach($sales as $sale){
        $arraysales[] = $sale["SaleID"];
    }
    if(!in_array($saleID , $arraysales)){
        // die("error");
        echo json_encode(["error" => "error"]);
    }
    $sql = "SELECT movies.Quantity FROM `movies` WHERE MovieID=:movieID" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":movieID" , $movieID);
    $stmt->execute();
    $movieQuantity = $stmt->fetch(PDO::FETCH_ASSOC);
    $movieQuantity = $movieQuantity["Quantity"];
    if(isset($_POST["increase"])){
        if($qty < $movieQuantity ){    
            $sql = "UPDATE saledetail 
                    SET saledetail.Qty=saledetail.Qty+1 
                    WHERE saledetail.SaleID=:saleID   
                    AND saledetail.MovieID=:movieID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":saleID" , $saleID);        
            $stmt->bindParam(":movieID" , $movieID);
            $stmt->execute();
            // header("location:../card.php");
            echo json_encode(["increase" => "success increase"]);
            die();        
        }else{
            // header("location:../card.php");
            echo json_encode(["error" => "error"]);
            die();
        }
    }else{
        if($qty > 1){
            $sql = "UPDATE saledetail 
            SET saledetail.Qty=saledetail.Qty-1 
            WHERE saledetail.SaleID=:saleID   
            AND saledetail.MovieID=:movieID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":saleID" , $saleID);        
            $stmt->bindParam(":movieID" , $movieID);
            $stmt->execute();
            // header("location:../card.php");
            echo json_encode(["decrease" => "decrease success"]);
            die();   
        }else{
            // header("location:../card.php");
            echo json_encode(["error" => "error"]);
            die();
        }
    }
}else{
    echo json_encode(["errorOne" => "error"]);
}
