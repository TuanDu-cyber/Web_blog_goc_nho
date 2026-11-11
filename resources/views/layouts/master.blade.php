<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Góc Nhỏ - blog cá nhân chia sẻ chuyện học tập, du lịch và đời sống.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Góc Nhỏ - Blog cá nhân')</title>

    <link href="https://fonts.googleapis.com/css?family=Exo+2:400,700|Open+Sans&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body id="top">

    <div class="content-wrap">

        @include('partials.header')

        @include('partials.flash')

        <main class="@yield('main-class', 'main')" role="main">
            @yield('content')
        </main>

        @include('partials.footer')

    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>