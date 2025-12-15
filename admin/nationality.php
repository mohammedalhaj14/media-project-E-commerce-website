<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}

// Include your database connection
require_once("../connection.php");

// Records per page
$records_per_page = 5; // Set how many records per page you want to display

// Get the current page number from the URL (default to 1 if not set)
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting record based on the current page
$start_from = ($page - 1) * $records_per_page;

// Get total number of records for pagination calculation
$sql_total = "SELECT COUNT(*) FROM `nationalities`";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute();
$total_records = $stmt_total->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Fetch records for the current page
$sql = "SELECT * FROM `nationalities` LIMIT :start_from, :records_per_page";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$nationalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nationalities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <h1 style="text-align:center">Nationalities</h1>
    <?php
    if(!$nationalities || count($nationalities) == 0){
        ?>
        <h3 style="color:red;text-align:center">No Nationalities Found</h3>
        <?php
    } else {
        ?>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Nationalities</th>
                <th>Delete</th>
            </tr>
            <?php
            foreach($nationalities as $nationalitie){
                ?>
                <tr>
                    <td><?php echo $nationalitie["NationalityID"] ?></td>
                    <td><?php echo $nationalitie["NationalityName"] ?></td>
                    <td>
                        <form action="actions/deleteNationality.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $nationalitie["NationalityID"]?>">
                            <input class="btn btn-danger" type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>

        <!-- Pagination links -->
        <div class="pagination" style="text-align: center;">
            <?php
            if($page > 1){
                echo "<a href='?page=" . ($page - 1) . "' class='btn btn-primary'>Previous</a> ";
            }

            for($i = 1; $i <= $total_pages; $i++){
                if($i == $page){
                    echo "<span class='btn btn-secondary'>$i</span> ";
                } else {
                    echo "<a href='?page=$i' class='btn btn-primary'>$i</a> ";
                }
            }

            if($page < $total_pages){
                echo "<a href='?page=" . ($page + 1) . "' class='btn btn-primary'>Next</a>";
            }
            ?>
        </div>
        <?php
    }
    ?>
    
    <h1 style="text-align: center;">Add new Nationalities</h1>
    <div style="display: flex;justify-content:center;align-items:center">
        <form action="actions/addNationality.php" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Nationalities</label>
                <input type="text" name="nationality" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php
            if(isset($_SESSION["addNationality"])){
                if($_SESSION["addNationality"] == true){
                    ?>
                    <p style="color:green">Added Successfully</p>
                    <?php
                }else{
                    ?>
                    <p style="color:red">Invalid Parameter</p>
                    <?php
                }
            }
            unset($_SESSION["addNationality"]);
            if(isset($_SESSION["deleteNationality"])){
            if($_SESSION["deleteNationality"] == true){
                    ?>
                    <p style="color:green">Deleted Successfully</p>
                    <?php
                }else{
                    ?>
                    <p style="color:red">Invalid Parameter</p>
                    <?php
                }
            }
            unset($_SESSION["deleteNationality"]);
            ?>
        </form>
    </div>
</body>
</html>
