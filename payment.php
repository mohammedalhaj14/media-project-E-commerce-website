<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    if(!isset($_SESSION["login"])){
        die();
    }
    if(isset($_POST["saleID"]) && !empty(trim($_POST["saleID"])) && is_numeric($_POST["saleID"])){
        $saleID = trim($_POST["saleID"]);
        require_once("connection.php");
        $sql = "SELECT sales.SaleID,sales.ClientID FROM `sales` WHERE sales.SaleID=:saleID AND sales.ClientID=:clientID AND Opened=1;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":saleID" , $saleID);
        $stmt->bindParam(":clientID" , $_SESSION["user"]);
        $stmt->execute();
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$sale){
            die();
        }
        $sql = "SELECT SUM(saledetail.Qty*movies.UnitPrice) AS total 
                FROM saledetail 
                JOIN movies ON movies.MovieID=saledetail.MovieID 
                JOIN sales ON sales.SaleID=saledetail.SaleID 
                WHERE saledetail.SaleID=:saleID";
        $stmt = $pdo->prepare($sql);
        $stmt ->bindParam(":saleID" , $saleID);
        $stmt->execute();
        $amount = $stmt->fetch(PDO::FETCH_ASSOC);
        $amount = $amount["total"];
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f4f6;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
        }

        #card-element {
            padding: 0.8rem;
            border-radius: 4px;
            border: 1px solid #ccc;
            outline: none;
            margin-bottom: 1.5rem;
        }

        #card-errors {
            color: red;
            margin-top: 1rem;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Details</h2>
        <form id="payment-form" onsubmit="handlePayment(event,<?php echo $amount ?>,<?php echo $saleID; ?>)">
            <div id="card-element"><!-- Stripe Elements will insert the card element here --></div>
            <div id="card-errors" role="alert"></div>
            <button type="submit">Complete Payment</button>
        </form>
    </div>

    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Initialize Stripe with your publishable key
        const stripe = Stripe('pk_test_51QJBGNK9JqRD1EchyUZOHsKpT7khOEjHoHVSsQgKvg38SKQv7srPLkzlahTxfJXdx1obD78UAWZFOlcsNTWPvgxX001bg00DBy'); // Replace with your actual publishable key
        const elements = stripe.elements();

        // Create the card element using Stripe Elements
        const card = elements.create('card');
        card.mount('#card-element');

        // Display errors to the user if they occur
        card.on('change', event => {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        function handlePayment(event,total,saleID) {
            event.preventDefault();
            total =  parseInt(total);
            const amount = total; // Amount in cents (e.g., 10.00 USD = 1000 cents)

            // Request `client_secret` from the server
            fetch('process-payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: amount,  
                    saleID: saleID
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.client_secret) {
                    // Complete the payment using Stripe Elements and `client_secret`
                    stripe.confirmCardPayment(data.client_secret, {
                        payment_method: {
                            card: card
                        }
                    }).then(result => {
                        if (result.error) {
                            // Show error if payment fails
                            alert("Payment failed: " + result.error.message);
                        } else {
                            // Payment succeeded
                            alert("Payment successful!");
                            window.location.href="index.php";
                        }
                    });
                } else {
                    alert("Error retrieving client_secret.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    </script>
</body>
</html>
<?php
    }else{
        die();
    }
    
?>
