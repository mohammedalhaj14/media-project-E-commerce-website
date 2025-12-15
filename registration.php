<!DOCTYPE html>
<?php
session_start(); 
if(isset($_SESSION["login"])){
    header("location:index.php");
    die();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <h1 style="text-align:center">Registration</h1>
    <div style="display:flex;justify-content:center;align-items:center;">
        <form action="actions/registration.php" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">First Name</label><br>
                <input name="fname" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Last Name</label><br>
                <input name="lname" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Phone</label><br>
                <input name="phone" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Address</label><br>
                <input name="address" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Username</label><br>
                <input name="username" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Password</label><br>
                <input name="password" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <label for="">Gender</label>
            <select name="gender" class="form-select" aria-label="Default select example">
                <?php
                require_once("connection.php");
                $sql = "SELECT GenderID,GenderName FROM genders" ;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $genders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$genders){
                    echo "<option value=''>None </option>";
                }else{
                    foreach($genders as $gender){
                        ?>
                        <option value="<?php echo $gender["GenderID"]; ?>"><?php echo $gender["GenderName"]; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <label for="">Country</label>
            <select name="country" class="form-select" aria-label="Default select example">
                <?php
                require_once("connection.php");
                $sql = "SELECT CountryID,CountryName FROM countries" ;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$countries){
                    echo "<option value=''>None </option>";
                }else{
                    foreach($countries as $countrie){
                        ?>
                        <option value="<?php echo $countrie["CountryID"]; ?>"><?php echo $countrie["CountryName"]; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php
            if(isset($_SESSION["registration"])){
                if($_SESSION["registration"] == false){
                    echo "<p style='color:red' > Invalid Parameter </p>";
                }
            }
            unset($_SESSION["registration"]);
            ?>
        </form>
    </div>        
</body>
</html>