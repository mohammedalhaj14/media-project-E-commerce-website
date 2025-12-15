<?php
session_start();
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QJBGNK9JqRD1EchtmBO2ZRnfGcfK5RgGlNLwlR9ByElxLUeOIaioV5KzqYmFsWHE9DZMvfHWRX1MfgDUa37zzGc00QMl3Rqs6'); // المفتاح السري

function createPaymentIntent($amount, $currency = 'usd') {
    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount, // المبلغ بوحدات السنت
            'currency' => $currency,
            'payment_method_types' => ['card'],
        ]);
        
        return $paymentIntent->client_secret;
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    
    $amountt = $data['amount'] * 100;
    $saleID = $data['saleID'];
    require_once("connection.php");

    $sql = "SELECT sales.SaleID, sales.ClientID FROM `sales` WHERE sales.SaleID = :saleID AND sales.ClientID = :clientID AND Opened = 1;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":saleID", $saleID);
    $stmt->bindParam(":clientID", $_SESSION["user"]);
    $stmt->execute();
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sale) {
        die("Sale not found or unauthorized access.");
    }

    $sql = 'UPDATE sales set sales.Opened = 0 WHERE sales.SaleID=:saleID';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":saleID" , $saleID);
    $stmt->execute();
    $sql = "SELECT movieID,Qty FROM saledetail WHERE SaleID=:saleID" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":saleID" , $saleID);
    $stmt->execute();
    $moviesID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0 ; $i < count($moviesID) ; $i++){
        $sql = "UPDATE movies 
                SET movies.Quantity = movies.Quantity-".$moviesID[$i]["Qty"]."
                WHERE movies.MovieID=:movieID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":movieID" , $moviesID[$i]["movieID"]);
        $stmt->execute();        
    }
    $clientSecret = createPaymentIntent($amountt);

    echo json_encode(['client_secret' => $clientSecret]);
}
?>
