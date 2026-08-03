<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare(
        "INSERT INTO sinh_vien (ma_sv, ho_ten, lop) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $_POST['ma_sv'], $_POST['ho_ten'], $_POST['lop']);
    $stmt->execute();

    // Dữ liệu vừa đổi -> cache cũ không còn đúng, phải xóa đi (cache invalidation)
    if ($cache) {
        $cache->del('sinh_vien:list');
    }

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8">
<title>Thêm sinh viên</title></head><body style="font-family:sans-serif;margin:24px">
<h2>Thêm sinh viên mới</h2>
<form method="post">
  Mã SV: <input name="ma_sv" required><br><br>
  Họ tên: <input name="ho_ten" required><br><br>
  Lớp: <input name="lop" required><br><br>
  <button type="submit">Lưu</button> <a href="index.php">Quay lại</a>
</form>
<p style="color:#666;font-size:13px">
  Sau khi lưu, cache <code>sinh_vien:list</code> sẽ bị xóa để lần tải tiếp theo
  đọc lại từ MySQL — nếu không, danh sách sẽ thiếu bản ghi vừa thêm cho tới khi hết TTL.
</p>
</body></html>
