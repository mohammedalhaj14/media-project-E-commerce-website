<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("location:../index.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<h1 style="text-align:center">Actors</h1>
<?php
require_once("../connection.php");

// Pagination setup
$limit = 5; // Number of actors per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of actors for pagination
$sqlTotal = "SELECT COUNT(*) as total FROM actors";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute();
$totalActors = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalActors / $limit);

// Fetch actors for the current page
$sql = "SELECT * 
        FROM actors 
        JOIN genders ON genders.GenderID = actors.GenderID 
        JOIN nationalities ON nationalities.NationalityID = actors.NationalityID 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$actors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$actors) {
    ?>
    <h3 style="text-align:center;color:red">Empty</h3>
    <?php
} else {
    ?>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Actor Name</th>
            <th>Gender</th>
            <th>Nationality</th>
            <th>Delete</th>
        </tr>
        <?php
        foreach ($actors as $actor) {
            ?>
            <tr>
                <td><?php echo $actor["ActorID"] ?></td>
                <td><?php echo htmlspecialchars($actor["ActorName"]) ?></td>
                <td><?php echo htmlspecialchars($actor["GenderName"]) ?></td>
                <td><?php echo htmlspecialchars($actor["NationalityName"]) ?></td>
                <td>
                    <form action="actions/deleteActor.php" method="post">
                        <input type="hidden" name="id" value="<?php $actor["ActorID"]?>">
                        <input class="btn btn-danger" type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}

// Pagination links
if ($totalPages > 1) {
    ?>
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php
}
?>
<!-- Add Actor Form -->
<div style="display:flex;justify-content:center;align-items:center">
    <form action="actions/addActor.php" method="POST">
        <div class="mb-3">
            <label for="actorName" class="form-label">Actor Name</label>
            <input type="text" name="actor" class="form-control" id="actorName">
        </div>
        <p>Gender</p>
        <select name="gender" class="form-select">
            <?php
            $sql = "SELECT * FROM genders";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $genders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($genders as $gender) {
                ?>
                <option value="<?= $gender['GenderID'] ?>"><?= htmlspecialchars($gender["GenderName"]) ?></option>
                <?php
            }
            ?>
        </select>
        <p>Nationality</p>
        <select name="nationality" class="form-select">
            <?php
            $sql = "SELECT * FROM nationalities";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $nationalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($nationalities as $nationality) {
                ?>
                <option value="<?= $nationality['NationalityID'] ?>"><?= htmlspecialchars($nationality["NationalityName"]) ?></option>
                <?php
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
        <?php
        if (isset($_SESSION["addActor"])) {
            if ($_SESSION["addActor"] == true) {
                ?>
                <p style="color:green">Added Successfully</p>
                <?php
            } else {
                ?>
                <p style="color:red">Invalid Parameter</p>
                <?php
            }
        }
        unset($_SESSION["addActor"]);
            if (isset($_SESSION["deleteActor"])) {
            if ($_SESSION["deleteActor"] == true) {
                ?>
                <p style="color:green">Deleted Successfully</p>
                <?php
            } else {
                ?>
                <p style="color:red">Invalid Parameter</p>
                <?php
            }
        }
        unset($_SESSION["deleteActor"]);
        ?>
    </form>
</div>
</body>
</html>
