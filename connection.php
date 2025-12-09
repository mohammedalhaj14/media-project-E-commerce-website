<?php
// تأكد من قراءة المتغيرات البيئية (إذا كنت تستخدم مكتبة dotenv)
// ...

$servername = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_DATABASE');
$port = getenv('DB_PORT');

// استخدام DSN لـ PostgreSQL
$dsn = "pgsql:host=$servername;port=$port;dbname=$dbname";

try {
    $conn = new PDO($dsn, $username, $password);
    // تعيين وضع الخطأ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // يمكنك تسجيل الخطأ بدلاً من إظهاره مباشرة
    die("Connection failed: " . $e->getMessage()); 
}
?>