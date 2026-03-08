<div class="max-w-7xl mx-auto pb-12">
    <!-- Messages -->
    @if(session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center">
            <div class="bg-emerald-100 p-2 rounded-xl mr-4 shrink-0">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-2xl text-emerald-800 font-semibold">{{ session('status') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center">
            <div class="bg-red-100 p-2 rounded-xl mr-4 shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-2xl text-red-800 font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 mb-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-6 border-b border-slate-200 space-y-4 md:space-y-0">
            <div>
                <p class="text-slate-500 font-medium tracking-wide uppercase text-sm mb-1">Phiên Kiểm Phiếu</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $election->title }}</h2>
            </div>
            <div class="flex space-x-3 w-full md:w-auto">
                <a href="{{ route('counting.dashboard') }}"
                   class="flex-1 md:flex-none inline-flex items-center justify-center px-6 py-3 border border-slate-300 text-lg font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Trở Lại Menu
                </a>
            </div>
        </div>

        @if(!$currentBallot)
            <!-- MÀN HÌNH KHỞI TẠO LÔ PHIẾU -->
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-full mb-6 text-blue-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-slate-900">Bắt Đầu Một Lô Kiểm Phiếu Mới</h3>
                    <p class="text-lg text-slate-500 mt-2">Vui lòng chọn chức vụ và số lượng phiếu hợp lệ trên tay</p>
                </div>

                <div class="space-y-8 bg-slate-50 p-8 rounded-2xl border border-slate-200">
                    <div>
                        <label class="block text-2xl font-bold text-slate-900 mb-3">
                            <span class="inline-block bg-blue-100 text-blue-700 w-8 h-8 text-center leading-8 rounded-lg mr-2">1</span> Chọn chức vụ
                        </label>
                        <select id="positionSelect" wire:model.live="selectedPositionId"
                                class="block w-full text-xl py-4 flex-1 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 bg-white transition-colors cursor-pointer">
                            <option value="">-- Bấm để chọn chức vụ --</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="expectedCount" class="block text-2xl font-bold text-slate-900 mb-3">
                            <span class="inline-block bg-blue-100 text-blue-700 w-8 h-8 text-center leading-8 rounded-lg mr-2">2</span> Số lượng phiếu cứng trên tay
                        </label>
                        <input type="number"
                               id="expectedCount"
                               wire:model.live="expectedCount"
                               min="1"
                               placeholder="Ví dụ: 10, 20..."
                               class="block w-full text-3xl py-4 px-5 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 text-slate-900 bg-white transition-colors font-bold">
                        @error('expectedCount')
                            <p class="text-red-600 text-lg mt-2 font-medium flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button wire:click="startBallot"
                                class="w-full flex justify-center items-center py-5 px-6 rounded-xl text-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-colors cursor-pointer group">
                            Bắt Đầu Nhập Phiếu
                            <svg class="ml-3 w-8 h-8 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- MÀN HÌNH ĐANG NHẬP PHIẾU -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                <!-- Cột Trái (Nhập Liệu + Thống Kê Tiến Độ) -->
                <div class="lg:col-span-7 space-y-8 flex flex-col h-full">

                    <!-- Progress Card -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8 flat-card-hover">
                        <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center justify-between">
                            <span>Chức vụ: {{ $currentBallot->position->title }}</span>
                        </h3>

                        <!-- Progress Bar (Lớn) -->
                        <div class="relative pt-1 mb-8">
                            <div class="flex items-center justify-between mb-3 text-2xl">
                                <div>
                                    <span class="font-bold text-blue-600">Đã nhập: {{ $currentBallot->entered_count }}</span>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-500">Tổng phiếu: {{ $currentBallot->expected_count }}</span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-6 text-xs flex rounded-full bg-slate-200 border border-slate-300">
                                <div style="width: {{ $currentBallot->expected_count > 0 ? min(100, ($currentBallot->entered_count / $currentBallot->expected_count * 100)) : 0 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 transition-all duration-500 ease-in-out"></div>
                            </div>

                            <!-- Cảnh báo Ngưỡng -->
                            @if($this->thresholdStatus)
                                <div class="mt-6 p-4 rounded-xl flex items-start {{ $this->thresholdStatus['within_threshold'] ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                                    @if($this->thresholdStatus['within_threshold'])
                                        <svg class="w-8 h-8 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-8 h-8 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    @endif
                                    <div>
                                        <p class="text-xl font-bold">
                                            {{ $this->thresholdStatus['within_threshold'] ? 'Tiến độ lô phiếu' : 'Cảnh báo lệch phiếu (Quá lớn)' }}
                                            ({{ $this->thresholdStatus['percentage'] }}%)
                                        </p>
                                        @if(!$this->thresholdStatus['within_threshold'])
                                            <p class="mt-1 text-base">{{ $this->thresholdStatus['message'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Khu vực nhập mã (Interactive Grid mode) -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 flex-1">
                        <label class="block text-2xl font-bold text-slate-900 mb-2">
                            Bảng Nhập Thông Minh:
                        </label>
                        <p class="text-slate-500 text-lg mb-6">Chạm vào ô số hoặc <strong class="text-slate-800">gõ phím số trên bàn phím</strong> tương ứng với lựa chọn trên lá phiếu thực tế.</p>

                        <!-- Candidate Grid -->
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-8">
                            @foreach($currentBallot->position->candidates->sortBy('sort_order') as $index => $candidate)
                                @php
                                    $number = $index + 1;
                                    $isSelected = in_array($number, $selectedCandidates);
                                @endphp
                                <button type="button"
                                        wire:click="toggleCandidate({{ $number }})"
                                        class="aspect-square flex flex-col items-center justify-center p-2 rounded-2xl border-2 transition-all cursor-pointer {{ $isSelected ? 'bg-blue-600 border-blue-600 text-white shadow-md transform scale-[1.03]' : 'bg-slate-50 border-slate-200 text-slate-600 hover:border-blue-300 hover:bg-slate-100' }}">
                                    <span class="text-4xl sm:text-5xl font-black">{{ $number }}</span>
                                    <span class="text-xs font-semibold mt-1 opacity-70">Phím {{ $number }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Error Message -->
                        @error('selectedCandidates')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-xl font-bold flex items-center mb-6">
                                <svg class="w-6 h-6 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </div>
                        @enderror

                        <!-- Submit Action -->
                        <div class="pt-4 border-t border-slate-100 flex flex-col gap-3 mt-2">
                            <button wire:click="submitBallot"
                                    class="w-full h-20 flex justify-center items-center px-6 border border-transparent rounded-2xl text-2xl sm:text-3xl font-black text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-500/50 cursor-pointer transition-all shadow-sm">
                                GHI NHẬN LÁ PHIẾU NÀY (Enter)
                            </button>

                            <!-- Báo Phiếu Không Hợp Lệ -->
                            <button wire:click="recordInvalidBallot"
                                    class="w-full h-16 flex justify-center items-center px-6 border border-red-200 rounded-2xl text-xl sm:text-2xl font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors cursor-pointer"
                                    title="Nhấp để ghi nhận lá phiếu bị gạch xóa sai quy định">
                                PHIẾU KHÔNG HỢP LỆ (Gạch xóa)
                            </button>
                        </div>
                    </div>

                    <!-- Lịch sử nhập gần đây -->
                    @if(count($recentEntries) > 0)
                        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8">
                            <h4 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Lịch Sử Nhập
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 text-slate-500 text-sm uppercase">
                                            <th class="py-3 px-2 font-medium w-16">STT</th>
                                            <th class="py-3 px-2 font-medium">Kết quả</th>
                                            <th class="py-3 px-2 font-medium w-16 text-center">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentEntries as $index => $entry)
                                            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50">
                                                <td class="py-3 px-2 text-lg text-slate-600 font-bold whitespace-nowrap">{{ $entry['count'] }}.</td>
                                                <td class="py-3 px-2 text-2xl font-black text-slate-900 tracking-wider">
                                                    {{ str_replace(',', ' -', $entry['input']) }}
                                                </td>
                                                <td class="py-3 px-2 text-center">
                                                    <button type="button"
                                                            x-data
                                                            @click="$dispatch('open-undo-modal', { index: {{ $index }}, count: {{ $entry['count'] }} })"
                                                            class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                            title="Xóa phiếu này">
                                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Khóa Lô Phiếu -->
                    @if($currentBallot->entered_count > 0)
                        <div class="pt-4 mt-auto">
                            <button x-data @click="$dispatch('open-modal', 'confirm-finalize')"
                                    {{ $this->thresholdStatus && !$this->thresholdStatus['within_threshold'] ? 'disabled' : '' }}
                                    class="w-full flex justify-center items-center py-6 px-6 rounded-2xl text-3xl font-black text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 transition-all disabled:bg-slate-300 disabled:text-slate-500 disabled:border-slate-300 disabled:cursor-not-allowed uppercase tracking-wider">
                                <svg class="w-10 h-10 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Khóa & Nộp Hệ Thống
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Cột Phải (Ứng Viên hoặc Bảng KQ) -->
                <div class="lg:col-span-5 flex flex-col space-y-6">
                    <!-- Controls -->
                    <div class="flex space-x-4">
                        <button wire:click="toggleResults"
                                class="flex-1 flex justify-center items-center py-4 px-4 border border-slate-300 text-xl font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-colors">
                            <svg class="w-6 h-6 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            {{ $showResults ? 'Ẩn Kết Quả' : 'Mở Bảng Tạm Tính' }}
                        </button>
                        <button x-data @click="$dispatch('open-modal', 'confirm-cancel')"
                                class="flex-1 flex justify-center items-center py-4 px-4 border border-red-200 text-xl font-bold rounded-xl text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-4 focus:ring-red-500/20 transition-colors">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hủy Bỏ
                        </button>
                    </div>

                    @if($showResults)
                        <div class="bg-indigo-50 rounded-2xl border border-indigo-200 p-6 sm:p-8 flex-1">
                            <h4 class="text-2xl font-bold text-indigo-900 mb-6 flex items-center border-b border-indigo-200 pb-4">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Thống Kê Tạm Tính
                            </h4>
                            <livewire:counting.results-display :ballot="$currentBallot" />
                        </div>
                    @else
                        <!-- Danh Sách Ứng Viên để đối chiếu -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 sm:p-8 flex-1 flex flex-col">
                            <h4 class="text-2xl font-bold text-slate-800 mb-6 flex items-center border-b border-slate-200 pb-4">
                                <svg class="w-8 h-8 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Bảng Mã Định Danh
                            </h4>
                            <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                                @forelse($currentBallot->position->candidates->sortBy('sort_order') as $index => $candidate)
                                    @php
                                        // Thẻ ứng viên màu pastel nhạt tương phản tốt
                                        $hex = ltrim($currentBallot->position->ballot_color ?? '#e2e8f0', '#');
                                        if (strlen($hex) == 3) { $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2); }
                                        $r = hexdec(substr($hex,0,2)); $g = hexdec(substr($hex,2,2)); $b = hexdec(substr($hex,4,2));
                                        $bgStyle = "background-color: rgba($r, $g, $b, 0.08); border-color: rgba($r, $g, $b, 0.2);";
                                    @endphp
                                    <div class="flex items-center p-4 border-2 rounded-xl transition-colors hover:bg-white" style="{{ $bgStyle }}">
                                        <div class="w-14 h-14 bg-white rounded-lg shadow-sm font-black text-3xl flex items-center justify-center mr-5 border border-slate-200 text-slate-900 shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="text-2xl font-bold text-slate-900 min-w-0 break-words">
                                            {{ $candidate->name }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-slate-500 italic mt-10">
                                        Chưa có ứng viên nào cho chức vụ này.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal: Xác nhận Xóa Lô Phiếu -->
    <div x-data="{ show: false, name: 'confirm-cancel' }"
         x-show="show"
         x-on:open-modal.window="show = ($event.detail === name)"
         x-on:close-modal.window="show = false"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;"
         wire:ignore.self>

        <!-- Background backdrop -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="show = false"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-extrabold leading-6 text-slate-900 mb-4" id="modal-title">Xác Nhận Hủy Lô Phiếu</h3>
                    <div class="mt-2 text-left">
                        <p class="text-lg text-slate-600 mb-2">Bạn có chắc chắn muốn <span class="text-red-600 font-bold uppercase">Xóa Lô Phiếu</span> này không?</p>
                        <p class="text-base text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-200">Tất cả dữ liệu phiếu bạn vừa nhập trong lô hiện tại sẽ bị xóa sạch khỏi hệ thống và không thể khôi phục.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200">
                    <button type="button"
                            @click="$wire.cancelBallot(); show = false"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-6 py-4 text-xl font-bold text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto transition-colors">
                        Đồng Ý Hủy Bỏ
                    </button>
                    <button type="button"
                            @click="show = false"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-4 text-xl font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                        Đóng Lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Xác nhận Chốt Sổ Lô Phiếu -->
    <div x-data="{ show: false, name: 'confirm-finalize' }"
         x-show="show"
         x-on:open-modal.window="show = ($event.detail === name)"
         x-on:close-modal.window="show = false"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;"
         wire:ignore.self>

        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="show = false"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 mb-6">
                        <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-extrabold leading-6 text-slate-900 mb-4" id="modal-title">Chốt Sổ Lô Lập Tức</h3>
                    <div class="mt-2 text-left">
                        <p class="text-lg text-slate-600 mb-2">Bạn có chắc chắn muốn <span class="text-emerald-600 font-bold uppercase">Khóa Lô Phiếu Này</span> và báo cáo lên hệ thống?</p>
                        <p class="text-base text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-200">Sau khi chốt, dữ liệu sẽ được lưu cố định. Xin kiểm tra kỹ trước khi Nộp Hệ Thống.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200">
                    <button type="button"
                            @click="$wire.finalizeBallot(); show = false"
                            class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-6 py-4 text-xl font-bold text-white shadow-sm hover:bg-emerald-700 sm:ml-3 sm:w-auto transition-colors">
                        Khóa & Nộp Hệ Thống
                    </button>
                    <button type="button"
                            @click="show = false"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-4 text-xl font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                        Quay Lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Xác nhận Undo Xóa Phiếu Lịch Sử -->
    <div x-data="{ show: false, index: null, count: null }"
         x-show="show"
         x-on:open-undo-modal.window="show = true; index = $event.detail.index; count = $event.detail.count"
         x-on:close-modal.window="show = false"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;"
         wire:ignore.self>

        <!-- Background backdrop -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="show = false"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-6">
                        <svg class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-extrabold leading-6 text-slate-900 mb-4" id="modal-title">Xác Nhận Xóa Phiếu</h3>
                    <div class="mt-2 text-left">
                        <p class="text-lg text-slate-600 mb-2">Bạn có chắc chắn muốn <span class="text-red-600 font-bold uppercase">XÓA phiếu thứ <span x-text="count"></span></span> này không?</p>
                        <p class="text-base text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-200">Dữ liệu của phiếu này sẽ bị xóa khỏi hệ thống và tổng số phiếu đã kiểm đếm sẽ tự động giảm đi 1 đơn vị.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200">
                    <button type="button"
                            @click="$wire.undoEntry(index); show = false"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-6 py-4 text-xl font-bold text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto transition-colors">
                        Đồng Ý Xóa
                    </button>
                    <button type="button"
                            @click="show = false"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-4 text-xl font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                        Đóng Lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Keyboard shortcuts: chỉ tồn tại khi đang kiểm phiếu --}}
    @if($currentBallot)
        <div x-data
             @keydown.window="
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes($event.target.tagName)) return;
                if ($event.key === 'Enter') { $event.preventDefault(); $wire.submitBallot(); return; }
                const num = parseInt($event.key);
                if (!isNaN(num) && num >= 1 && num <= 9) { $wire.toggleCandidate(num); }
             "
             class="hidden"></div>
    @endif
</div>
