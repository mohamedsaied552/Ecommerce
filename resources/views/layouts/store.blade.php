<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Store')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-full">
  <header class="border-b bg-white">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
      <a href="{{ route('store.home') }}" class="text-lg font-semibold text-gray-900">My Store</a>

      <nav class="flex items-center gap-4">
        <a href="{{ route('store.products.index') }}" class="text-sm text-gray-700 hover:text-gray-900">Products</a>
        <a href="{{ route('store.cart') }}" class="text-sm text-gray-700 hover:text-gray-900">Cart</a>
        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Admin</a>
      </nav>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-8">
    @if(session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    @yield('content')
  </main>

  <footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-gray-500">
      © {{ date('Y') }} My Store. Payments via Paymob.
    </div>
  </footer>
</body>
</html>
