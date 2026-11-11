## Góc Nhỏ - Blog cộng đồng (Laravel 12)

## Giới thiệu

Blog cộng đồng nhiều tác giả xây dựng bằng Laravel 12. Dự án bắt đầu là bài tập chia layout Blade, sau đó mở rộng thành đồ án cuối khóa: đăng ký, đăng nhập, quản lý bài viết, bình luận, trang quản trị, xử lý nền và test.

Giao diện dựa trên mẫu News Website Template của Frivizn Studio.

## Tính năng

**Khách**

- Trang chủ hiển thị bài đã đăng (mới nhất trước, phân trang 9 bài), tìm kiếm theo từ khóa, lọc theo danh mục và tag
- Trang chi tiết bài viết theo slug: ảnh, tác giả, danh mục, tag, bình luận
- Đăng ký, đăng nhập, quên mật khẩu và đặt lại mật khẩu qua email

**Thành viên**

- Tạo, sửa bài có ảnh, danh mục, nhiều tag; 3 trạng thái: nháp, đã đăng, hẹn giờ đăng
- Xóa mềm: thùng rác, khôi phục, xóa vĩnh viễn
- Bình luận bài đã đăng; người viết, tác giả bài và quản trị viên được xóa bình luận
- Phân quyền bằng Policy, chặn ở phía server

**Quản trị viên (`/admin`)**

- Thống kê: số thành viên, số bài đã đăng, số bình luận, 5 bài nhiều bình luận nhất
- Thêm, sửa, xóa danh mục
- Xem mọi bài viết, xóa bài và bình luận vi phạm

**Xử lý nền**

- Task Scheduling: lệnh `posts:publish` chạy mỗi phút, tự đăng các bài hẹn giờ đã đến hạn
- Queue: khi có bình luận mới, email báo cho tác giả bài viết được đưa vào hàng đợi (bảng `jobs`)

**Chất lượng**

- Trang lỗi riêng cho 403, 404, 500
- Ghi log kênh `auth` và `blog`; giới hạn số lần đăng nhập và quên mật khẩu (5 lần mỗi phút)
- 54 test (47 Feature, 7 Unit)

## Luồng quên mật khẩu

1. Người dùng vào `/forgot-password`, nhập email.
2. Hệ thống gửi email chứa liên kết đặt lại mật khẩu, có hiệu lực 60 phút.
3. Người dùng bấm liên kết, vào `/reset-password/{token}` và nhập mật khẩu mới.
4. Đổi mật khẩu xong thì quay về trang đăng nhập.

Trang quên mật khẩu luôn báo kết quả giống nhau dù email có tồn tại hay không, để không lộ email nào đã đăng ký.

## Log

Các sự kiện xác thực được ghi vào kênh `auth`, mỗi ngày một file tại `storage/logs/auth-YYYY-MM-DD.log`. Mỗi dòng có thời gian, email và IP.

Kênh `blog` ghi các sự kiện về bài viết và bình luận (tạo, sửa, xóa, tự động đăng bài hẹn giờ) tại `storage/logs/blog-YYYY-MM-DD.log`.

| Sự kiện                  | Khi nào ghi                 |
| ------------------------ | --------------------------- |
| Đăng ký                  | Tạo tài khoản thành công    |
| Đăng nhập                | Đăng nhập thành công        |
| Đăng nhập thất bại       | Sai email hoặc mật khẩu     |
| Yêu cầu cấp lại mật khẩu | Gửi mail đặt lại mật khẩu   |
| Đổi mật khẩu             | Đặt lại mật khẩu thành công |

Múi giờ ứng dụng là `Asia/Ho_Chi_Minh` (cấu hình trong `config/app.php`).

## Cấu trúc chính

