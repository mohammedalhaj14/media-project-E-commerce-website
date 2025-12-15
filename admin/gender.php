<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gender</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <h1 style="text-align:center">Genders</h1>
    <?php
    require_once("../connection.php");
    $sql = "SELECT * FROM `genders`" ;
    $stmt = $pdo->prepare($sql) ;
    $stmt->execute();
    $genders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$genders){
        ?>
        <h3 style="color:red;text-align:center">Empty</h3>
        <?php
    }else{
        ?>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Gender</th>
            </tr>
            <?php
            foreach($genders as $gender){
                ?>
                <tr>
                    <td><?php echo $gender["GenderID"] ?></td>
                    <td><?php echo $gender["GenderName"] ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>
    <h1 style="text-align: center;">Add new Gender</h1>
    <div style="display: flex;justify-content:center;align-items:center">
        <form action="actions/addGender.php" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Gender</label>
                <input type="text" name="gender" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php
            if(isset($_SESSION["addGender"])){
                if($_SESSION["addGender"] == true){
                    ?>
                    <p style="color:green">Added Successfully</p>
                    <?php
                }else{
                    ?>
                    <p style="color:red">Invalid Parameter</p>
                    <?php
                }
            }
            unset($_SESSION["addGender"]);
        ?>
        </form>
    </div>
</body>
</html>