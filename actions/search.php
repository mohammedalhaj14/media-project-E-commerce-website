<?php
require_once __DIR__ . '/_helpers.php';

$query = post_text('search', 100);
if ($query === '') {
    json_out(['error' => 'error']);
}

require_once __DIR__ . '/../connection.php';

// % and _ typed by the visitor are searched as normal characters.
$like = '%' . addcslashes($query, '%_\\') . '%';

$stmt = $pdo->prepare("SELECT Title, MovieID FROM movies WHERE Title LIKE :search ORDER BY Title LIMIT 10");
$stmt->bindValue(':search', $like);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$movies) {
    json_out(['empty' => 'empty']);
}

json_out(['success' => $movies]);
