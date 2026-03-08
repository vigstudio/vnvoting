@extends('components.layouts.app')

@section('title', 'Bảng điều khiển - VoteCore')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Trung tâm điều khiển</h1>
            <p class="text-slate-500">Chào mừng trở lại, <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100 uppercase tracking-wider">
                {{ auth()->user()->role ?? 'Thành viên' }}
            </span>
            <span class="text-sm text-slate-400 font-medium">{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $totalElections = \App\Models\Election::count();
            $activeElections = \App\Models\Election::where('is_active', true)->count();
            $totalBallots = \App\Models\Ballot::count();
            $completedBallots = \App\Models\Ballot::whereNotNull('counted_at')->count();
        @endphp

        <div class="bg-white p-5 rounded-2xl border border-slate-200 flat-card-hover transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tổng bầu cử</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ $totalElections }}</span>
                <span class="text-xs font-medium text-slate-500">cuộc</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 flat-card-hover transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đang chạy</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-emerald-600">{{ $activeElections }}</span>
                <span class="text-xs font-medium text-slate-500">hoạt động</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 flat-card-hover transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lô phiếu</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ $totalBallots }}</span>
                <span class="text-xs font-medium text-slate-500">tổng lô</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 flat-card-hover transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đã chốt sổ</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ $completedBallots }}</span>
                <span class="text-xs font-medium text-slate-500">đã nộp</span>
            </div>
        </div>
    </div>

    <!-- Main Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Actions (Hub) -->
        <div class="lg:col-span-1 space-y-4">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest pl-1">Truy cập nhanh</h3>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-4 bg-white border border-slate-200 rounded-2xl hover:border-blue-500 transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Quản trị viên</h4>
                    <p class="text-xs text-slate-500">Cấu hình & báo cáo</p>
                </div>
                <svg class="w-5 h-5 ml-auto text-slate-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            @endif

            <a href="{{ route('counting.dashboard') }}" class="flex items-center p-4 bg-white border border-slate-200 rounded-2xl hover:border-emerald-500 transition-all group">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Kiểm phiếu</h4>
                    <p class="text-xs text-slate-500">Nhập liệu & đối soát</p>
                </div>
                <svg class="w-5 h-5 ml-auto text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center p-4 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-red-50 hover:border-red-200 transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white text-slate-400 rounded-xl flex items-center justify-center mr-4 group-hover:text-red-500 transition-colors border border-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-600 group-hover:text-red-600">Đăng xuất</h4>
                        <p class="text-xs text-slate-400">Kết thúc phiên làm việc</p>
                    </div>
                </button>
            </form>
        </div>

        <!-- Latest Activity or Election Snapshot -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest pl-1">Cuộc bầu cử đang diễn ra</h3>

            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden">
                @php
                    $latestElections = \App\Models\Election::latest()->take(3)->get();
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tên cuộc bầu cử</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Trạng thái</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestElections as $election)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-900">{{ $election->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $election->starts_at?->format('d/m/Y') }} - {{ $election->ends_at?->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($election->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Đóng
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('counting.entry', $election) }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm">Vào đếm phiếu</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">Chưa có dữ liệu cuộc bầu cử nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
