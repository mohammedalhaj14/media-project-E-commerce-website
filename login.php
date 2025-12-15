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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<h1 style="text-align:center">Login</h1>
    <div style="display:flex;justify-content:center;align-items:center;">
        <form action="actions/login.php" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">Username</label><br>
                <input name="username" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Password</label><br>
                <input name="password" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter New Categories"><br>
            </div>
            <input type="checkbox" name="keep-me" id=""><label for="">Keep me Login</label>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php
            if(isset($_SESSION['msg'])){
                echo '<p style:"color:red;">' . $_SESSION['msg'] . '</p>';
            }
            unset($_SESSION["msg"]);
            ?>
        </form>
    </div>    
</body>
</html>