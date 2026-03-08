<div class="bg-white rounded-3xl border border-slate-200">
    <!-- Header -->
    <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50 rounded-t-3xl">
        <h2 class="text-2xl font-bold text-slate-900 flex items-center">
            <svg class="w-7 h-7 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Danh Sách Ứng Viên
        </h2>
        <a href="{{ route('admin.elections.edit', $position->election) }}"
           class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-colors">
            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay Lại
        </a>
    </div>

    <div class="p-8">
        <!-- Position Info Card -->
        @php
            $hex = ltrim($position->ballot_color ?? '#e2e8f0', '#');
            if (strlen($hex) == 3) { $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2); }
            $r = hexdec(substr($hex,0,2)); $g = hexdec(substr($hex,2,2)); $b = hexdec(substr($hex,4,2));
        @endphp
        <div class="mb-8 p-6 rounded-2xl flex items-center border"
             style="background-color: rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.05); border-color: rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.2);">
            <div class="w-4 h-12 rounded-full mr-4" style="background-color: {{ $position->ballot_color }}"></div>
            <div>
                <h3 class="text-xl font-bold text-slate-900 flex items-center mb-1">
                    {{ $position->title }}
                    <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        {{ $position->candidates->count() }} Ứng viên
                    </span>
                </h3>
                <p class="text-slate-500 text-sm">Mã màu thẻ: <code class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">{{ $position->ballot_color ?? '#e2e8f0' }}</code></p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center">
                <svg class="w-6 h-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-lg text-emerald-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Add/Edit -->
        <div class="mb-10 bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-100/50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    @if($editingCandidate)
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Sửa Thông Tin Ứng Viên
                    @else
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Thêm Ứng Viên Mới
                    @endif
                </h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-8">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Tên Úng Viên</label>
                        <input type="text"
                               wire:model="name"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors"
                               placeholder="VD: Nguyễn Văn A">
                        @error('name')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Thứ Tự (ID)</label>
                        <input type="number"
                               wire:model="sort_order"
                               min="0"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors bg-white">
                        @error('sort_order')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-12">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Thông Tin Thêm (Mô Tả)</label>
                        <textarea wire:model="description"
                                  rows="2"
                                  class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors resize-y"
                                  placeholder="Đơn vị công tác, chức vụ hiện tại..."></textarea>
                        @error('description')<p class="text-red-500 text-sm mt-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button wire:click="save"
                            class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/50 transition-colors">
                        @if($editingCandidate) Cập Nhật Lại @else Thêm Vào Danh Sách @endif
                    </button>
                    @if($editingCandidate)
                        <button wire:click="cancel"
                                class="inline-flex justify-center items-center px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                            Hủy Sửa
                        </button>
                     @endif
                </div>
            </div>
        </div>

        <!-- Danh sách -->
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">Danh Sách Ứng Viên Hiện Tại</h3>

        <div class="space-y-3">
            @forelse($candidates as $candidate)
                <div class="group flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-white hover:bg-slate-50 rounded-2xl border border-slate-200 transition-colors">
                    <div class="flex items-center space-x-4 mb-3 sm:mb-0 w-full sm:w-auto">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-xl border border-indigo-100 shrink-0">
                            {{ $candidate->sort_order + 1 }}
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 mb-0.5">{{ $candidate->name }}</h4>
                            @if($candidate->description)
                                <p class="text-sm text-slate-500 line-clamp-1">{{ $candidate->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-auto w-full sm:w-auto mt-2 sm:mt-0 pt-3 sm:pt-0 border-t border-slate-100 sm:border-0 justify-end transition-opacity">
                        <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden shrink-0 mr-2 bg-white">
                            <button wire:click="moveUp({{ $candidate->id }})" class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none" title="Di chuyển lên">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <div class="w-px h-5 bg-slate-200"></div>
                            <button wire:click="moveDown({{ $candidate->id }})" class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none" title="Di chuyển xuống">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                        <button wire:click="edit({{ $candidate->id }})"
                                class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 font-medium transition-colors border border-blue-200 text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Sửa
                        </button>
                        <button wire:click="delete({{ $candidate->id }})"
                                wire:confirm="Xóa ứng viên này?"
                                class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 font-medium transition-colors border border-red-200 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                    <div class="mx-auto w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-400 mb-3 border border-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">Chưa có ứng viên</h3>
                    <p class="text-slate-500">Hãy thêm các ứng viên đầu tiên cho chức vụ này.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