- `routes/web.php`: khai báo route; `routes/console.php`: lịch chạy `posts:publish`
- `app/Http/Controllers/`: `HomeController`, `AuthController`, `PasswordResetController`, `PostController`, `CommentController`; thư mục `Admin/` gồm `DashboardController`, `CategoryController`, `PostController`, `CommentController`
- `app/Http/Requests/`: validate dữ liệu (`RegisterRequest`, `LoginRequest`, `ForgotPasswordRequest`, `ResetPasswordRequest`, `SearchPost`, `StorePost`, `UpdatePost`, `StoreComment`, `CategoryRequest`)
- `app/Http/Middleware/EnsureUserIsAdmin.php`: chỉ cho admin vào `/admin`
- `app/Policies/`: phân quyền bài viết và bình luận
- `app/Console/Commands/PublishScheduledPosts.php`: đăng bài hẹn giờ
- `app/Mail/`: `WelcomeMail`, `NewCommentMail` (đưa vào queue)
- `app/Notifications/ResetPasswordNotification.php`: email cấp lại mật khẩu
- `resources/views/`: `layouts`, `partials`, `auth`, `posts`, `admin`, `errors`, `mail`
- `tests/`: `Feature` và `Unit`

## Các directive Blade đã sử dụng

@extends: Kế thừa layout.

@section: Khai báo nội dung trang.

@yield: Hiển thị nội dung từ section.

@include: Nhúng header, footer và các thành phần giao diện.

@if: Kiểm tra điều kiện.

@foreach và @forelse: Lặp danh sách bài viết.

@auth và @guest: Hiển thị theo trạng thái đăng nhập.

@error: Hiển thị lỗi validate dưới từng ô nhập.

@csrf: Chống tấn công CSRF trong form.

## Tài khoản mẫu

Sau khi chạy `php artisan migrate --seed`:

| Vai trò    | Email           | Mật khẩu |
| ---------- | --------------- | -------- |
| Quản trị   | admin@gmail.com | 12345678 |
| Thành viên | an@gmail.com    | 12345678 |
| Thành viên | binh@gmail.com  | 12345678 |
| Thành viên | chau@gmail.com  | 12345678 |
| Thành viên | dat@gmail.com   | 12345678 |
| Thành viên | ha@gmail.com    | 12345678 |

## Cách chạy dự án

Cài đặt thư viện:

```bash
composer install
```

Tạo file `.env` và khóa ứng dụng:

```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình trong file `.env`:

- Database: `.env.example` mặc định dùng SQLite (file `database/database.sqlite`). Muốn dùng MySQL thì đổi `DB_CONNECTION=mysql` và điền `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Gửi mail: `MAIL_MAILER=log` ghi email vào `storage/logs/laravel.log` (không cần cấu hình gì). Muốn thử bằng Mailtrap thì đổi sang `smtp` và điền `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- Queue: `QUEUE_CONNECTION=database`

Tạo bảng và dữ liệu mẫu:

```bash
php artisan migrate --seed
```

Chạy dự án (mở 3 cửa sổ terminal):

```bash
php artisan serve          # web, mở http://127.0.0.1:8000
php artisan queue:work     # xử lý hàng đợi (gửi email bình luận)
php artisan schedule:work  # chạy lịch mỗi phút (tự đăng bài hẹn giờ)
```

Hoặc chạy cả ba cùng lúc bằng `composer dev` (cần Node.js).

## Cách kiểm tra nhanh

1. **Queue:** đăng nhập `an@gmail.com`, bình luận vào bài của người khác, rồi xem nội dung email trong `storage/logs/laravel.log` (khi `queue:work` đang chạy).
2. **Hẹn giờ:** đăng nhập, tạo bài ở trạng thái "Hẹn giờ đăng" với thời gian vài phút sau; khi `schedule:work` đang chạy, bài tự chuyển sang "Đã đăng". Hoặc chạy thử ngay: `php artisan posts:publish`.
3. **Quản trị:** đăng nhập `admin@gmail.com` rồi vào `/admin`.
4. **Quên mật khẩu:** vào `/forgot-password`, lấy liên kết trong log hoặc Mailtrap.
5. **Test:** `php artisan test`.
