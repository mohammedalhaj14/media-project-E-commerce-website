<?php
// Shared helpers for the files in actions/ (the customer side).
// Usage at the top of an action:  require_once __DIR__ . '/_helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Send a JSON answer and stop.
function json_out(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// For AJAX endpoints: the visitor must be a logged-in customer. Returns the ClientID.
function require_user_json(): int
{
    if (!isset($_SESSION['user'])) {
        json_out(['error' => 'login required'], 401);
    }
    return (int) $_SESSION['user'];
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
