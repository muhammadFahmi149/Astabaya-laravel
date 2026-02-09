<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Astabaya') - Aplikasi Statistik Kota Surabaya</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CSS Files -->
    @stack('styles')
    
    <link rel="shortcut icon" href="{{ asset('images/Aastabaya-favicon(2).png') }}">
</head>
<body>
    @yield('content')
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>


