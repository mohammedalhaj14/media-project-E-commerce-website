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
    <title>Update Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    color: #333;
}

/* Centering content */
h1 {
    text-align: center;
    color: #343a40;
    margin-top: 30px;
}

/* Styling the form container */
form {
    width: 70%;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Form labels */
form label {
    font-weight: bold;
    font-size: 16px;
    color: #555;
}

/* Input fields styling */
form input,
form select {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

form input[type="number"] {
    -moz-appearance: textfield; /* Removes spinner for number inputs */
    appearance: textfield;
}

/* Button styling */
form input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Select styling */
form select {
    padding: 12px;
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    color: #555;
}

/* Additional Styling */
form .mb-3 {
    margin-bottom: 20px;
}

/* Responsive Design for smaller screens */
@media (max-width: 768px) {
    form {
        width: 90%;
    }
}

    </style>
</head>
<body>
    <?php
    if(isset($_POST["id"]) && isset($_POST["title"]) && isset($_POST["year"]) && isset($_POST["price"])
    && isset($_POST["quantite"]) && isset($_POST["directorID"]) && !empty(trim($_POST["id"])) && !empty(trim($_POST["title"]))
    && !empty(trim($_POST["year"])) && !empty(trim($_POST["price"])) && !empty(trim($_POST["quantite"])) &&
    !empty(trim($_POST["directorID"])) && is_numeric($_POST["id"])  && is_numeric($_POST["price"])
    && is_numeric($_POST["year"])  && is_numeric($_POST["quantite"])  && is_numeric($_POST["directorID"])){
        
        $id = trim($_POST["id"]);
        $title = trim($_POST["title"]);
        $year = trim($_POST["year"]);
        $price = trim($_POST["price"]);
        $quantite = trim($_POST["quantite"]);
        $directorID = trim($_POST["directorID"]);

        require_once("../connection.php");
        $sql = "SELECT MovieID FROM movies" ;
        $stmt = $pdo->prepare($sql) ;
        $stmt->execute();
        $moviesID = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $moviesArray = [] ;
        foreach($moviesID as $movieID){
            $moviesArray[] = $movieID["MovieID"];
        }
        if(!in_array($id , $moviesArray)){
            die('error') ; 
        }

        $sql = "SELECT DirectorID FROM directors" ;
        $stmt = $pdo->prepare($sql) ;
        $stmt->execute();
        $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $directorArray = [] ;
        foreach($directors as $director){
            $directorArray[] = $director["DirectorID"];
        }
        if(!in_array($directorID , $directorArray)){
            die('error') ; 
        }
        ?>
        <h1 style="text-align:center">Update Movie</h1>
        <div style="display:flex;justify-content:center;align-items:center;">
            <form action="actions/updateMovie.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Title</label>
                    <input name="title" value="<?php echo $title ?>" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Year</label>
                    <input name="year" value="<?php echo $year ?>" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Price</label>
                    <input name="price" value="<?php echo $price ?>" type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">quantite</label>
                    <input name="quantite" value="<?php echo $quantite ?>" type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <label for="">director</label><br>
                <select name="directorID" class="form-select" aria-label="Default select example">
                    <?php
                    $sql = "SELECT * FROM `directors` " ;
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$directors){
                        ?>
                        <option value="">none</option>
                        <?php
                    }else{
                        foreach($directors as $director){
                            if($director["DirectorID"] == $directorID){ 
                                ?>
                                <option selected value="<?php echo $director['DirectorID'] ?>"><?php echo $director["DirectorName"]; ?></option>
                                <?php
                            }else{
                            ?>
                            <option value="<?php echo $director['DirectorID'] ?>"><?php echo $director["DirectorName"]; ?></option>
                            <?php
                            }
                        }
                    }
                    ?>
                </select><br>
                <input type="submit" >
                <?php
    }
    ?>
</body>
</html>