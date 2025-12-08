<?php
// قراءة إعدادات الاتصال من متغيرات البيئة المحددة في Render
$servername = getenv('DB_HOST') ?: 'localhost'; 
$username   = getenv('DB_USERNAME') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: ''; // يجب تعديل هذه القيمة إلى كلمة مرورك المحلية إذا كانت موجودة
$dbname     = getenv('DB_DATABASE') ?: 'your_local_db_name'; // استبدل 'your_local_db_name' باسم قاعدة البيانات المحلية لديك
$port       = getenv('DB_PORT') ?: 3306; // البورت الافتراضي لـ MySQL

// إنشاء الاتصال باستخدام MySQLi (بما أن مشروعك Pure PHP)
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}
// ... بقية شيفرة الاتصال
?>