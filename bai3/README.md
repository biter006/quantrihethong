# Bài lab 3 — Điều phối hệ thống với Docker Compose

Hai hệ thống độc lập, chạy được song song vì dùng cổng và network khác nhau.

```
bai3/
├── php-web-app/        Bài thực hành 1 — hệ thống Bài lab 2 + Redis, gom vào Compose
│   ├── docker-compose.yml
│   ├── .env.example
│   ├── Dockerfile
│   ├── src/{index,add,config,flush}.php
│   └── data/init.sql
├── wordpress-lab/      Bài thực hành 2 — WordPress + MySQL + Redis + phpMyAdmin
│   ├── docker-compose.yml
│   ├── .env.example
│   ├── Dockerfile
│   └── init.sql
└── .gitignore
```

## Lấy source về máy ảo Ubuntu

```bash
cd ~
git clone https://gitlab.com/leeictu/trienkhaiquantrihethongphanmem.git
cp -r trienkhaiquantrihethongphanmem/bai3 ~/bai3
```

## Bài thực hành 1 — php-web-app + Redis

```bash
cd ~/bai3/php-web-app
cp .env.example .env          # BẮT BUỘC, nếu thiếu Compose sẽ báo biến rỗng

docker compose config          # kiểm tra cú pháp trước khi chạy
docker compose up -d --build
docker compose ps
docker compose logs -f php-web
```

Truy cập `http://<IP-máy-ảo>:8080`. Lần đầu nhãn nguồn dữ liệu là **MySQL**, tải lại
trong vòng 60 giây sẽ đổi thành **Redis (cache)**.

Dừng và dọn:

```bash
docker compose down            # xóa container + network, GIỮ volume
docker compose down -v         # xóa cả volume, mất dữ liệu
```

## Bài thực hành 2 — WordPress

Trước khi chạy, phải dừng hệ thống ở bài thực hành 1 nếu muốn tránh tiêu tốn RAM:

```bash
cd ~/bai3/php-web-app && docker compose down
cd ~/bai3/wordpress-lab
cp .env.example .env
docker compose up -d --build
docker compose ps
```

- WordPress: `http://<IP-máy-ảo>:8081`
- phpMyAdmin: `http://<IP-máy-ảo>:8082` (server `db`, user `wp_user`)

## Cổng đã dùng trong toàn học phần

| Cổng | Ai giữ |
|---|---|
| 80, 443 | Nginx trên host — Bài lab 1 |
| 8080 | php-web — Bài lab 2 và bài thực hành 1 của Bài lab 3 |
| 8081 | WordPress — bài thực hành 2 |
| 8082 | phpMyAdmin — bài thực hành 2 |
| 8889 | php-web bản tag 2.0 — bài tập bắt buộc của Bài lab 2 |

## Lưu ý

- File `.env` nằm trong `.gitignore` nên **không** được commit. Repo chỉ có `.env.example`.
- `docker compose down -v` xóa volume, mất toàn bộ dữ liệu — chỉ dùng khi muốn khởi tạo lại.
- Redis dùng `--requirepass` nên mọi kết nối phải xác thực; ứng dụng lấy mật khẩu từ biến `REDIS_PASS`.
- Nếu Redis chết, ứng dụng vẫn chạy (đọc thẳng MySQL) nhưng nhãn sẽ báo không kết nối được cache.
