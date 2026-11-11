<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') | Góc Nhỏ</title>

    <style>
        body {
            margin: 0;
            background: white;
            font-family: Arial, sans-serif;
        }

        .error {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            margin-top: 60px;
        }

        h2 {
            margin: 0;
            font-size: 22px;
        }

        .line {
            height: 28px;
            width: 1px;
            background: #ddd;
        }

        p {
            margin: 0;
            font-size: 20px;
        }

        .back {
            text-align: center;
            margin-top: 24px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="error">
        <h2>@yield('code')</h2>
        <div class="line"></div>
        <p>@yield('message')</p>
    </div>

    <p class="back"><a href="{{ url('/') }}">&larr; Về trang chủ Góc Nhỏ</a></p>

</body>

</html>