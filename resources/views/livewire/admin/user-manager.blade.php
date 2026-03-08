<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-slate-800">Quản Lý Nhân Sự</h1>
            <p class="text-slate-500 text-sm mt-1">Quản trị danh sách nhân viên kiểm phiếu và phân quyền truy cập hệ thống.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm kiếm tên, email..."
                    class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none w-full md:w-64 transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors flex items-center gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Thêm Nhân Sự
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Họ và Tên</th>
                        <th class="px-6 py-4">Tài Khoản (Email)</th>
                        <th class="px-6 py-4">Vai Trò</th>
                        <th class="px-6 py-4 rounded-tr-lg">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold whitespace-nowrap">Chỉ Huy (Admin)</span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold whitespace-nowrap">Bàn Kiểm Phiếu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->id !== auth()->id())
                                    <button
                                        wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Bạn có chắc chắn muốn xóa nhân viên này khỏi hệ thống?"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors whitespace-nowrap">
                                        Hủy kích hoạt
                                    </button>
                                @else
                                    <span class="text-slate-400 text-xs italic">Đang đăng nhập</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Không tìm thấy nhân sự nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create User Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        <!-- Modal Panel -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-[500px] p-8 sm:p-10 overflow-hidden transform transition-all text-left">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-slate-800 font-heading">Thêm Nhân Sự Mới</h3>
                <p class="text-sm text-slate-500 mt-2">Cấp tài khoản truy cập hệ thống kiểm phiếu.</p>
            </div>

            <form wire:submit.prevent="saveUser" class="space-y-6">

                <!-- Input: Name -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Họ Tên / Tên Bàn Cổng</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all outline-none"
                        placeholder="VD: Bàn Đếm Số 2">
                    @error('name') <span class="text-red-500 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Input: Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tài khoản Email</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all outline-none"
                        placeholder="VD: ban2@vnvoting.test">
                    @error('email') <span class="text-red-500 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Input: Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mật khẩu truy cập</label>
                    <input type="text" wire:model="password"
                        class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all outline-none"
                        placeholder="Nhập ít nhất 6 ký tự">
                    @error('password') <span class="text-red-500 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Input: Role Selection -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Vai trò hệ thống</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative group">
                            <input type="radio" wire:model="role" value="vote_counter" id="role_counter" class="peer sr-only">
                            <label for="role_counter" class="flex flex-col h-full cursor-pointer rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50/50 transition-all">
                                <span class="block text-sm font-bold text-slate-900 peer-checked:text-blue-700">Người Kiểm Phiếu</span>
                                <span class="block text-xs text-slate-500 mt-1">Chỉ đọc và đếm phiếu</span>
                            </label>
                        </div>

                        <div class="relative group">
                            <input type="radio" wire:model="role" value="admin" id="role_admin" class="peer sr-only">
                            <label for="role_admin" class="flex flex-col h-full cursor-pointer rounded-xl border-2 border-slate-200 bg-white p-4 hover:border-slate-300 peer-checked:border-slate-800 peer-checked:bg-slate-50 transition-all">
                                <span class="block text-sm font-bold text-slate-900 peer-checked:text-slate-900">Quản Trị Viên</span>
                                <span class="block text-xs text-slate-500 mt-1">Toàn quyền hệ thống</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" wire:click="closeModal"
                        class="px-6 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 transition-all">
                        Hủy Bỏ
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all shadow-sm shadow-blue-600/20">
                        Xác Nhận Tạo
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
