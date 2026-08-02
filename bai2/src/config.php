<?php
$conn = new mysqli(
    getenv('DB_HOST') ?: 'mysql-db',   // hostname = tên container MySQL
    'root',
    getenv('DB_PASS') ?: 'Password123',
    'university'
);
if ($conn->connect_error) {
    die('Không kết nối được CSDL: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
