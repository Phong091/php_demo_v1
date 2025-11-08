<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Demo_v2 — Hướng dẫn chạy bằng Docker

## Yêu cầu
- Docker Desktop
- Docker Compose

## Khởi chạy lần đầu
1) Mở terminal tại THƯ MỤC GỐC của repo (nơi có file `docker-compose.yml`).

   Ví dụ:
   - Windows (PowerShell):
     ```powershell
     cd path\to\your\cloned\repo
     ```
   - macOS/Linux (bash/zsh):
     ```bash
     cd /path/to/your/cloned/repo
     ```

2) Khởi chạy dịch vụ và setup Laravel:
```powershell
docker compose up -d --build
docker compose exec php bash
composer install
cp .env.example .env
php artisan key:generate

# Cấu hình DB trong .env (đã có sẵn dịch vụ db trong docker-compose)
# DB_CONNECTION=mysql
# DB_HOST=db
# DB_PORT=3306
# DB_DATABASE=phong_phpv1
# DB_USERNAME=root
# DB_PASSWORD=root

php artisan migrate
php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder
exit
```

## Frontend
```powershell
docker compose exec php bash
npm ci || npm install
npm run build
# hoặc dùng Vite dev server (đã map cổng 5173):
# npm run dev
exit
```

## Dịch vụ và URL
- App (Nginx + PHP): http://localhost
- phpMyAdmin: http://localhost:8080 (Server: `db`, User: `root`, Pass: `root`)
- Adminer: http://localhost:9090
- Mailpit (SMTP/UI): http://localhost:8025
- Redis: 6379

## Lưu ý về gửi mail
- Mặc định khi gửi mail, hệ thống ghi nội dung email vào file log thay vì gửi thật.
- Đường dẫn log: `storage/logs/laravel.log`.
- Nếu cần, đảm bảo `.env` dùng cấu hình:
  - `MAIL_MAILER=log`

## Xác thực JWT (token based)
- Khi đăng nhập thành công, hệ thống phát hành JWT và lưu trong cookie `token` (HttpOnly).
- Các trang cần đăng nhập (ví dụ `/profile`) sẽ kiểm tra JWT thay cho session.
- Trang quản trị người dùng: `/admin/users` (chỉ role=0).

### Cấu hình JWT
Thêm vào `.env` (sẽ có mặc định nếu chưa đặt):
```
JWT_SECRET=b1946ac92492d2347c6235b4d2611184f1e3a92d7e94cb9e92a0d6d2f89f9a0c
JWT_TTL_MINUTES=10080  # 7 ngày
```
Tạo chuỗi ngẫu nhiên có thể dùng: `php -r "echo bin2hex(random_bytes(32));"`

### Tài khoản admin (seeder)
- Email: `admin@example.com`
- Mật khẩu: `Admin@1234`
- Role: `0` (admin)

## Lệnh hữu ích
```powershell
# Khởi động lại (lần sau không cần build)
docker compose up -d

# Xem log
docker compose logs -f nginx
docker compose logs -f php

# Truy cập container PHP
docker compose exec php bash

# Dừng và xoá containers + networks
docker compose down
```