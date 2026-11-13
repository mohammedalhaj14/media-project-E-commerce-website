<?php
// Shared helpers for every file in admin/actions/.
// Usage at the top of an action:
//     require_once __DIR__ . '/_bootstrap.php';
//     require_admin();                       // or require_admin(true) for JSON endpoints
//     require_once __DIR__ . '/../../connection.php';   // defines $pdo

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Stop everyone who is not logged in as admin (nothing is printed before the redirect).
function require_admin(bool $json = false): void
{
    if (isset($_SESSION['admin'])) {
        return;
    }
    if ($json) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'forbidden']);
    } else {
        header('location:../../index.php');
    }
    exit;
}

// Save the result in the session (read by flash() in the admin pages) and go back.
function flash_redirect(string $key, bool $ok, string $page): void
{
    $_SESSION[$key] = $ok;
    header('location:../' . $page);
    exit;
}

// Trimmed text from $_POST. Returns '' when missing, not a string, or longer than $max characters.
function post_text(string $key, int $max): string
{
    $value = $_POST[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }
    $value = trim($value);
    return mb_strlen($value) > $max ? '' : $value;
}

// Positive integer id from $_POST, or null.
function post_id(string $key): ?int
{
    $value = $_POST[$key] ?? null;
    return (is_string($value) && ctype_digit($value) && (int) $value > 0) ? (int) $value : null;
}

// Integer between $min and $max from $_POST, or null.
function post_int_range(string $key, int $min, int $max): ?int
{
    $value = $_POST[$key] ?? null;
    if (!is_string($value) || !preg_match('/^-?\d+$/', trim($value))) {
        return null;
    }
    $number = (int) trim($value);
    return ($number >= $min && $number <= $max) ? $number : null;
}

// Non-negative decimal from $_POST, or null.
function post_decimal(string $key): ?float
{
    $value = $_POST[$key] ?? null;
    if (!is_string($value) || !is_numeric(trim($value))) {
        return null;
    }
    $number = (float) trim($value);
    return ($number >= 0 && $number <= 99999999) ? $number : null;
}

// List of unique positive integer ids from an array field such as actors[].
function post_id_list(string $key): array
{
    $values = $_POST[$key] ?? [];
    if (!is_array($values)) {
        return [];
    }
    $ids = [];
    foreach ($values as $item) {
        if (is_string($item) && ctype_digit($item) && (int) $item > 0) {
            $ids[(int) $item] = true;
        }
    }
    return array_keys($ids);
}

// True when every id exists in $table.$column. Table and column names are written in our own code, never taken from users.
function all_exist(PDO $pdo, string $table, string $column, array $ids): bool
{
    if (!$ids) {
        return false;
    }
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $stmt  = $pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` IN ($marks)");
    $stmt->execute(array_values($ids));
    return (int) $stmt->fetchColumn() === count($ids);
}
