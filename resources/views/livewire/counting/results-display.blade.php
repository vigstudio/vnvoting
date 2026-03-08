<div>
    <h4 class="text-2xl font-bold text-gray-900 mb-6">KẾT QUẢ PHIẾU</h4>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xl font-bold text-gray-700">STT</th>
                    <th class="px-6 py-4 text-left text-xl font-bold text-gray-700">Ứng Viên</th>
                    <th class="px-6 py-4 text-center text-xl font-bold text-gray-700">Số Phiếu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($results as $result)
                    <tr class="text-lg">
                        <td class="px-6 py-4 font-bold text-2xl">{{ $result['candidate_number'] }}</td>
                        <td class="px-6 py-4">{{ $result['name'] }}</td>
                        <td class="px-6 py-4 text-center font-bold text-2xl {{ $result['vote_count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-xl">
                            Chưa có phiếu nào được nhập.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 p-4 bg-blue-50 rounded-xl border-2 border-blue-300">
        <p class="text-xl text-blue-900">
            📊 Tổng số phiếu đã nhập: <strong class="text-2xl">{{ $ballot->entered_count }}</strong>
        </p>
    </div>
</div>
