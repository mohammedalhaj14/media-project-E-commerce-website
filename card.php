<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Basket</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px; 
            color: #333;
            overflow-y: auto; 
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 650px; 
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        h3 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }

        h4 {
            font-size: 1.2em;
            margin: 0;
            color: #666;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #e0e0e0;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #333;
            color: #ffffff;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .deleteBtn {
            background: none;
            border: none;
            color: #ff4444;
            font-size: 18px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .deleteBtn:hover {
            color: #ff0000;
        }

        .qty-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qtyBtn {
            background-color: #ddd;
            border: none;
            color: #333;
            padding: 5px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 16px;
            margin: 0 5px;
        }

        .qtyBtn:hover {
            background-color: #ccc;
        }

        .quantity {
            font-weight: bold;
            color: #333;
            text-align: center;
            width: 50px;
            height: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 5px;
            font-size: 16px;
        }


        .buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    require_once("connection.php");
    $sql = "SELECT sales.SaleID,sales.ClientID,sales.saleDate,clients.FName,saledetail.Qty,saledetail.MovieID,movies.Title,movies.ProduceYear,movies.UnitPrice,directors.DirectorName 
            FROM `sales`
            JOIN clients ON clients.ClientID=sales.ClientID
            JOIN saledetail ON saledetail.SaleID=sales.SaleID
            JOIN movies ON saledetail.MovieID=movies.MovieID 
            JOIN directors ON directors.DirectorID=movies.DirectorID
            WHERE sales.ClientID=:clientID AND Opened=1" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":clientID" , $_SESSION["user"]);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
        <h3><?php echo $orders[0]["FName"] ?></h3>
        <h4>Basket #: <?php echo $orders[0]["SaleID"] ?></h4>
        <h4>Creation Date: <?php echo $orders[0]["saleDate"] ?></h4>

        <table>
            <thead>
                <tr>
                    <th>Movie Title</th>
                    <th>Production Year</th>
                    <th>Director Name</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="movieList">
                <?php
                $amount = 0 ;
                $count = 0 ;
                foreach($orders as $order){
                    ?>
                    <tr>
                        <td><?php echo $order["Title"]; ?></td>
                        <td><?php echo $order["ProduceYear"]; ?></td>
                        <td><?php echo $order["DirectorName"]; ?></td>
                        <td meta-price="<?php echo $count; ?>"><?php echo $order["UnitPrice"]; ?></td>
                        <td>
                            <!-- <form action="actions/increaseDecrease.php" method="POST"> -->
                                <!-- <input type="hidden" name="saleID" value="<?php echo $order["SaleID"]; ?>" id=""> -->
                                <!-- <input type="hidden" name="movieID" value="<?php echo $order["MovieID"]; ?>" id=""> -->
                                <div class="qty-container">
                                    <button type="button" onclick="decrease(<?php echo $order['SaleID']; ?> , <?php echo $order['MovieID']; ?>)" class="qtyBtn" >-</button>
                                    <p meta-qty="<?php echo $count; ?>" meta-movie="<?php echo $order["MovieID"]; ?>"><?php echo $order["Qty"]; ?></p>
                                    <button type="button" onclick="increase(<?php echo $order['SaleID']; ?> , <?php echo $order['MovieID']; ?>)" class="qtyBtn" >+</button>
                                </div>
                            <!-- </form> -->
                        </td>
                        <td><button class="deleteBtn" onclick="deleteRow(this,<?php echo $count ?>)">🗑️</button></td>
                    </tr>
                    <?php
                    $amount += $order['Qty']*$order['UnitPrice']; 
                    $count++ ;
                }
                ?>
                <tr>
                    <td id="amount" colspan="5"><?php echo $amount ; ?></td>
                </tr>
            </tbody>
        </table>

        <div class="buttons">
            <form action="payment.php" method="POST">
                <!-- <input type="hidden" name="total" id="total" value="<?php echo $amount; ?>"> -->
                <input type="hidden" name="saleID" value="<?php echo $orders[0]["SaleID"] ?>" id="">
                <button type="submit">Checkout</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById("selectAll");
            const checkboxes = document.querySelectorAll(".movieCheckbox");
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        function deleteSelected() {
            let amountElement = document.getElementById("amount");
            let currentAmount = parseFloat(amountElement.textContent);

            const checkboxes = document.querySelectorAll(".movieCheckbox");

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    let priceElement = checkbox.parentElement.nextElementSibling.nextElementSibling.nextElementSibling;
                    let qtyElement = priceElement.nextElementSibling.querySelector('p');

                    let price = parseFloat(priceElement.textContent);
                    let qty = parseInt(qtyElement.textContent);
                    
                    console.log("Price:", price, "Qty:", qty); // التحقق من القيم

                    currentAmount -= price * qty;
                    
                    checkbox.parentElement.parentElement.remove();
                }
            });

            // تحديث قيمة المبلغ في الصفحة بعد التقريب
            amountElement.textContent = currentAmount.toFixed(2);
        }



        function deleteRow(button, count) {
    // Get the saleID and movieID for the row to be deleted
    let saleID = <?php echo $orders[0]["SaleID"]; ?>; // SaleID should be retrieved dynamically, can be passed directly if known
    let movieID = document.querySelector(`p[meta-qty='${count}']`).getAttribute('meta-movie');

    // Get the amount element to update the total amount after deletion
    let amountElement = document.getElementById("amount");
    let priceElement = document.querySelector(`td[meta-price='${count}']`);
    let qtyElement = document.querySelector(`p[meta-qty='${count}']`);

    // Calculate the new total amount after deletion
    let newAmount = parseFloat(amountElement.textContent) - (parseFloat(priceElement.textContent) * parseInt(qtyElement.textContent));
    amountElement.textContent = newAmount.toFixed(2);

    // Create a URLSearchParams object to send data to the server
    const urlencoded = new URLSearchParams();
    urlencoded.append("delete", true);  // Indicating this is a delete action
    urlencoded.append("saleID", saleID);
    urlencoded.append("movieID", movieID);
    urlencoded.append("qty", qtyElement.textContent);  // Send the current quantity as well

    // Setup the request
    const requestOptions = {
        method: "POST",
        body: urlencoded
    };

    // Perform the fetch request to delete the item from the database
    fetch("http://localhost/media/actions/deleteRow.php", requestOptions)
        .then(response => response.json())
        .then(result => {
            if (result.delete === "success") {
                // If the deletion was successful, remove the row
                button.parentElement.parentElement.remove();
                console.log('Item deleted successfully!');
            } else {
                console.error('Error deleting item:', result.error || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error during fetch operation:', error);
        });
}


        function increaseQty(button) {
            const quantityElement = button.previousElementSibling;
            let quantity = parseInt(quantityElement.value);
            quantityElement.value = ++quantity;
        }

        function decreaseQty(button) {
            const quantityElement = button.nextElementSibling;
            let quantity = parseInt(quantityElement.value);
            if (quantity > 1) {
                quantityElement.value = --quantity;
            }
        }
        function decrease(saleID,movieID){
            console.log(saleID);
            console.log(movieID);
            let qty = document.querySelector(`p[meta-movie='${movieID}']`).textContent;

            const urlencoded = new URLSearchParams();
            urlencoded.append("saleID", saleID);
            urlencoded.append("movieID", movieID);
            urlencoded.append("qty", qty);

            const requestOptions = {
            method: "POST",
            body: urlencoded,
            };

            fetch("http://localhost/media/actions/increaseDecrease.php", requestOptions)
            .then((response) => response.json())
            .then((result) => getDataDecrease(result,movieID))
            .catch((error) => console.error(error));
        }
        function getDataDecrease(result,movieID){
            // console.log(result)
            let amount = document.getElementById("amount");
            amount.innerHTML = 0 ;
            if(result.decrease){
                let qty = document.querySelector(`p[meta-movie='${movieID}']`).textContent;
                qty--;
                console.log(qty);
                // console.log("gkfd")
                document.querySelector(`p[meta-movie='${movieID}']`).innerHTML = qty ;
            }
            for(let i = 0 ; i < <?php echo $count ?> ; i++){
                let price = document.querySelector(`td[meta-price='${i}']`);
                let qty = document.querySelector(`p[meta-qty='${i}']`);
                amount.textContent =parseFloat(amount.textContent) +(parseFloat(price.textContent)*parseFloat(qty.textContent)) ; 
                console.log(amount.textContent);
            }
            let total = document.getElementById("total");
            total.value = amount.textContent;
        }
        function increase(saleID,movieID){
            let qty = document.querySelector(`p[meta-movie='${movieID}']`).textContent;
            let increase = "increase" ;
            const urlencoded = new URLSearchParams();
            urlencoded.append("saleID", saleID);
            urlencoded.append("movieID", movieID);
            urlencoded.append("qty", qty);
            urlencoded.append("increase" , increase);

            const requestOptions = {
            method: "POST",
            body: urlencoded,
            };

            fetch("http://localhost/media/actions/increaseDecrease.php", requestOptions)
            .then((response) => response.json())
            .then((result) => getDataIncrease(result,movieID))
            .catch((error) => console.error(error));
        }
        function getDataIncrease(result,movieID){
            // console.log(result)
            amount.innerHTML = 0 ;
            if(result.increase){
                let qty = document.querySelector(`p[meta-movie='${movieID}']`).textContent;
                qty++;
                console.log(qty);
                // console.log("gkfd")
                document.querySelector(`p[meta-movie='${movieID}']`).innerHTML = qty ;
            }
            for(let i = 0 ; i < <?php echo $count ?> ; i++){
                let price = document.querySelector(`td[meta-price='${i}']`);
                let qty = document.querySelector(`p[meta-qty='${i}']`);
                amount.textContent =parseFloat(amount.textContent) + (parseFloat(price.textContent)*parseFloat(qty.textContent)) ; 
                console.log(amount.textContent);
            }
            let total = document.getElementById("total");
            total.value = amount.textContent;
        }
    </script>
</body>
</html>