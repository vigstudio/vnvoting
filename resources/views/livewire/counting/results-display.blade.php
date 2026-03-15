<div class="space-y-4" wire:poll.5s>
    @php
        $results = $this->results;
        $maxVotes = collect($results)->max('vote_count') ?: 1;
        $totalVotes = $ballot->entered_count;
    @endphp

    @forelse($results as $result)
        @php
            $percentage = $totalVotes > 0 ? round(($result['vote_count'] / $totalVotes) * 100, 1) : 0;
            $barWidth = $maxVotes > 0 ? round(($result['vote_count'] / $maxVotes) * 100) : 0;
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-indigo-300 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center font-black text-lg shrink-0">
                        {{ $result['candidate_number'] }}
                    </div>
                    <span class="font-bold text-slate-900 text-lg truncate">{{ $result['name'] }}</span>
                </div>
                <div class="flex items-baseline gap-1 shrink-0 ml-3">
                    <span class="text-3xl font-black text-indigo-600">{{ $result['vote_count'] }}</span>
                    <span class="text-sm font-semibold text-slate-400">phiếu</span>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
                <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $barWidth }}%"></div>
            </div>
            <div class="text-right mt-1">
                <span class="text-xs font-semibold text-slate-400">{{ $percentage }}%</span>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <p class="text-sm font-medium">Chưa có phiếu nào được nhập.</p>
        </div>
    @endforelse

    {{-- Tổng kết --}}
    <div class="flex items-center justify-between bg-indigo-100/50 rounded-xl p-4 border border-indigo-200">
        <span class="text-base font-bold text-indigo-800">Tổng phiếu đã nhập</span>
        <div class="flex items-baseline gap-1">
            <span class="text-2xl font-black text-indigo-700">{{ $totalVotes }}</span>
            <span class="text-sm font-semibold text-indigo-500">/ {{ $ballot->expected_count }}</span>
        </div>
    </div>
</div>
