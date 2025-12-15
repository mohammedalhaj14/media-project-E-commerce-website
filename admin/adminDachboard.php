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
    <title>
        Admin Dachboard
    </title>
    <style>
        /* Reset and base styles */
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f4f4f9;
    color: #333;
}

h1 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #444;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    min-height: 100vh;
    padding: 60px 20px;
    text-align: center;
}

.links {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}

.links a {
    display: inline-block;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.links a:hover {
    background-color: #0056b3;
    color: #fff;
    transform: translateY(-2px);
}

.links a:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Responsive design */
@media (max-width: 768px) {
    h1 {
        font-size: 1.5rem;
    }

    .links a {
        padding: 10px;
        font-size: 0.9rem;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dachboard</h1>
        <div class="links">
            <a href="clients.php">clients</a>
            <a href="categories.php">Categories</a>
            <a href="actors.php">Actors</a>
            <a href="directors.php">Directors</a>
            <a href="movies.php">Movies</a>
            <a href="gender.php">Genders</a>
            <a href="nationality.php">Nationalities</a>
        </div>
    </div>
</body>
</html>