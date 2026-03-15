<div class="max-w-6xl mx-auto pb-10">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Báo Cáo Kiểm Phiếu Cá Nhân</h1>
            <p class="text-slate-500 mt-2 text-lg">Kết quả các lô phiếu bạn đã hoàn thành cho cuộc bầu cử: <strong class="text-slate-800">{{ $election->title }}</strong></p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('counting.export.my-pdf', $election) }}"
               class="inline-flex items-center px-5 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Xuất PDF
            </a>
            <a href="{{ route('counting.export.my-excel', $election) }}"
               class="inline-flex items-center px-5 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Xuất Excel
            </a>
            <a href="{{ route('counting.entry', $election) }}"
               class="inline-flex items-center px-5 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Quay Lại Kiểm Phiếu
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('status'))
        <div class="mb-8 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center shadow-sm">
            <svg class="w-6 h-6 mr-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium text-lg">{{ session('status') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center shadow-sm">
            <svg class="w-6 h-6 mr-3 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium text-lg">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tổng hợp theo chức vụ -->
    @foreach($this->mySummary as $item)
        <div class="bg-white rounded-2xl border border-slate-200 mb-8 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ $item['position']->title }}</h2>
                        <div class="text-slate-500 mt-1 text-sm font-medium">Đã hoàn thành <strong class="text-slate-800">{{ $item['total_ballots_blocks'] }}</strong> Lô phiếu</div>
                    </div>

                    <div class="flex flex-wrap gap-2 text-sm font-semibold">
                        <span class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl border border-slate-200 shadow-sm flex items-center">
                            Tổng phát ra: <span class="ml-2 text-lg font-bold text-slate-900">{{ $item['total_expected'] }}</span>
                        </span>
                        <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm flex items-center">
                            Hợp lệ: <span class="ml-2 text-lg font-bold text-emerald-600">{{ $item['total_valid'] }}</span>
                        </span>
                        <span class="px-4 py-2 bg-red-50 text-red-700 rounded-xl border border-red-200 shadow-sm flex items-center">
                            Khoảng hợp lệ (Gạch Xóa): <span class="ml-2 text-lg font-bold text-red-600">{{ $item['total_invalid'] }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tổng hợp Ứng viên --}}
            @if($item['candidates']->count() > 0)
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-700 mb-4">Chi Tiết Từng Ứng Viên (Trên Tổng Phiếu Hợp Lệ)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($item['candidates'] as $candidate)
                            <div class="bg-gradient-to-br from-slate-50 to-white rounded-xl border border-slate-200 p-5 text-center hover:border-blue-300 hover:shadow-md transition-all">
                                <div class="text-sm font-bold text-slate-700 truncate mb-3" title="{{ $candidate->name }}">{{ $candidate->name }}</div>

                                <div class="flex items-end justify-center gap-1 mb-2">
                                    <span class="text-4xl font-extrabold text-blue-600">{{ $candidate->total_votes }}</span>
                                    <span class="text-slate-500 font-semibold mb-1">/ {{ $item['total_valid'] }}</span>
                                </div>
                                <div class="text-xs text-slate-400 mb-3 uppercase tracking-wider font-semibold">Phiếu Bầu</div>

                                <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $candidate->percentage }}%"></div>
                                </div>
                                <div class="text-sm font-bold {{ $candidate->percentage > 50 ? 'text-emerald-600' : 'text-blue-600' }}">
                                    Chiếm {{ $candidate->percentage }}%
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="p-6 text-center text-slate-400">
                    <p>Chưa có dữ liệu kiểm phiếu hợp lệ cho chức vụ này.</p>
                </div>
            @endif
        </div>
    @endforeach

    <!-- Chi tiết từng Lô Phiếu -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h2 class="text-2xl font-bold text-slate-900">Chi Tiết Từng Lô Phiếu</h2>
            <p class="text-slate-500 mt-1">Lịch sử đầy đủ các lô bạn đã kiểm đếm và nộp lên hệ thống. Bấm vào từng lô để xem và sửa từng phiếu.</p>
        </div>

        @if($this->myBallots->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($this->myBallots as $index => $ballot)
                    <div wire:key="ballot-{{ $ballot->id }}" class="transition-colors">
                        {{-- Ballot Header --}}
                        <div class="p-6 hover:bg-slate-50/50 cursor-pointer" wire:click="viewEntries({{ $ballot->id }})">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                        <span class="font-bold text-xl">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg text-slate-900">
                                            Lô #{{ $index + 1 }} — {{ $ballot->position->title }}
                                        </h3>
                                        <p class="text-sm text-slate-500 mt-0.5">
                                            Hoàn thành: {{ $ballot->counted_at->format('d/m/Y H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-semibold rounded-full">
                                        {{ $ballot->entered_count }}/{{ $ballot->expected_count }} phiếu
                                    </span>
                                    @if($ballot->isWithinThreshold())
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-semibold rounded-full">Đạt</span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 font-semibold rounded-full">Lệch</span>
                                    @endif

                                    {{-- View Entries Button --}}
                                    <button type="button"
                                            wire:click.stop="viewEntries({{ $ballot->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all
                                                {{ $viewingBallotId === $ballot->id
                                                    ? 'bg-blue-600 text-white shadow-sm'
                                                    : 'text-blue-600 hover:bg-blue-50 border border-blue-200 hover:border-blue-300' }}"
                                            title="Xem lịch sử nhập phiếu">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        {{ $viewingBallotId === $ballot->id ? 'Đóng' : 'Xem phiếu' }}
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button"
                                            x-data
                                            @click.stop="$dispatch('open-delete-modal', { id: {{ $ballot->id }}, name: 'Lô #{{ $index + 1 }} — {{ addslashes($ballot->position->title) }}' })"
                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg transition-colors border border-transparent hover:border-red-200"
                                            title="Xóa Lô Phiếu này">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Vote breakdown for this ballot --}}
                            @php
                                $voteGroups = $ballot->votes->where('is_invalid', false)->groupBy('candidate_id');
                            @endphp
                            @if($voteGroups->count() > 0)
                                <div class="ml-16 flex flex-wrap gap-3">
                                    @foreach($ballot->position->candidates as $candidate)
                                        @php $count = $voteGroups->get($candidate->id)?->count() ?? 0; @endphp
                                        <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium
                                            {{ $count > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-50 text-slate-400 border border-slate-100' }}">
                                            {{ $candidate->name }}: <span class="font-bold ml-1">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Entry History Panel --}}
                        @if($viewingBallotId === $ballot->id)
                            <div class="border-t border-blue-100 bg-gradient-to-b from-blue-50/50 to-white">
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Lịch Sử Nhập Phiếu — Lô #{{ $index + 1 }}
                                        </h4>
                                        <span class="text-sm text-slate-500">
                                            {{ count($this->ballotEntries) }} phiếu
                                        </span>
                                    </div>

                                    @if(count($this->ballotEntries) > 0)
                                        <div class="space-y-2">
                                            @foreach($this->ballotEntries as $entry)
                                                <div wire:key="entry-{{ $ballot->id }}-{{ $entry['entry_number'] }}"
                                                     class="rounded-xl border transition-all
                                                        {{ $entry['is_invalid']
                                                            ? 'bg-red-50/50 border-red-200'
                                                            : 'bg-white border-slate-200 hover:border-blue-200' }}
                                                        {{ $editingBallotId === $ballot->id && $editingEntryNumber === $entry['entry_number']
                                                            ? 'ring-2 ring-blue-400 border-blue-400'
                                                            : '' }}">

                                                    {{-- Entry display mode --}}
                                                    @if(!($editingBallotId === $ballot->id && $editingEntryNumber === $entry['entry_number']))
                                                        <div class="flex items-center justify-between px-4 py-3">
                                                            <div class="flex items-center gap-3">
                                                                {{-- Entry number badge --}}
                                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold shrink-0
                                                                    {{ $entry['is_invalid']
                                                                        ? 'bg-red-100 text-red-600'
                                                                        : 'bg-blue-100 text-blue-600' }}">
                                                                    {{ $entry['entry_number'] }}
                                                                </div>

                                                                {{-- Content --}}
                                                                @if($entry['is_invalid'])
                                                                    <div class="flex items-center gap-2">
                                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                        <span class="text-sm font-semibold text-red-600">Phiếu không hợp lệ</span>
                                                                    </div>
                                                                @else
                                                                    <div class="flex flex-wrap gap-1.5">
                                                                        @foreach($entry['candidates'] as $candidate)
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">
                                                                                {{ $candidate['sort_order'] + 1 }}. {{ $candidate['name'] }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                {{-- Time --}}
                                                                @if($entry['created_at'])
                                                                    <span class="text-xs text-slate-400 ml-2">{{ $entry['created_at'] }}</span>
                                                                @endif
                                                            </div>

                                                            {{-- Edit button --}}
                                                            <button type="button"
                                                                    wire:click="startEdit({{ $ballot->id }}, {{ $entry['entry_number'] }})"
                                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 hover:border-amber-300 transition-all"
                                                                    title="Sửa phiếu này">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                                Sửa
                                                            </button>
                                                        </div>

                                                    {{-- Entry edit mode --}}
                                                    @else
                                                        <div class="px-4 py-4">
                                                            <div class="flex items-center gap-2 mb-3">
                                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold shrink-0 bg-amber-100 text-amber-600">
                                                                    {{ $entry['entry_number'] }}
                                                                </div>
                                                                <span class="text-sm font-bold text-amber-700">Đang chỉnh sửa phiếu #{{ $entry['entry_number'] }}</span>
                                                            </div>

                                                            {{-- Invalid toggle --}}
                                                            <div class="mb-3">
                                                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                                                    <input type="checkbox"
                                                                           wire:click="toggleEditInvalid"
                                                                           {{ $editIsInvalid ? 'checked' : '' }}
                                                                           class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500">
                                                                    <span class="text-sm font-medium {{ $editIsInvalid ? 'text-red-600' : 'text-slate-600' }}">
                                                                        Phiếu không hợp lệ
                                                                    </span>
                                                                </label>
                                                            </div>

                                                            {{-- Candidate selection (disabled if invalid) --}}
                                                            @if(!$editIsInvalid)
                                                                <div class="flex flex-wrap gap-2 mb-3">
                                                                    @foreach($ballot->position->candidates->sortBy('sort_order') as $candidateIdx => $candidate)
                                                                        @php
                                                                            $candidateNumber = $candidateIdx + 1;
                                                                            $isSelected = in_array($candidateNumber, $editSelectedCandidates);
                                                                        @endphp
                                                                        <button type="button"
                                                                                wire:click="toggleEditCandidate({{ $candidateNumber }})"
                                                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold border-2 transition-all
                                                                                    {{ $isSelected
                                                                                        ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                                                                        : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300 hover:bg-blue-50' }}">
                                                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold
                                                                                {{ $isSelected ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                                                                                {{ $candidateNumber }}
                                                                            </span>
                                                                            {{ $candidate->name }}
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            {{-- Error message --}}
                                                            @error('editSelectedCandidates')
                                                                <div class="mb-2 px-3 py-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium flex items-center gap-2">
                                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror

                                                            {{-- Action buttons --}}
                                                            <div class="flex items-center gap-2">
                                                                <button type="button"
                                                                        wire:click="saveEdit"
                                                                        wire:loading.attr="disabled"
                                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    <span wire:loading.remove wire:target="saveEdit">Lưu</span>
                                                                    <span wire:loading wire:target="saveEdit">Đang lưu...</span>
                                                                </button>
                                                                <button type="button"
                                                                        wire:click="cancelEdit"
                                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-50 transition-colors border border-slate-300">
                                                                    Hủy
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-slate-400">
                                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-sm">Không tìm thấy lịch sử nhập phiếu cho lô này.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-16 text-center">
                <div class="inline-flex justify-center items-center w-20 h-20 bg-slate-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Chưa có lô phiếu nào</h3>
                <p class="text-slate-500">Bạn chưa hoàn thành lô kiểm phiếu nào cho cuộc bầu cử này. Hãy bắt đầu kiểm đếm!</p>
            </div>
        @endif
    </div>

    <!-- Modal: Xác nhận Xóa Lô Phiếu -->
    <div x-data="{ show: false, ballotId: null, ballotName: '' }"
         x-show="show"
         x-on:open-delete-modal.window="show = true; ballotId = $event.detail.id; ballotName = $event.detail.name"
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
                    <h3 class="text-3xl font-extrabold leading-6 text-slate-900 mb-4" id="modal-title">Xác Nhận Xóa Lô Phiếu</h3>
                    <div class="mt-2 text-left">
                        <p class="text-lg text-slate-600 mb-2">Bạn có chắc chắn muốn <span class="text-red-600 font-bold uppercase">XÓA TOÀN BỘ</span> dữ liệu của <strong class="text-slate-900" x-text="ballotName"></strong> không?</p>
                        <p class="text-base text-slate-500 bg-red-50 p-4 rounded-xl border border-red-100 text-red-700"><strong>CẢNH BÁO:</strong> Hành động này không thể hoàn tác. Mọi lá phiếu trong Lô này sẽ bị xóa khỏi hệ thống. Bạn sẽ phải nhập lại từ đầu.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 gap-3">
                    <button type="button"
                            @click="$wire.deleteBallot(ballotId); show = false"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-6 py-4 text-xl font-bold text-white shadow-sm hover:bg-red-700 sm:w-auto transition-colors">
                        Đồng Ý Xóa Lô Phiếu
                    </button>
                    <button type="button"
                            @click="show = false"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-4 text-xl font-bold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
