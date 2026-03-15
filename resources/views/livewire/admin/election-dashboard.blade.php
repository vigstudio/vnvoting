<div class="max-w-7xl mx-auto pb-10" wire:poll.300s>
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Dashboard Tổng Hợp</h1>
            <p class="text-slate-500 mt-2 text-lg">Thống kê toàn diện cho cuộc bầu cử: <strong class="text-slate-800">{{ $election->title }}</strong></p>
        </div>
        <a href="{{ route('admin.elections.index') }}"
           class="inline-flex items-center px-5 py-3 bg-slate-600 text-white font-bold rounded-xl hover:bg-slate-700 transition-colors text-base">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Quản Lý Bầu Cử
        </a>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-6 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Dự Kiến</span>
            </div>
            <div class="text-3xl font-extrabold text-amber-600">{{ number_format($this->overview['total_expected']) }}</div>
            <p class="text-xs text-slate-400 mt-1">Phiếu khai báo</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Phiếu Nhập</span>
            </div>
            <div class="text-3xl font-extrabold text-blue-600">{{ number_format($this->overview['total_entered']) }}</div>
            <p class="text-xs text-slate-400 mt-1">Tổng đã nhập</p>
        </div>

        <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-white text-emerald-600 rounded-xl shadow-sm flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide">Hợp Lệ</span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600">{{ number_format($this->overview['total_valid']) }}</div>
            <p class="text-xs text-emerald-600/70 mt-1">Phiếu tính cho ƯV</p>
        </div>

        <div class="bg-red-50 rounded-2xl border border-red-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-white text-red-600 rounded-xl shadow-sm flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <span class="text-xs font-bold text-red-700 uppercase tracking-wide">Không Hợp Lệ</span>
            </div>
            <div class="text-3xl font-extrabold text-red-600">{{ number_format($this->overview['total_invalid']) }}</div>
            <p class="text-xs text-red-600/70 mt-1">Phiếu gạch xóa</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Lô Phiếu</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ $this->overview['total_ballots'] }}</div>
            <p class="text-xs text-slate-400 mt-1">Đã hoàn thành</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Kiểm Phiếu Viên</span>
            </div>
            <div class="text-3xl font-extrabold text-violet-600">{{ $this->overview['total_counters'] }}</div>
            <p class="text-xs text-slate-400 mt-1">Người tham gia</p>
        </div>
    </div>

    <!-- Kết quả theo từng chức vụ -->
    @foreach($this->positionResults as $result)
        <div class="bg-white rounded-2xl border border-slate-200 mb-8 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ $result['position']->title }}</h2>
                        <div class="text-slate-500 mt-1 text-sm font-medium">Đã hoàn thành <strong class="text-slate-800">{{ $result['ballot_count'] }}</strong> Lô phiếu</div>
                    </div>

                    <div class="flex flex-wrap gap-2 text-sm font-semibold">
                        <span class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl border border-slate-200 shadow-sm flex items-center">
                            Tổng phát ra: <span class="ml-2 text-lg font-bold text-slate-900">{{ number_format($result['total_expected']) }}</span>
                        </span>
                        <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm flex items-center">
                            Tổng hợp lệ: <span class="ml-2 text-lg font-bold text-emerald-600">{{ number_format($result['total_valid']) }}</span>
                        </span>
                        <span class="px-4 py-2 bg-red-50 text-red-700 rounded-xl border border-red-200 shadow-sm flex items-center">
                            Tổng không hợp lệ: <span class="ml-2 text-lg font-bold text-red-600">{{ number_format($result['total_invalid']) }}</span>
                        </span>
                    </div>
                </div>
            </div>

            @if($result['candidates']->count() > 0)
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-700 mb-4">Chi Tiết Từng Ứng Viên - Xếp Hạng Từ Cao Đến Thấp</h3>
                    <div class="space-y-4">
                        @foreach($result['candidates'] as $index => $candidate)
                            @php
                                $isTop = $index === 0;
                                $barColor = $isTop ? 'bg-emerald-500' : 'bg-blue-500';
                                $bgColor = $isTop ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-slate-200 hover:border-blue-300';
                            @endphp
                            <div class="rounded-xl border p-4 {{ $bgColor }} transition-all shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 md:w-10 md:h-10 {{ $isTop ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-600' }} rounded-lg flex items-center justify-center font-bold text-sm md:text-base">
                                            #{{ $index + 1 }}
                                        </div>
                                        <span class="font-bold text-lg md:text-xl text-slate-900">{{ $candidate->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 md:gap-4">
                                        <div class="text-right">
                                            <span class="text-2xl md:text-3xl font-extrabold {{ $isTop ? 'text-emerald-600' : 'text-blue-600' }}">{{ number_format($candidate->total_votes) }}</span>
                                            <span class="text-sm font-medium text-slate-500 hidden md:inline">/ {{ number_format($result['total_valid']) }} Hợp lệ</span>
                                        </div>
                                        <div class="px-3 py-1 rounded-lg {{ $isTop ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-50 text-blue-700' }} font-black text-sm md:text-base">
                                            {{ $candidate->percentage }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-200/60 rounded-full h-3 md:h-4 overflow-hidden shadow-inner">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $candidate->percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="p-6 text-center text-slate-400 bg-slate-50 border-t border-slate-100">
                    <p class="py-10 text-lg">Chưa có dữ liệu phiếu bầu cho chức vụ này.</p>
                </div>
            @endif
        </div>
    @endforeach

    <!-- Thống kê Kiểm Phiếu Viên -->
    @if($this->counterStats->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                <h2 class="text-2xl font-bold text-slate-900">Thống Kê Theo Kiểm Phiếu Viên</h2>
                <p class="text-slate-500 mt-1">Tiến độ và hiệu suất của từng nhân viên kiểm phiếu.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/50">
                            <th class="px-6 py-4 text-sm font-bold text-slate-600 uppercase tracking-wide">Kiểm Phiếu Viên</th>
                            <th class="text-center px-6 py-4 text-sm font-bold text-slate-600 uppercase tracking-wide">Lô Đã Đếm</th>
                            <th class="text-center px-6 py-4 text-sm font-bold text-slate-600 uppercase tracking-wide">Tổng Phiếu</th>
                            <th class="text-center px-6 py-4 text-sm font-bold text-emerald-600 uppercase tracking-wide">Hợp Lệ</th>
                            <th class="text-center px-6 py-4 text-sm font-bold text-red-600 uppercase tracking-wide">Không Hợp Lệ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($this->counterStats as $stat)
                            @php
                                $valid = $stat->total_entered - $stat->total_invalid;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 text-slate-600 border border-slate-200 rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ $stat->user?->initials() ?? 'NV' }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $stat->user?->name ?? 'Nhân viên bị xóa' }}</div>
                                            <div class="text-xs text-slate-500">{{ $stat->user?->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 font-bold rounded-full">{{ $stat->total_ballots }}</span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700 text-lg">
                                    {{ number_format($stat->total_entered) }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-emerald-600 text-lg border-l border-slate-50">
                                    {{ number_format($valid) }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-red-600 text-lg border-l border-slate-50">
                                    {{ number_format($stat->total_invalid) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
