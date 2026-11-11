<?php

use Illuminate\Support\Facades\Schedule;

// Mỗi phút kiểm tra và đăng các bài hẹn giờ đã đến hạn.
// withoutOverlapping: lượt trước chưa chạy xong thì bỏ qua lượt này (tránh đăng trùng);
// khóa tự mở sau 10 phút nếu tiến trình bị treo.
Schedule::command('posts:publish')->everyMinute()->withoutOverlapping(10);
