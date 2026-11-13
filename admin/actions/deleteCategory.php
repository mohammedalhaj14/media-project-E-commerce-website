<?php
require_once __DIR__ . '/_bootstrap.php';
require_admin(true);            // answers with JSON 403 instead of redirecting

header('Content-Type: application/json');

$id = post_id('id');
if ($id === null) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid id']);
    exit;
}

require_once __DIR__ . '/../../connection.php';

$stmt = $pdo->prepare("DELETE FROM categories WHERE CategoryID = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'not found']);
}
