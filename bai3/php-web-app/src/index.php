<?php
require 'config.php';

$key    = 'sinh_vien:list';
$source = 'MySQL';
$rows   = null;
$t0     = microtime(true);

// ---------- Bước 1: thử lấy từ cache ----------
if ($cache) {
    $cached = $cache->get($key);
    if ($cached !== false) {
        $rows   = json_decode($cached, true);
        $source = 'Redis (cache)';
    }
}

// ---------- Bước 2: cache rỗng thì truy vấn CSDL rồi ghi vào cache ----------
if ($rows === null) {
    $rows = [];
    $rs = $conn->query("SELECT id, ma_sv, ho_ten, lop FROM sinh_vien ORDER BY id");
    while ($r = $rs->fetch_assoc()) {
        $rows[] = $r;
    }
    if ($cache) {
        $cache->setex($key, $cacheTtl, json_encode($rows, JSON_UNESCAPED_UNICODE));
    }
}

$ms = round((microtime(true) - $t0) * 1000, 2);
?>
<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8">
<title>Quản lý sinh viên</title>
<style>
  body { font-family: sans-serif; margin: 24px; }
  table { border-collapse: collapse; }
  th, td { border: 1px solid #444; padding: 6px 12px; }
  th { background: #eee; }
  .badge { display:inline-block; padding:3px 10px; border-radius:10px; font-size:13px; }
  .hit  { background:#eafaf0; border:1px solid #1e7d45; color:#1e7d45; }
  .miss { background:#fff4e8; border:1px solid #d35400; color:#d35400; }
</style></head><body>
<h2>Danh sách sinh viên</h2>

<p>
  Nguồn dữ liệu:
  <span class="badge <?= $source === 'MySQL' ? 'miss' : 'hit' ?>"><?= htmlspecialchars($source) ?></span>
  &nbsp;—&nbsp; <?= $ms ?> ms
  <?php if (!$cache): ?>
    &nbsp;<span class="badge miss">Redis không kết nối được</span>
  <?php endif; ?>
</p>

<p>
  <a href="add.php">+ Thêm sinh viên</a> &nbsp;|&nbsp;
  <a href="index.php">Tải lại</a> &nbsp;|&nbsp;
  <a href="flush.php">Xóa cache</a>
</p>

<table>
<tr><th>ID</th><th>Mã SV</th><th>Họ tên</th><th>Lớp</th></tr>
<?php foreach ($rows as $r): ?>
<tr>
  <td><?= (int)$r['id'] ?></td>
  <td><?= htmlspecialchars($r['ma_sv']) ?></td>
  <td><?= htmlspecialchars($r['ho_ten']) ?></td>
  <td><?= htmlspecialchars($r['lop']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<p style="color:#666; font-size:13px">
  Lần tải đầu lấy từ MySQL và ghi vào Redis với TTL <?= $cacheTtl ?> giây.
  Tải lại trang trong khoảng thời gian đó sẽ thấy nguồn đổi thành Redis.
</p>
</body></html>
