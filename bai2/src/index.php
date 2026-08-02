<?php require 'config.php'; ?>
<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8">
<title>Quản lý sinh viên</title></head><body>
<h2>Danh sách sinh viên</h2>
<p><a href="add.php">+ Thêm sinh viên</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Mã SV</th><th>Họ tên</th><th>Lớp</th></tr>
<?php
$rs = $conn->query("SELECT * FROM sinh_vien ORDER BY id");
while ($r = $rs->fetch_assoc()) {
    printf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td></tr>",
        $r['id'], htmlspecialchars($r['ma_sv']),
        htmlspecialchars($r['ho_ten']), htmlspecialchars($r['lop']));
}
?>
</table></body></html>
