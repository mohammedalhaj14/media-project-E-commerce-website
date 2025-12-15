<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("location:../index.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
           body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 1.2rem;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php
    if (
isset($_POST["id"]) && !empty(trim($_POST["id"])) && is_numeric($_POST["id"]) &&
    isset($_POST["fname"]) && !empty(trim($_POST["fname"])) &&
    isset($_POST["lname"]) && !empty(trim($_POST["lname"])) &&
    isset($_POST["phone"]) && !empty(trim($_POST["phone"])) && is_numeric($_POST["phone"])) {
        $id = trim($_POST["id"]);
        $fname = trim($_POST["fname"]);
        $lname = trim($_POST["lname"]);
        $phone = trim($_POST["phone"]);

        require_once("../connection.php");

        // Fetch clients from the database
        $sql = "SELECT * FROM clients WHERE ClientID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$clients) {
            die("<div class='text-center mt-5'><h3 style='color: red;'>Client not found.</h3></div>");
        }

        foreach ($clients as $client) {
            ?>
            <div class="container mt-5">
                <h1 class="text-center">Update Client</h1>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form action="actions/updateClient.php" method="POST">
                            <!-- Hidden field for ClientID -->
                            <input type="hidden" name="id" value="<?php echo $client['ClientID']; ?>">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" name="fname" value="<?php echo $client['FName']; ?>" class="form-control" id="fname">
                            </div>
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" name="lname" value="<?php echo $client['LName']; ?>" class="form-control" id="lname">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" value="<?php echo $client['Phone']; ?>" class="form-control" id="phone" >
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        die("<div class='text-center mt-5'><h3 style='color: red;'>Invalid or missing Client ID.</h3></div>");
    }
    ?>
</body>
</html>
