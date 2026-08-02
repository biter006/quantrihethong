# php-web-app — Bài lab B2: Triển khai ứng dụng qua Docker

Ứng dụng quản lý sinh viên, mô hình **2 container**: `php-web` (PHP 8.3 + Apache) và
`mysql-db` (MySQL 8.0), nối nhau qua network `php-network`, dữ liệu lưu ở volume `mysql_data`.

## Cấu trúc

```
php-web-app/
├── Dockerfile
├── .dockerignore
├── src/
│   ├── index.php      # danh sách sinh viên
│   ├── add.php        # form thêm sinh viên
│   └── config.php     # kết nối CSDL qua hostname "mysql-db"
├── data/
│   └── init.sql       # tạo bảng + 3 bản ghi mẫu (chạy lần khởi tạo đầu)
└── README.md
```

## Chạy lab (trên máy ảo Ubuntu 20.04)

Chép cả thư mục này vào `~/php-web-app` rồi:

```bash
cd ~/php-web-app

# 1) Tạo network
docker network create php-network

# 2) Chạy MySQL (KHÔNG mở cổng ra ngoài)
docker run -d \
  --name mysql-db \
  --network php-network \
  -e MYSQL_ROOT_PASSWORD=Password123 \
  -e MYSQL_DATABASE=university \
  -v mysql_data:/var/lib/mysql \
  -v $(pwd)/data/init.sql:/docker-entrypoint-initdb.d/init.sql \
  mysql:8.0

# Đợi tới khi thấy "ready for connections" (~20-30 giây)
docker logs -f mysql-db

# 3) Build image và chạy web
docker build -t php-web-app:1.0 .
docker run -d --name php-web --network php-network -p 8080:80 php-web-app:1.0
```

Truy cập `http://localhost:8080` trong máy ảo, hoặc `http://<IP-máy-ảo>:8080` từ máy Desktop
(lấy IP bằng `ip a | grep ens`).

## Kiểm tra / debug

```bash
docker ps                                   # php-web và mysql-db đều Up
docker logs php-web
docker logs mysql-db
docker exec -it php-web bash                # vào trong container PHP
docker exec -it php-web ping -c 3 mysql-db  # kiểm tra DNS nội bộ
```

## Kiểm chứng Volume

```bash
docker rm -f mysql-db          # xóa hẳn container CSDL
docker run -d --name mysql-db --network php-network \
  -e MYSQL_ROOT_PASSWORD=Password123 -e MYSQL_DATABASE=university \
  -v mysql_data:/var/lib/mysql mysql:8.0
docker logs -f mysql-db
```

Tải lại trang web — dữ liệu sinh viên vẫn còn nguyên vì nằm trong volume `mysql_data`.

## Bài tập bắt buộc (mục 4.7): PHP mới nhất, cổng 8889, tag mới

Sửa dòng đầu `Dockerfile` thành `FROM php:apache`, rồi:

```bash
docker build -t php-web-app:2.0 .
docker rm -f php-web
docker run -d --name php-web --network php-network -p 8889:80 php-web-app:2.0

docker ps                          # PORTS phải là 0.0.0.0:8889->80/tcp
docker logs php-web
docker exec -it php-web php -v
curl -I http://localhost:8889      # HTTP/1.1 200 OK
```

Chụp màn hình `docker ps`, `docker logs` và trình duyệt ở cổng 8889 để nộp báo cáo.

## Lưu ý

- `init.sql` chỉ chạy khi volume `mysql_data` còn **trống** (lần đầu). Muốn khởi tạo lại:
  `docker rm -f mysql-db && docker volume rm mysql_data` rồi chạy lại bước 2.
- Mật khẩu để trần qua `-e` chỉ phù hợp môi trường lab. Production dùng Docker Secrets / Vault.
- `data/` nằm trong `.dockerignore` nên **không** bị copy vào image — nó được bind mount vào MySQL lúc chạy.
