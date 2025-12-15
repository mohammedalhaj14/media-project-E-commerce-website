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
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    if(isset($_POST["id"]) && isset($_POST["categorie"]) && !empty(trim($_POST["id"]))
    && !empty(trim($_POST["categorie"])) && is_numeric($_POST["id"])){
        
        $id = trim($_POST["id"]);
        $categorieName = trim($_POST["categorie"]);
        require_once("../connection.php");
        $sql = "SELECT CategoryID FROM `categories` " ;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categoriesArray = [] ;
        foreach($categories as $categorie){
            $categoriesArray[] = $categorie["CategoryID"] ;
        }
        if(!in_array($id , $categoriesArray)){
            die("error");
        }
     
    
    ?>
    <h1 style="text-align:center">Update category</h1>
    <div style="display:flex;justify-content:center;align-items:center">
        <form action="actions/updateCategorie.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>" id="">
            <div class="form-group">
                <label for="exampleInputEmail1">Update Categorie</label><br>
                <input value="<?php echo $categorieName; ?>" name="categorie"  type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php
            if(isset($_SESSION["addCategorie"])){
                if($_SESSION["addCategorie"] === true){
                    ?>
                    <p style="color:green">Added Successfully</p>
                    <?php
                }else{
                    ?>
                    <p style="color:red">Invalid Input</p>
                    <?php
                }
            }
            unset($_SESSION["addCategorie"]);
            ?>
        </form>
    </div>
    <?php
    }else{
        die("error");
    }
    ?>
</body>
</html>