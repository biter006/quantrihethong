<?php
// Xóa cache thủ công — dùng để quan sát rõ sự khác nhau giữa cache hit và cache miss
require 'config.php';

if ($cache) {
    $cache->del('sinh_vien:list');
}
header('Location: index.php');
exit;
