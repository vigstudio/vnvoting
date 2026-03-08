<div class="max-w-7xl mx-auto pb-12">
    <!-- Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center">
            <div class="bg-emerald-100 p-2 rounded-xl mr-4 shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-xl text-emerald-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3 hidden sm:flex">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                Quản lý Cuộc Bầu Cử
            </h1>
            <p class="text-slate-500 mt-2 text-lg">Thiết lập và theo dõi các tiến trình bầu cử.</p>
        </div>
        <a href="{{ route('admin.elections.create') }}"
           class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/50 font-semibold text-lg transition-colors">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tạo Mới
        </a>
    </div>

    <!-- Cột Tìm kiếm -->
    <div class="mb-6 relative max-w-lg">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text"
               wire:model.live="search"
               placeholder="Tìm kiếm cuộc bầu cử..."
               class="w-full pl-12 pr-4 py-4 text-lg border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 focus:outline-none transition-colors bg-white">
    </div>

    <!-- Bảng Dữ Liệu -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                        <th class="px-6 py-5 font-semibold text-sm uppercase tracking-wider">Tên Cuộc Bầu Cử</th>
                        <th class="px-6 py-5 font-semibold text-sm uppercase tracking-wider text-center">Số Cấp</th>
                        <th class="px-6 py-5 font-semibold text-sm uppercase tracking-wider text-center">Trạng Thái</th>
                        <th class="px-6 py-5 font-semibold text-sm uppercase tracking-wider text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($elections as $election)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="font-bold text-xl text-slate-900 mb-1">{{ $election->title }}</div>
                                <div class="text-slate-500 line-clamp-1">{{ $election->description }}</div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold border border-slate-200">
                                    {{ $election->positions_count }} cấp
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($election->is_active)
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Hoạt Động
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Kết Thúc
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2 transition-opacity">
                                    <a href="{{ route('admin.elections.dashboard', $election) }}"
                                       class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Thống Kê
                                    </a>
                                    <a href="{{ route('admin.elections.edit', $election) }}"
                                       class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 hover:text-blue-600 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Chỉnh sửa
                                    </a>
                                    <button wire:click="delete({{ $election->id }})"
                                            wire:confirm="Bạn có chắc chắn muốn xóa cuộc bầu cử này không? Mọi dữ liệu đi kèm sẽ bị mất."
                                            class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-200 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">Trống Dữ Liệu</h3>
                                <p class="text-slate-500">Bạn chưa tạo cuộc bầu cử nào, hoặc không có kết quả phù hợp với từ khóa.</p>
                                <a href="{{ route('admin.elections.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium">
                                    Tạo cuộc bầu cử ngay &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($elections->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $elections->links() }}
            </div>
        @endif
    </div>
</div>
