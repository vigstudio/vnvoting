<!DOCTYPE html>
<html lang="vi" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hệ Thống Kiểm Đếm Phiếu')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-blue-200">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand & Links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center shadow-sm group-hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900 group-hover:text-blue-700 transition-colors">VoteCore</span>
                    </a>

                    @if(auth()->check() && auth()->user()->isAdmin())
                        <div class="hidden md:flex space-x-1">
                            <a href="{{ route('admin.elections.index') }}"
                               class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.elections*') ? 'bg-slate-100 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <span>Cuộc bầu cử</span>
                                </div>
                            </a>

                            <a href="{{ route('admin.users') }}"
                               class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.users') ? 'bg-slate-100 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>Quản lý nhân sự</span>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-slate-700 hidden sm:block">
                        {{ auth()->user()->name ?? 'Người dùng' }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center text-sm text-slate-500 border-t border-slate-200 bg-white">
        <p>Hệ thống Kiểm Đếm Phiếu &copy; {{ date('Y') }}. Chạy nội bộ.</p>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
