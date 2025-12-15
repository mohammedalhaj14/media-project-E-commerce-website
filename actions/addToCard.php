<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("location:../login.php");
    die();
}

// Validate `movieID` input
if (isset($_POST["movieID"]) && !empty(trim($_POST["movieID"])) && is_numeric($_POST["movieID"])) {
    $movieID = trim($_POST["movieID"]);
    require_once("../connection.php");

    // Validate if the movie exists
    $sql = "SELECT MovieID FROM movies WHERE MovieID = :movieID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":movieID", $movieID, PDO::PARAM_INT);
    $stmt->execute();
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$movie) {
        die("Error: Movie does not exist.");
    }

    // Check if the user has an open cart
    $sql = "SELECT * FROM sales WHERE ClientID = :userID AND Opened = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":userID", $_SESSION["user"], PDO::PARAM_INT);
    $stmt->execute();
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sale) {
        // If no open cart exists, create a new one
        $sql = "INSERT INTO sales (ClientID, saleDate, Opened) VALUES (:userID, CURRENT_TIMESTAMP, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userID", $_SESSION["user"], PDO::PARAM_INT);
        $stmt->execute();
        $saleID = $pdo->lastInsertId();
    } else {
        // Use the existing open cart
        $saleID = $sale["SaleID"];
    }

    // Check if the movie is already in the cart
    $sql = "SELECT * FROM saledetail WHERE SaleID = :saleID AND MovieID = :movieID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":saleID", $saleID, PDO::PARAM_INT);
    $stmt->bindParam(":movieID", $movieID, PDO::PARAM_INT);
    $stmt->execute();
    $saleDetail = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$saleDetail) {
        // Add the movie to the cart
        $sql = "INSERT INTO saledetail (SaleID, MovieID, Qty) VALUES (:saleID, :movieID, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":saleID", $saleID, PDO::PARAM_INT);
        $stmt->bindParam(":movieID", $movieID, PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION["msg"] = "Movie added to your cart.";
    } else {
        // Increase the quantity of the movie in the cart
        $sql = "UPDATE saledetail SET Qty = Qty + 1 WHERE SaleID = :saleID AND MovieID = :movieID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":saleID", $saleID, PDO::PARAM_INT);
        $stmt->bindParam(":movieID", $movieID, PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION["msg"] = "Movie quantity updated in your cart.";
    }

    // Redirect to the main page with a success message
    header("location:../index.php");
    die();
} else {
    die("Error: Invalid parameters.");
}
?>
