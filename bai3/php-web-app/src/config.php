<?php
// ---------- Kết nối MySQL ----------
// Hostname lấy từ biến môi trường do docker-compose truyền vào,
// mặc định là tên service "mysql-db" trong file docker-compose.yml
$conn = new mysqli(
    getenv('DB_HOST') ?: 'mysql-db',
    'root',
    getenv('DB_PASS') ?: 'Password123',
    getenv('DB_NAME') ?: 'university'
);
if ($conn->connect_error) {
    die('Không kết nối được CSDL: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// ---------- Kết nối Redis ----------
// $cache = null nghĩa là cache không dùng được; ứng dụng vẫn chạy, chỉ chậm hơn.
$cache    = null;
$cacheTtl = (int)(getenv('CACHE_TTL') ?: 60);
try {
    $redis = new Redis();
    $redis->connect(getenv('REDIS_HOST') ?: 'redis', 6379, 2.0);
    $pass = getenv('REDIS_PASS');
    if ($pass) {
        $redis->auth($pass);
    }
    $cache = $redis;
} catch (Throwable $e) {
    error_log('Redis không sẵn sàng: ' . $e->getMessage());
}
