<?php
if (isset($_POST["delete"]) && $_POST["delete"] === "true") {
    if (isset($_POST["saleID"], $_POST["movieID"], $_POST["qty"])) {
        $saleID = $_POST["saleID"];
        $movieID = $_POST["movieID"];
        $qty = $_POST["qty"];
        require_once("../connection.php");

        // Proceed with deletion logic in the database
        $deleteSql = "DELETE FROM saledetail WHERE SaleID = :saleID AND MovieID = :movieID";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(":saleID", $saleID);
        $deleteStmt->bindParam(":movieID", $movieID);
        $deleteStmt->execute();

        // Check if the deletion was successful
        if ($deleteStmt->rowCount() > 0) {
            echo json_encode(["delete" => "success", "message" => "Item deleted successfully."]);
        } else {
            echo json_encode(["error" => "Item not found or couldn't be deleted."]);
        }
    } else {
        echo json_encode(["error" => "Missing parameters."]);
    }
}
