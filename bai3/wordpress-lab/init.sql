SET NAMES utf8mb4;

-- WordPress tự tạo bảng khi cài đặt lần đầu, file này chỉ nới quyền cho tài khoản ứng dụng.
GRANT ALL PRIVILEGES ON wordpress.* TO 'wp_user'@'%';
FLUSH PRIVILEGES;
