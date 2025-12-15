<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if(!isset($_SESSION["admin"])){
        header("location:../index.php");
        die();
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        h1 {
            text-align: center;
        }
        .search {
            display: flex;
            justify-content: center;
            padding: 10px;
        }
        .search form input[type="text"] {
            border-radius: 20px;
            width: 350px;
            padding: 5px 10px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px;
        }
        .pagination a, .pagination span {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #007bff;
            border-radius: 5px;
            color: #007bff;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
        .pagination .active {
            background-color: #007bff;
            color: white;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <h1>Clients</h1>
    <div class="search">
        <form action="clients.php" method="GET">
            <input type="text" name="search" placeholder="Search...">
            <input type="submit" class="btn btn-primary">
        </form>
    </div>
    <?php
    require_once("../connection.php");

    $number_of_clients_in_page = 3;
    $search_query = isset($_GET["search"]) && !empty($_GET["search"]) ? $_GET["search"] : null;

    if ($search_query) {
        // Count for search results
        $search = "%$search_query%";
        $sql_total = "SELECT COUNT(*) as total FROM clients WHERE FName LIKE :search OR LName LIKE :search";
        $stmt_total = $pdo->prepare($sql_total);
        $stmt_total->bindParam(':search', $search);
        $stmt_total->execute();
        $total_number_of_clients = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
    } else {
        // Count all clients
        $sql_total = "SELECT COUNT(ClientID) as total FROM clients";
        $stmt_total = $pdo->query($sql_total);
        $total_number_of_clients = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
    }

    $number_of_pages = ceil($total_number_of_clients / $number_of_clients_in_page);
    $current_page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;

    if ($current_page < 1) $current_page = 1;
    if ($current_page > $number_of_pages) $current_page = $number_of_pages;

    $start_limit = ($current_page - 1) * $number_of_clients_in_page;

    if ($search_query) {
        $sql = "SELECT * FROM clients WHERE FName LIKE :search OR LName LIKE :search LIMIT :start_limit, :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':search', $search);
        $stmt->bindParam(':start_limit', $start_limit, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $number_of_clients_in_page, PDO::PARAM_INT);
    } else {
        $sql = "SELECT ClientID, FName, LName, Phone FROM clients LIMIT :start_limit, :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':start_limit', $start_limit, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $number_of_clients_in_page, PDO::PARAM_INT);
    }
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$clients) {
        echo "<p style='text-align: center; color: red;'>No clients found.</p>";
    } else {
        ?>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Delete</th>
                <th>Update</th>
            </tr>
            <?php
            foreach ($clients as $client) {
                ?>
                <tr>
                    <td><a href="clientInfo.php?id=<?php echo $client['ClientID']; ?>"><?php echo $client["ClientID"]; ?></a></td>
                    <td><?php echo htmlspecialchars($client["FName"]); ?></td>
                    <td><?php echo htmlspecialchars($client["LName"]); ?></td>
                    <td><?php echo htmlspecialchars($client["Phone"]); ?></td>
                    <td>
                        <form action="actions/deleteClient.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $client["ClientID"]?>">
                            <input class="btn btn-danger" type="submit" value="Delete">
                        </form>
                    </td>
                    <td>
                        <form action="updateClient.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $client['ClientID']; ?>">
                            <input type="hidden" name="fname" value="<?php echo $client['FName']; ?>">
                            <input type="hidden" name="lname" value="<?php echo $client['LName']; ?>">
                            <input type="hidden" name="phone" value="<?php echo $client['Phone']; ?>">
                            <input class="btn btn-success" type="submit" value="Update">
                        </form>
                    </td>
                </tr>
                <?php
            }
            
            ?>
        </table>
        <?php
            if (isset($_SESSION["deleteClient"])) {
            if ($_SESSION["deleteClient"] == true) {
                ?>
                <p style="color:green">Deleted Successfully</p>
                <?php
            } else {
                ?>
                <p style="color:red">Invalid Parameter</p>
                <?php
            }
        }
        unset($_SESSION["deleteClient"]);
            if (isset($_SESSION["updateClient"])) {
            if ($_SESSION["updateClient"] === true) {
                ?>
                <p style="color:green">Updated Successfully</p>
                <?php
            } else {
                ?>
                <p style="color:red">Invalid Parameter</p>
                <?php
            }
        }
        unset($_SESSION["updateClient"]);
        ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            // Previous button
            if ($current_page > 1) {
                echo "<a href='?page=" . ($current_page - 1) . ($search_query ? "&search=" . urlencode($search_query) : "") . "'>Previous</a>";
            }

            // Page links
            for ($page = 1; $page <= $number_of_pages; $page++) {
                if ($page == $current_page) {
                    echo "<span class='active'>$page</span>";
                } else {
                    echo "<a href='?page=$page" . ($search_query ? "&search=" . urlencode($search_query) : "") . "'>$page</a>";
                }
            }

            // Next button
            if ($current_page < $number_of_pages) {
                echo "<a href='?page=" . ($current_page + 1) . ($search_query ? "&search=" . urlencode($search_query) : "") . "'>Next</a>";
            }
            ?>
        </div>
        <?php
    }
    ?>
</body>
</html>
