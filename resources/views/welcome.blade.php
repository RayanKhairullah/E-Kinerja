<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Kinerja</title>
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/logoSmea.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-blue-50 font-sans text-gray-800">

    <!-- Navbar -->
    <header class="flex justify-between items-center p-4 md:px-16 bg-blue-50">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-gray-300"></div>
            <span class="font-bold text-blue-900">Lazy Developer</span>
        </div>
        <nav class="flex items-center space-x-4">
            <a href="#" class="font-semibold text-blue-900 hover:text-blue-700">Home</a>
            <a href="#" class="font-semibold text-blue-900 hover:text-blue-700">Buat Laporan</a>
            <a href="e-kinerja/login" class="bg-blue-700 text-white font-semibold py-1 px-4 rounded shadow hover:bg-blue-800">Log in</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="flex flex-col lg:flex-row items-center justify-between px-6 md:px-16 py-10">
        <div class="space-y-4">
            <h1 class="text-4xl md:text-5xl font-extrabold text-blue-900">Laporan<br>Kinerja Guru</h1>
        </div>
        <div class="mt-10 lg:mt-0">
            <img src="{{ asset('images/logoSmea.png') }}" alt="SMKN 1 Kota Bengkulu" class="w-84 lg:w-80 rounded-xl p-4">
        </div>
    </section>

    <!-- Content Section -->
    <section class="flex flex-col md:flex-row items-center justify-center px-6 md:px-16 pb-10">
        <div class="w-full md:w-1/2">
            <img src="{{ asset('images/garfisSide.png') }}" alt="Ilustrasi Folder" class="w-full max-w-xs mx-auto">
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 md:pl-10 text-justify">
            <h2 class="text-lg font-bold text-blue-900 mb-2">Laporan Kinerja Guru</h2>
            <p class="text-blue-900">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-6 bg-blue-50 font-semibold">
        Footer
    </footer>

</body>

</html>
