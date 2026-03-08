<div class="max-w-6xl mx-auto pb-10">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Danh Sách Cuộc Bầu Cử</h1>
        <p class="text-slate-500 mt-2 text-lg">Chọn một cuộc bầu cử đang diễn ra để bắt đầu tiến trình kiểm đếm phiếu của bạn.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start">
            <svg class="w-6 h-6 text-emerald-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-emerald-800 text-lg font-medium">{{ session('status') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start">
            <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-red-800 text-lg font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if($activeElections->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($activeElections as $election)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-blue-300 transition-all flex flex-col h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                            <span class="font-bold text-xl">{{ $loop->iteration }}</span>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full">Đang diễn ra</span>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 mb-2 line-clamp-2">{{ $election->title }}</h2>
                    <p class="text-slate-600 mb-6 flex-grow line-clamp-3">{{ $election->description ?? 'Không có mô tả thêm về cuộc bầu cử này.' }}</p>

                    <div class="flex items-center space-x-2 mb-6">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="text-slate-700 font-medium">{{ $election->positions->count() }} Cấp chức vụ</span>
                    </div>

                    <a href="{{ route('counting.entry', $election) }}"
                       class="w-full inline-flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        Mở Phòng Kiểm Đếm Phiếu
                    </a>
                    <a href="{{ route('counting.report', $election) }}"
                       class="mt-3 w-full inline-flex justify-center items-center py-3 px-4 border border-slate-200 rounded-xl text-base font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-blue-300 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Xem Báo Cáo Của Tôi
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-slate-50 border border-slate-200 rounded-3xl p-16 text-center max-w-3xl mx-auto mt-10">
            <div class="inline-flex justify-center items-center w-20 h-20 bg-slate-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Chưa có cuộc bầu cử nào</h3>
            <p class="text-lg text-slate-500">
                Hiện tại không có cuộc bầu cử nào đang ở trạng thái hoạt động trên hệ thống. <br>
                Vui lòng liên hệ Admin để kiểm tra lại cấu hình.
            </p>
        </div>
    @endif
</div>
