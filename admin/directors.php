<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("location:../index.php");
    die();
}

require_once("../connection.php");

// Pagination setup
$limit = 5; // Number of items per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch directors with search and pagination
$sql = "SELECT * FROM directors 
        WHERE DirectorName LIKE :search 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$directors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total directors for pagination
$sqlTotal = "SELECT COUNT(*) as total FROM directors WHERE DirectorName LIKE :search";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmtTotal->execute();
$totalDirectors = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalDirectors / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #ffffff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Headings */
h1, h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #343a40;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

input.form-control {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ced4da;
}

button.btn {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
}

/* Success and Error Messages */
p {
    text-align: center;
    font-size: 14px;
    margin-top: 10px;
}

p[style="color:green"] {
    font-weight: bold;
    color: #28a745;
}

p[style="color:red"] {
    font-weight: bold;
    color: #dc3545;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

table thead tr {
    background-color: #007bff;
    color: white;
}

table th, table td {
    padding: 10px 15px;
    text-align: left;
    border: 1px solid #dee2e6;
}

table tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

table tbody tr:hover {
    background-color: #e9ecef;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination .page-item {
    margin: 0 5px;
}

.pagination .page-item .page-link {
    color: #007bff;
    padding: 10px 15px;
    border-radius: 5px;
    border: 1px solid #007bff;
    text-decoration: none;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Add Form */
form.w-50 {
    margin-top: 30px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 20px;
    background-color: #ffffff;
}

    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Directors</h1>

        <!-- Search Form -->
        <form class="d-flex mb-4" method="GET">
            <input class="form-control me-2" type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search Directors">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>

        <!-- Directors Table -->
        <?php if (!$directors): ?>
            <h3 class="text-center text-danger">No Directors Found</h3>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Director Name</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($directors as $director): ?>
                        <tr>
                            <td><?= $director['DirectorID'] ?></td>
                            <td><?= htmlspecialchars($director['DirectorName']) ?></td>
                            <td>
                                <form action="actions/deleteDirector.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $director["DirectorID"]?>">
                                    <input class="btn btn-danger" type="submit" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
    <!-- Add New Director -->
        <h2 class="text-center my-5">Add New Directors</h2>
        <form action="actions/addDirectors.php" method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label for="categoryInput" class="form-label">Enter New Director</label>
                <input name="directors" type="text" class="form-control" id="directorInput" placeholder="Enter New Director">
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
            <?php
        if(isset($_SESSION["addDirectors"])){
        if($_SESSION["addDirectors"] == true){
            ?>
            <p style="color:green">Added Successfuly</p>
            <?php
        }else{
            ?>
            <p style="color:red">Invalid Parameter</p>
            <?php
        }
    }
    unset($_SESSION["addDirectors"]);
        if(isset($_SESSION["deleteDirector"])){
        if($_SESSION["deleteDirector"] == true){
            ?>
            <p style="color:green">Deleted Successfuly</p>
            <?php
        }else{
            ?>
            <p style="color:red">Invalid Parameter</p>
            <?php
        }
    }
    unset($_SESSION["deleteDirector"]);
            ?>
        </form>
</body>
</html>
