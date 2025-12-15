<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location:../index.php");
    die();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* Add your custom CSS styles here */

body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
}

h1 {
    color: #343a40;
    margin-bottom: 20px;
}

.table {
    margin-top: 20px;
}

.table th, .table td {
    text-align: center;
}

table th {
    background-color: #007bff;
    color: white;
}

table td {
    background-color: #f1f1f1;
}

form {
    width: 80%;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

form label {
    font-weight: bold;
}

form input, form select, form button {
    width: 100%;
    margin-bottom: 15px;
}

form button {
    width: auto;
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

form button:hover {
    background-color: #218838;
}

button[type="button"] {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button[type="button"]:hover {
    background-color: #0056b3;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination .page-item .page-link {
    padding: 8px 16px;
    margin: 0 5px;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
}

    </style>
</head>
<body>
    <h1 style="text-align:center">Movies</h1>
    <?php
    require_once("../connection.php");
    $sql = "SELECT * 
            FROM movies 
            JOIN directors ON directors.DirectorID=movies.DirectorID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$movies){
        echo "<h3 style='text-align:center;color:red'>Empty</h3>";
    } else {
        echo "<table class='table table-striped'>
                <tr>
                    <th>ID</th>
                    <th>Movies</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Director Name</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>";
        foreach($movies as $movie){
            echo "<tr>
                    <td><a href='movieDetail.php?id=" . $movie["MovieID"] . "'>" . $movie["MovieID"] . "</a></td>
                    <td>" . $movie["Title"] . "</td>
                    <td>" . $movie["ProduceYear"] . "</td>
                    <td>" . $movie["UnitPrice"] . "</td>
                    <td>" . $movie["Quantity"] . "</td>
                    <td>" . $movie["DirectorName"] . "</td>
                    <td> <form action='actions/deleteMovie.php' method='POST'>
                            <input type='hidden' name='id' value='". $movie["MovieID"] . "'>
                            <input type='submit' class='btn btn-danger' value='Delete'>
                        </form>
                    </td>
                    <td> <form action='updateMovie.php' method='POST'>
                            <input type='hidden' name='id' value='". $movie["MovieID"] . "'>
                            <input type='hidden' name='title' value='". $movie["Title"] . "'>
                            <input type='hidden' name='year' value='". $movie["ProduceYear"] . "'>
                            <input type='hidden' name='price' value='". $movie["UnitPrice"] . "'>
                            <input type='hidden' name='quantite' value='". $movie["Quantity"] . "'>
                            <input type='hidden' name='directorID' value='". $movie["DirectorID"] . "'>
                            <input type='hidden' name='directorName' value='". $movie["DirectorName"] . "'>
                            <input type='submit' class='btn btn-success' value='update'>
                        </form>
                    </td>        
                </tr>";
        }
        echo "</table>";
    }
    if(isset($_SESSION["deleteMovie"])){
        if($_SESSION["deleteMovie"] == true){
            ?>
            <p style="color:green">Delete Successfuly</p>
            <?php
        }else{
            ?>
            <p style="color:red">Invalid Parameter</p>
            <?php
        }
    }
    if(isset($_SESSION["updateMovie"])){
        if($_SESSION["updateMovie"] == true){
            ?>
            <p style="color:green">Update Successfuly</p>
            <?php
        }else{
            ?>
            <p style="color:red">Invalid Parameter</p>
            <?php
        }
    }
    unset($_SESSION["deleteMovie"]);
    unset($_SESSION["updateMovie"]);
    ?>
    <h1 style="text-align:center">Add New Movies</h1>
    <div style="display:flex;justify-content:center;align-items:center;">
        <form action="actions/addMovie.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Year</label>
                <input name="year" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Price</label>
                <input name="price" type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">quantite</label>
                <input name="quantite" type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <label for="">director</label><br>
            <select name="director" class="form-select" aria-label="Default select example">
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
                        ?>
                        <option value="<?php echo $director['DirectorID'] ?>"><?php echo $director["DirectorName"]; ?></option>
                        <?php
                    }
                }
                ?>
            </select><br> 
            <label for="">Actors</label>
            <select  id="select1" name="actors[]" class="form-select" aria-label="Default select example">
                <?php
                $sql = "SELECT ActorID,ActorName FROM `actors`" ;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$actors){
                    ?>
                    <option value="">none</option>
                    <?php
                }else{
                    foreach($actors as $actor){
                        ?>
                        <option value="<?php echo $actor['ActorID']; ?>"><?php echo $actor["ActorName"]; ?></option>
                        <?php
                    }
                }
                ?>
            </select> <br>
            <button type="button" onclick="copySelect('select1')">Add Actor</button><br>
            <div id="container1"></div>  
            
            <label for="">Categories</label>
            <select  id="select2" name="categories[]" class="form-select" aria-label="Default select example">
                <?php
                $sql = "SELECT CategoryID,CategoryName FROM `categories`" ;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$categories){
                    ?>
                    <option value="">none</option>
                    <?php
                }else{
                    foreach($categories as $categorie){
                        ?>
                        <option value="<?php echo $categorie['CategoryID']; ?>"><?php echo $categorie["CategoryName"]; ?></option>
                        <?php
                    }
                }
                ?>
            </select> <br>
            <button type="button" onclick="copySelect('select2')">Add Category</button>
            <div id="container2"></div><br>
            <input type="file" name="file"><br><br>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php
            if(isset($_SESSION["addMovie"])){
                if($_SESSION["addMovie"] == true){
                    ?>
                    <p style="color:green">Added Successfuly</p>
                    <?php
                }else{
                    ?>
                    <p style="color:red">Invalid Parameter</p>
                    <?php
                }
            }
            unset($_SESSION["addMovie"]);
            ?>
        </form>
    </div>
    <script>
        function copySelect(selectId) {
            // Get the original select element
            const selectElement = document.getElementById(selectId);
            // Create a clone of the select element
            const newSelect = selectElement.cloneNode(true);
            // Append the cloned select to the appropriate container
            const containerId = selectId === 'select1' ? 'container1' : 'container2';
            document.getElementById(containerId).appendChild(newSelect);
        }
    </script>
</body>
</html>
