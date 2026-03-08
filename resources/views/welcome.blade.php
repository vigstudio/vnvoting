<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>VoteCore - Hệ Thống Kiểm Phiếu</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800|source-sans-3:400,500,600,700" rel="stylesheet" />
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased h-full flex flex-col justify-between">
        <!-- Header -->
        <header class="bg-white border-b border-slate-200 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm">
                        V
                    </div>
                    <span class="text-2xl font-extrabold text-slate-800 tracking-tight heading-font">VoteCore<span class="text-blue-600">.</span></span>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-base font-bold text-blue-600 hover:text-blue-800 transition-colors">Vào Dashboard &rarr;</a>
                        @else
                            <a href="{{ route('login') }}" class="text-base font-bold text-blue-600 hover:text-blue-800 transition-colors">Đăng nhập</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl w-full text-center">
                <!-- Hero Section -->
                <div class="mb-16">
                    <h1 class="text-5xl sm:text-6xl font-black text-slate-900 heading-font mb-6 tracking-tight">
                        Kiểm phiếu <span class="text-blue-600">Nhanh Chóng</span> & <span class="text-orange-500">Chính Xác</span>
                    </h1>
                    <p class="text-xl text-slate-500 max-w-2xl mx-auto mb-10">
                        Nền tảng VoteCore được chuẩn hóa giao diện để thao tác tốc độ cao, hiển thị tức thì và giảm thiểu sai sót tối đa cho các Ban Cán Sự.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center py-4 px-8 border border-transparent text-xl font-bold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 hover:-translate-y-1 transition-all shadow-sm focus:ring-4 focus:ring-blue-500/50 cursor-pointer">
                                Mở Trung Tâm Kiểm Phiếu
                                <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex justify-center items-center py-4 px-8 border border-transparent text-xl font-bold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 hover:-translate-y-1 transition-all shadow-sm focus:ring-4 focus:ring-blue-500/50 cursor-pointer">
                                Đăng nhập Hệ thống
                                <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Features Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left max-w-4xl mx-auto">
                    <!-- Admin Card -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-200 transition-all hover:border-blue-300 hover:shadow-md group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-3 heading-font">Dành Cho Quản Trị Viên</h3>
                        <p class="text-slate-500 text-lg mb-6 leading-relaxed">
                            Quản lý cuộc bầu cử, chức vụ, danh sách ứng viên và theo dõi tiến độ theo thời gian thực trực quan.
                        </p>
                        <a href="/docs/admin.html" class="inline-flex items-center text-blue-600 font-bold hover:text-blue-800 transition-colors">
                            Xem hướng dẫn Admin
                            <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>

                    <!-- Counter Card -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-200 transition-all hover:border-orange-300 hover:shadow-md group">
                        <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-3 heading-font">Dành Cho Kiểm Phiếu Viên</h3>
                        <p class="text-slate-500 text-lg mb-6 leading-relaxed">
                            Giao diện nút bấm trực quan, phản hồi màu sắc tức thì. Nhập phiếu không cần gõ phím, hạn chế sai sót.
                        </p>
                        <a href="/docs/counter.html" class="inline-flex items-center text-orange-500 font-bold hover:text-orange-700 transition-colors">
                            Xem hướng dẫn Kiểm Phiếu
                            <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-6 text-center text-slate-500 text-sm mt-auto">
            <p>&copy; {{ date('Y') }} VoteCore. Giao diện tiêu chuẩn UI Pro Max Flat Design.</p>
        </footer>
    </body>
</html>
