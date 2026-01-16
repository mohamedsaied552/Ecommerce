<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Invoice System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-gray-900">
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-800">
                    <h1 class="text-white text-xl font-bold">Invoice System</h1>
                </div>
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-2 py-4 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                            <x-icon name="home" class="mr-3 h-6 w-6" />
                            Dashboard
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.invoices.*') ? 'bg-gray-800 text-white' : '' }}">
                            <x-icon name="document-text" class="mr-3 h-6 w-6" />
                            Invoices
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white' : '' }}">
    <x-icon name="cube" class="mr-3 h-6 w-6" />
    Products
</a>

<a href="{{ route('admin.orders.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : '' }}">
    <x-icon name="shopping-bag" class="mr-3 h-6 w-6" />
    Orders
</a>

                        <a href="{{ route('admin.invoices.create') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.invoices.create') ? 'bg-gray-800 text-white' : '' }}">
                            <x-icon name="plus" class="mr-3 h-6 w-6" />
                            New Invoice
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md w-full">
                            <x-icon name="logout" class="mr-3 h-6 w-6" />
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" x-transition class="md:hidden fixed inset-0 z-40 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-900">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <x-icon name="x" class="h-6 w-6 text-white" />
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <h1 class="text-white text-xl font-bold">Invoice System</h1>
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <x-icon name="home" class="mr-4 h-6 w-6" />
                            Dashboard
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <x-icon name="document-text" class="mr-4 h-6 w-6" />
                            Invoices
                        </a>
                        <a href="{{ route('admin.invoices.create') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <x-icon name="plus" class="mr-4 h-6 w-6" />
                            New Invoice
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md w-full">
                            <x-icon name="logout" class="mr-4 h-6 w-6" />
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top bar -->
            <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-50">
                <button @click="sidebarOpen = true" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                    <x-icon name="filter" class="h-6 w-6" />
                </button>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div data-success-message="{{ session('success') }}" class="hidden"></div>
            @endif
            @if(session('error'))
                <div data-error-message="{{ session('error') }}" class="hidden"></div>
            @endif

            <!-- Page content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
