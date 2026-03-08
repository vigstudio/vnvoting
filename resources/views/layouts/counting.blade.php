<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kiểm Phiếu')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="counting-body bg-slate-50">
    @auth
        <nav class="counting-nav px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h1 class="counting-heading text-blue-900">KIỂM PHIẾU</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="counting-text text-slate-700">{{ auth()->user()->name }}</span>
                    <a href="{{ route('dashboard') }}"
                       class="counting-text text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                        Trang Chủ
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="btn-counting-large bg-red-600 text-white hover:bg-red-700 transition-colors">
                            Đăng Xuất
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <main class="py-6 px-4">
        @yield('content')
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
