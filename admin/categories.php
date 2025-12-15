<?php
session_start();
if(!isset($_SESSION["admin"])){
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

// Fetch categories with search and pagination
$sql = "SELECT * FROM categories 
        WHERE CategoryName LIKE :search 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total categories for pagination
$sqlTotal = "SELECT COUNT(*) as total FROM categories WHERE CategoryName LIKE :search";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmtTotal->execute();
$totalCategories = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalCategories / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        <style>
    /* General Body Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa; /* Light background */
        margin: 0;
        padding: 0;
    }

    /* Container */
    .container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    /* Header Styling */
    h1, h2 {
        color: #333;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 20px;
    }

    h1.text-center {
        border-bottom: 3px solid #007bff;
        padding-bottom: 10px;
        display: inline-block;
    }

    /* Form Styling */
    form {
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 20px;
        padding: 10px 15px;
    }

    .btn {
        border-radius: 20px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    /* Table Styling */
    .table {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .table th {
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 10px;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    /* Pagination Styling */
    .pagination {
        margin-top: 20px;
    }

    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .page-link {
        color: #007bff;
        border-radius: 20px;
    }

    .page-link:hover {
        background-color: #f1f1f1;
        text-decoration: none;
    }

    /* Messages */
    p {
        text-align: center;
        font-weight: bold;
        font-size: 1rem;
    }

    p.text-danger {
        color: #dc3545;
    }

    p.text-success {
        color: #28a745;
    }

    /* Add New Category Form */
    .w-50 {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .w-50 .form-label {
        font-weight: bold;
        color: #333;
    }
</style>

    </style>
</head>
<body>
    < class="container my-5">
        <h1 class="text-center">Categories</h1>

        <!-- Search Form -->
        <form class="d-flex mb-4" method="GET">
            <input class="form-control me-2" type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search Categories">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>

        <!-- Categories Table -->
        <?php if (!$categories): ?>
            <h3 class="text-center text-danger">No Categories Found</h3>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Delete</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $categorie): ?>
                        <tr meta-cat="<?= $categorie['CategoryID'] ?>">
                            <td><?= $categorie['CategoryID'] ?></td>
                            <td><?= htmlspecialchars($categorie['CategoryName']) ?></td>
                            <td>
                                <button type="button" onclick="deletee(<?= $categorie['CategoryID'] ?>)" class="btn btn-danger">Delete</button>
                            </td>
                            <td>
                                <form action="updateCategorie.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $categorie['CategoryID'] ?>">
                                    <input type="hidden" name="categorie" value="<?= htmlspecialchars($categorie['CategoryName']) ?>">
                                    <input type="submit" value="Update" class="btn btn-primary">
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

        <!-- Add New Category -->
        <h2 class="text-center my-5">Add New Categories</h2>
        <form action="actions/addCategories.php" method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label for="categoryInput" class="form-label">Enter New Category</label>
                <input name="categories" type="text" class="form-control" id="categoryInput" placeholder="Enter New Category">
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    

    <script>
        function deletee(id) {
            const urlencoded = new URLSearchParams();
            urlencoded.append("id", id);

            const requestOptions = {
                method: "POST",
                body: urlencoded,
            };

            fetch("http://localhost/media/admin/actions/deleteCategory.php", requestOptions)
                .then((response) => response.json())
                .then((result) => getDataDelete(result, id))
                .catch((error) => console.error(error));
        }

        function getDataDelete(result, id) {
            if (result.success) {
                document.querySelector(`tr[meta-cat='${id}']`).remove();
            } else {
                alert("Failed to delete category");
            }
        }
    </script>
</body>
</html>
