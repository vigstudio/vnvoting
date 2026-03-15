<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo cá nhân - {{ $election->title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 22px;
            margin: 0 0 5px 0;
            color: #1e40af;
        }
        .header h2 {
            font-size: 18px;
            margin: 0 0 10px 0;
            font-weight: normal;
            color: #334155;
        }
        .header .meta {
            font-size: 11px;
            color: #64748b;
        }
        .position-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .position-header {
            background-color: #f1f5f9;
            padding: 10px 14px;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 12px;
            border-left: 4px solid #3b82f6;
            color: #0f172a;
        }
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .stat-item {
            display: table-cell;
            padding: 8px 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .stat-item .label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
        }
        .stat-item.valid .value { color: #059669; }
        .stat-item.invalid .value { color: #dc2626; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #e2e8f0;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: #475569;
        }
        td { font-size: 13px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .total-row {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .ballot-section {
            margin-top: 15px;
        }
        .ballot-section h4 {
            font-size: 13px;
            color: #475569;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            font-size: 10px;
            text-align: center;
            color: #94a3b8;
        }
        @page { margin: 18mm; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO KIỂM ĐẾM PHIẾU CÁ NHÂN</h1>
        <h2>{{ $election->title }}</h2>
        <div class="meta">
            Kiểm phiếu viên: <strong>{{ $userName }}</strong> |
            Ngày xuất: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    @foreach($positionsData as $data)
        <div class="position-section">
            <div class="position-header">
                {{ $data['position']->title }}
            </div>

            <div class="stats-row">
                <div class="stat-item">
                    <div class="label">Số lô</div>
                    <div class="value">{{ $data['total_ballots_blocks'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="label">Phiếu phát ra</div>
                    <div class="value">{{ number_format($data['total_expected']) }}</div>
                </div>
                <div class="stat-item valid">
                    <div class="label">Hợp lệ</div>
                    <div class="value">{{ number_format($data['total_valid']) }}</div>
                </div>
                <div class="stat-item invalid">
                    <div class="label">Không hợp lệ</div>
                    <div class="value">{{ number_format($data['total_invalid']) }}</div>
                </div>
            </div>

            @if($data['candidates']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%;">STT</th>
                            <th style="width: 52%;">Tên ứng viên</th>
                            <th style="width: 20%;" class="text-center">Số phiếu</th>
                            <th style="width: 20%;" class="text-center">Tỷ lệ (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['candidates'] as $candidate)
                            <tr>
                                <td class="text-center bold">{{ $candidate->sort_order + 1 }}</td>
                                <td>{{ $candidate->name }}</td>
                                <td class="text-center bold">{{ $candidate->total_votes }}</td>
                                <td class="text-center">{{ $candidate->percentage }}%</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="2">TỔNG PHIẾU HỢP LỆ</td>
                            <td class="text-center">{{ $data['total_valid'] }}</td>
                            <td class="text-center">100%</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @if($data['ballots']->count() > 0)
                <div class="ballot-section">
                    <h4>Chi tiết từng lô phiếu</h4>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 8%;">Lô #</th>
                                <th class="text-center">Phát ra</th>
                                <th class="text-center">Đã nhập</th>
                                <th class="text-center">Không hợp lệ</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['ballots'] as $index => $ballot)
                                <tr>
                                    <td class="text-center bold">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $ballot->expected_count }}</td>
                                    <td class="text-center">{{ $ballot->entered_count }}</td>
                                    <td class="text-center">{{ $ballot->invalid_count }}</td>
                                    <td>{{ $ballot->counted_at?->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <p>Hệ thống Kiểm đếm Phiếu Bầu Cử | Báo cáo tự động xuất lúc {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
