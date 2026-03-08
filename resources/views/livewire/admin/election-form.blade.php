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

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3 hidden sm:flex">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                {{ $election ? 'Cấu Hình Bầu Cử' : 'Tạo Cuộc Bầu Cử' }}
            </h1>
            <p class="text-slate-500 mt-2 text-lg">Thông tin cơ bản để hệ thống nhận diện trên Dashboard.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            @if($election)
                <a href="{{ route('admin.elections.export.excel', $election) }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-emerald-200 text-sm font-bold rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 focus:outline-none transition-colors">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Xuất Excel
                </a>
                <a href="{{ route('admin.elections.export.pdf', $election) }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-red-200 text-sm font-bold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none transition-colors">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Biên bản PDF
                </a>
            @endif
            <a href="{{ route('admin.elections.index') }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-bold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Trở về
            </a>
        </div>
    </div>

    <!-- Form -->
    <form wire:submit="save">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 mb-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                <div class="md:col-span-12">
                    <label for="title" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">
                        Tên Gọi Chính Thức <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="title"
                           wire:model="title"
                           class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors font-bold text-xl"
                           placeholder="Ví dụ: Đại hội cổ đông 2026">
                    @error('title')
                        <p class="text-red-600 text-sm mt-2 font-medium flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-12">
                    <label for="description" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">
                        Mô Tả & Ghi Chú
                    </label>
                    <textarea id="description"
                              wire:model="description"
                              rows="3"
                              class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-900 transition-colors resize-y text-lg"
                              placeholder="Thông tin thêm giới thiệu cuộc bầu cử hoặc nội quy..."></textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-2 font-medium flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-6">
                    <label for="starts_at" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">
                        Thời gian Khai Mạc <span class="text-slate-400 font-normal normal-case">(Không bắt buộc)</span>
                    </label>
                    <input type="date"
                           id="starts_at"
                           wire:model="starts_at"
                           class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-700 transition-colors text-lg">
                    @error('starts_at')
                        <p class="text-red-600 text-sm mt-2 font-medium flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-6">
                    <label for="ends_at" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">
                        Thời gian Bế Mạc <span class="text-slate-400 font-normal normal-case">(Không bắt buộc)</span>
                    </label>
                    <input type="date"
                           id="ends_at"
                           wire:model="ends_at"
                           class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-slate-700 transition-colors text-lg">
                    @error('ends_at')
                        <p class="text-red-600 text-sm mt-2 font-medium flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-12 mt-4">
                    <label class="relative flex items-center cursor-pointer p-4 border-2 border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center h-6 mr-3">
                            <input type="checkbox"
                                   wire:model.live="is_active"
                                   class="w-6 h-6 text-blue-600 border-slate-300 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-colors rounded">
                        </div>
                        <div class="text-lg">
                            <span class="font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Cho Phép Kiểm Đếm (Hoạt Động Mở)</span>
                            <p class="text-slate-500 text-sm">Hiển thị ở khu vực Dashboard Kiểm Phiếu</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200">
                <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-blue-600 text-white text-xl font-bold rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/50 transition-colors cursor-pointer group">
                    <svg class="w-6 h-6 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Lưu Thông Tin Sơ Bộ
                </button>
            </div>
        </div>
    </form>

    @if($election && $election->exists)
        <div class="pt-6 border-t border-slate-200 mt-8">
            <livewire:admin.position-manager :election="$election" />
        </div>
    @endif
</div>
