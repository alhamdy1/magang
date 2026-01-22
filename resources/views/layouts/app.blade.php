<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Perizinan Reklame')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                            Perizinan Reklame
                        </a>
                    </div>
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('user.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'border-blue-500 text-gray-900' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('user.permits.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('user.permits.*') ? 'border-blue-500 text-gray-900' : '' }}">
                                Permohonan Saya
                            </a>
                        @elseif(auth()->user()->role === 'operator')
                            <a href="{{ route('operator.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('operator.dashboard') ? 'border-blue-500 text-gray-900' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('operator.permits.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('operator.permits.index') ? 'border-blue-500 text-gray-900' : '' }}">
                                Permohonan Tersedia
                            </a>
                            <a href="{{ route('operator.permits.my') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('operator.permits.my') ? 'border-blue-500 text-gray-900' : '' }}">
                                Review Saya
                            </a>
                        @elseif(auth()->user()->role === 'kasi')
                            <a href="{{ route('kasi.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('kasi.dashboard') ? 'border-blue-500 text-gray-900' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('kasi.permits.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('kasi.permits.*') ? 'border-blue-500 text-gray-900' : '' }}">
                                Permohonan
                            </a>
                        @elseif(auth()->user()->role === 'kabid')
                            <a href="{{ route('kabid.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('kabid.dashboard') ? 'border-blue-500 text-gray-900' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('kabid.permits.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('kabid.permits.*') ? 'border-blue-500 text-gray-900' : '' }}">
                                Permohonan
                            </a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'border-blue-500 text-gray-900' : '' }}">
                                Kelola User
                            </a>
                        @endif
                    </div>
                    @endauth
                </div>
                <div class="flex items-center">
                    @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            {{ auth()->user()->name }}
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-1">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                Logout
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Sistem Perizinan Reklame. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Leaflet JS for maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
