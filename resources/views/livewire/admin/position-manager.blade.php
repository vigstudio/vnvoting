<div class="bg-white rounded-3xl border border-slate-200">
    <!-- Header -->
    <div class="px-8 py-6 border-b border-slate-200 bg-slate-50/50 rounded-t-3xl border-t-4 border-t-indigo-600">
        <h2 class="text-2xl font-bold text-slate-900 flex items-center">
            <svg class="w-7 h-7 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Cấu Hình Các Cấp Chức Vụ
        </h2>
        <p class="text-slate-500 mt-1 text-sm">Các phân vùng bỏ phiếu và màu sắc tương ứng</p>
    </div>

    <div class="p-8">
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center">
                <svg class="w-6 h-6 text-emerald-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-lg text-emerald-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Add/Edit -->
        <div class="mb-10 bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-100/50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    @if($editingPosition)
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Sửa Cấp Chức Vụ: <span class="ml-1 text-indigo-700">{{ $title }}</span>
                    @else
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Thêm Cấp Chức Vụ Mới
                    @endif
                </h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-12 lg:col-span-6">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Tên Chức Vụ / Cấp Bầu</label>
                        <input type="text"
                               wire:model="title"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors bg-white"
                               placeholder="VD: Chủ tịch Hội đồng, Bí thư...">
                        @error('title')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-6 lg:col-span-3">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Màu Phiếu Nhận Diện</label>
                        <div class="flex items-center space-x-3 bg-white border border-slate-300 rounded-xl p-1 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/20 transition-all">
                            <input type="color"
                                   wire:model="ballot_color"
                                   class="w-10 h-10 rounded cursor-pointer border-0 p-0 shrink-0 bg-transparent">
                            <input type="text"
                                   wire:model="ballot_color"
                                   class="flex-1 px-2 py-2 text-sm border-0 focus:ring-0 text-slate-900 font-mono uppercase bg-transparent"
                                   placeholder="#HEXCODE">
                        </div>
                        @error('ballot_color')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-3 lg:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Số Bầu Tối Đa</label>
                        <input type="number"
                               wire:model="max_votes"
                               min="1"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors bg-white"
                               placeholder="VD: 1">
                        @error('max_votes')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-3 lg:col-span-1">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Thứ Tự</label>
                        <input type="number"
                               wire:model="sort_order"
                               min="0"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors bg-white"
                               placeholder="0">
                        @error('sort_order')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button wire:click="save"
                            class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/50 transition-colors">
                        @if($editingPosition) Cập Nhật Lại @else Thêm Mới Chức Vụ @endif
                    </button>
                    @if($editingPosition)
                        <button wire:click="cancel"
                                class="inline-flex justify-center items-center px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                            Hủy Bỏ
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danh sách -->
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">Danh Sách Cấp Chức Vụ</h3>

        <div class="space-y-4">
            @forelse($positions as $position)
                @php
                    $hex = ltrim($position->ballot_color ?? '#e2e8f0', '#');
                    if (strlen($hex) == 3) { $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2); }
                    $r = hexdec(substr($hex,0,2)); $g = hexdec(substr($hex,2,2)); $b = hexdec(substr($hex,4,2));
                @endphp
                <div class="group relative flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 bg-white hover:bg-slate-50 rounded-2xl border transition-all"
                     style="border-color: rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.3);">

                    <!-- Color Line Indicator -->
                    <div class="absolute left-0 top-0 bottom-0 w-2 rounded-l-2xl" style="background-color: {{ $position->ballot_color }}"></div>

                    <div class="flex items-center space-x-4 pl-3 mb-4 sm:mb-0 w-full sm:w-auto">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-black text-xl border border-black/10 shrink-0"
                             style="background-color: {{ $position->ballot_color ?? '#94a3b8' }}">
                            {{ $position->sort_order + 1 }}
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 mb-0.5">{{ $position->title }}</h4>
                            <div class="flex items-center text-sm text-slate-500 flex-wrap gap-x-4 gap-y-1">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Được Bầu Tối Đa: <strong class="ml-1 text-slate-700">{{ $position->max_votes }}</strong>
                                </span>
                                <span class="flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-1.5 border border-slate-200" style="background-color: {{ $position->ballot_color }}"></span>
                                    <code class="font-mono text-xs text-slate-600 bg-slate-100 px-1 py-0.5 rounded">{{ strtoupper($position->ballot_color) }}</code>
                                </span>
                                <span class="text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded-full text-xs border border-indigo-100">
                                    {{ $position->candidates->count() }} Ứng Viên
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-auto w-full sm:w-auto mt-2 sm:mt-0 pt-4 sm:pt-0 border-t border-slate-100 sm:border-0 justify-end transition-opacity">
                        <a href="{{ route('admin.positions.edit', $position) }}"
                           class="inline-flex items-center px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 font-bold transition-colors border border-indigo-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Ds. Ứng Viên
                        </a>

                        <!-- Hover Actions for Edit/Delete -->
                        <div class="transition-opacity flex">
                            <button wire:click="edit({{ $position->id }})"
                                    class="p-2.5 bg-white text-slate-600 rounded-lg hover:bg-slate-100 hover:text-blue-600 transition-colors border border-slate-200 ml-2 focus:outline-none" title="Chỉnh sửa">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="delete({{ $position->id }})"
                                    wire:confirm="Xóa chức vụ này sẽ XÓA TOÀN BỘ Ứng Viên trực thuộc. Xác nhận?"
                                    class="p-2.5 bg-white text-red-500 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition-colors border border-slate-200 ml-2 focus:outline-none" title="Xóa toàn bộ">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                    <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-400 mb-4 border border-slate-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-medium text-slate-900 mb-2">Chưa thiết lập Cấp chức vụ / Bảng màu Phiếu</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">Tạo các cấp chức vụ và màu lá phiếu nhận diện để bắt đầu tiến hành thêm ứng viên vào cuộc bầu cử.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
