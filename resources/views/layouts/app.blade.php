<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Perizinan Reklame - Ajukan permohonan izin reklame secara online">
    <meta name="theme-color" content="#2563eb">
    <title>@yield('title', 'Sistem Perizinan Reklame')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        [x-cloak] { display: none !important; }
        /* Print styles */
        @media print {
            nav, footer, .no-print { display: none !important; }
            main { padding: 0 !important; }
            .print-break { page-break-before: always; }
        }
        /* Focus visible outline for accessibility */
        :focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    {{-- Skip to Content Link for Accessibility --}}
    @include('components.skip-link')
    <!-- Navigation -->
    <nav class="bg-white shadow-lg" role="navigation" aria-label="Navigasi utama">
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

    <!-- Flash Messages - Using Alert Component above -->
    {{-- Keep old flash messages for backward compatibility --}}
    @if(session('success') && !View::hasSection('no-duplicate-alert'))
    {{-- Handled by @include('components.alert') --}}
    @endif

    @if(session('error') && !View::hasSection('no-duplicate-alert'))
    {{-- Handled by @include('components.alert') --}}
    @endif

    <!-- Main Content -->
    <main id="main-content" class="py-6" role="main" tabindex="-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alert Component --}}
            @include('components.alert')
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8" role="contentinfo">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Sistem Perizinan Reklame. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- Toast Notifications --}}
    @include('components.toast')
    
    {{-- Loading Overlay --}}
    @include('components.loading-overlay')

    <!-- Leaflet JS for maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
