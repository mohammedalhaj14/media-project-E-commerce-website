<!DOCTYPE html>
<html lang="en">
<?php
session_start();
?>    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Home</title>
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

/* Navbar Styles */
nav {
    width: 100%;
    height: 70px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ffcc00;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 10;
}

nav a {
    color: black;
    text-decoration: none;
    padding: 10px 20px;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav a:hover {
    color: white;
}

/* Footer Styles */
footer {
    width: 100%;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: gold;
    box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
}


/* Content Styles */
.cont {
    display: flex;
    justify-content: center;
    padding: 20px;
    gap: 20px;
}

/* Categories Section */
.cont .categories {
    display: flex;
    flex-direction: column;
    width: 25%;
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.cont .categories a {
    color: black;
    text-decoration: none;
    margin-top: 7px;
    font-size: 18px;
    transition: color 0.3s ease;
}

.cont .categories a:hover {
    color: #ffcc00;
}

/* Movies Section */
.cont .movies {
    width: 70%;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.cont .movies a img {
    width: 100%;
    max-width: 200px;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cont .movies a img:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

/* Search Bar */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}

.search-input {
    width: 300px;
    padding: 10px 15px;
    font-size: 16px;
    border: 2px solid #ddd;
    border-radius: 25px;
    outline: none;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
}

.search-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 0 25px 25px 0;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    margin-left: -5px;
}

.search-btn:hover {
    background-color: #0056b3;
}

/* Search Results */
.searching {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.searching a {
    color: #007bff;
    font-size: 16px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.searching a:hover {
    color: #0056b3;
}

/* Cart Icon */
.cardd {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    fill: black;
    transition: fill 0.3s ease;
}

.cardd:hover {
    fill: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cont {
        flex-direction: column;
        gap: 10px;
    }

    .cont .categories {
        width: 100%;
    }

    .cont .movies {
        width: 100%;
    }

    .search-container {
        flex-direction: column;
        gap: 10px;
    }

    .search-input {
        width: 90%;
    }
}

    </style>
</head>
<body>
    <?php
    if(isset($_SESSION["msg"])){
        ?>
        <script>alert("<?php echo $_SESSION["msg"]; ?>")</script>
        <?php
    }
    unset($_SESSION["msg"]);
    ?>
    <nav>
       <?php
       if(isset($_SESSION["login"]) || isset($_COOKIE["user"])){
       ?>
       <a href="logout.php">Logout</a>
       <?php
       require_once("connection.php");
       $sql = "SELECT * FROM sales WHERE sales.ClientID=:clientID AND Opened=1 " ;
       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(":clientID" , $_SESSION["user"]);
       $stmt->execute();
       $order = $stmt->fetch(PDO::FETCH_ASSOC);
       if(!$order){
        ?>
        <svg class="cardd" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-dash-fill" viewBox="0 0 16 16">
            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M6.5 7h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1"/>
        </svg>
        <?php
       }else{ 
       ?>
       <a class="cardd" href="card.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-dash-fill" viewBox="0 0 16 16">
                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M6.5 7h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1"/>
            </svg></a>
       <?php 
       }
    }else{ 
       ?> 
       <a href="login.php">Login</a>
       <a href="registration.php">Registration</a>
       <?php
       }
       ?>
    </nav>
    <div class="search-container">
        <input onkeyup="search()" autocomplete="off" type="text" class="search-input" placeholder="Search..." id="search">
        <button class="search-btn">Search</button>
    </div>
    <div id="searching" class="searching"></div>
    <div class="cont">
        <div class="categories">
            <?php
            require_once("connection.php");
            $sql = "SELECT * FROM categories" ;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$categories){
                echo '<p> None </p>';
            }else{
                foreach($categories as $categorie){
                    ?>
                    <a href="index.php?categorieID=<?php echo $categorie["CategoryID"]; ?>"><?php echo $categorie["CategoryName"]; ?></a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="movies">
            <?php
            if(!isset($_GET["categorieID"])){
                $sql = "SELECT * FROM movies" ;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$movies){
                    echo '<p> None </p>' ;
                }else{
                    foreach($movies as $movie){
                        ?>
                        <a href="movieDetails.php?movieID=<?php echo $movie["MovieID"]; ?>"><img width="33%" height="200px" src="<?php echo $movie["images"]; ?>" alt=""></a>
                        <?php
                    }
                }
            }else{
                if(!empty(trim($_GET["categorieID"])) && is_numeric($_GET["categorieID"])){
                    $sql = "SELECT * 
                            FROM movies 
                            JOIN moviecategories ON movies.MovieID=moviecategories.MovieID 
                            WHERE moviecategories.CategoryID = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":id" , $_GET["categorieID"]);
                    $stmt->execute();   
                    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$movies){
                        echo '<p> None </p>' ;
                    }else{
                        foreach($movies as $movie){
                            ?>
                            <a href="movieDetails.php?movieID=<?php echo $movie["MovieID"]; ?>"><img width="33%" height="200px" src="<?php echo $movie["images"]; ?>" alt=""></a>
                            <?php
                        }
                    }     
                }
            }
            ?>
        </div>
    </div>
    <footer>
    </footer>
</body>
<script>
    function search(){
        let search = document.getElementById("search").value;
        const myHeaders = new Headers();

        const urlencoded = new URLSearchParams();
        urlencoded.append("search", search);

        const requestOptions = {
        method: "POST",
        body: urlencoded,
        };

        fetch("http://localhost/media/actions/search.php", requestOptions)
        .then((response) => response.json())
        .then((result) => getDataSearch(result))
        .catch((error) => console.error(error));
    }
    function getDataSearch(result){
        let searching = document.getElementById("searching");
        searching.innerHTML = "" ;
        if(result.success){
            for(let i = 0 ; i < result.success.length ; i++){
                let a = document.createElement("a");
                a.href = "movieDetails.php?movieID="+result.success[i].MovieID;
                a.innerHTML = result.success[i].Title ;
                searching.appendChild(a);
            }
        }else{
            console.log("no movies")
        }
    }
</script>
</html>


