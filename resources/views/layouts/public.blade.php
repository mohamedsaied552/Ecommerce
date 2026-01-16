<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Invoice') - Invoice System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-full flex flex-col">
        <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </main>
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Invoice System. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
