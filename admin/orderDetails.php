<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    if(!isset($_SESSION["admin"])){
        header("location:../index.php");
        die();
    }
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    if(!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])){
        die("error");
    }
    require_once("../connection.php");
    $id = $_GET["id"] ;
    $sql = "SELECT movies.Title 
            FROM saledetail 
            JOIN movies ON movies.MovieID=saledetail.MovieID 
            WHERE saledetail.SaleID=:id" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id" , $id);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$movies){
        die("error");
    }
    ?>
    <h1 style="text-align:center">Movies</h1>
    <table class="table table-striped">
        <tr>
            <th>Movies</th>
        </tr>
        <?php
        foreach($movies as $movie){
            ?>
            <tr>
                <td><?php echo $movie["Title"] ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</body>
</html>