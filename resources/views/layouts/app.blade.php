<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'E-SIDAK - Pengadilan Negeri Gorontalo')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" type="image/png" href="{{ asset('storage/logo/logo01.png') }}" >


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    @include('components.navbar')
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            $('.fixed').fadeOut();
        }, 3000);
    </script>
    @endif
    
    @if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            $('.fixed').fadeOut();
        }, 3000);
    </script>
    @endif
</body>
</html>