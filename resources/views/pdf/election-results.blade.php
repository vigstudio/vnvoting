<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả bầu cử - {{ $election->title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .header p {
            font-size: 12px;
            margin: 5px 0;
            color: #666;
        }
        .position-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .position-header {
            background-color: #f0f0f0;
            padding: 10px;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            border-left: 4px solid {{ $positionsData[0]['position']->ballot_color ?? '#000' }};
        }
        .position-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            text-align: center;
            color: #999;
        }
        @page {
            margin: 20mm;
            footer {
                font-size: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO KẾT QUẢ BẦU CỬ</h1>
        <h2>{{ $election->title }}</h2>
        <p>Ngày xuất: {{ now()->format('d/m/Y H:i') }}</p>
        @if($election->starts_at)
            <p>Thời gian bầu cử: {{ $election->starts_at->format('d/m/Y') }}
                @if($election->ends_at) - {{ $election->ends_at->format('d/m/Y') }} @endif
            </p>
        @endif
    </div>

    @foreach($positionsData as $data)
        <div class="position-section">
            <div class="position-header" style="border-left-color: {{ $data['position']->ballot_color }}">
                {{ $data['position']->title }}
            </div>
            <div class="position-info">
                Màu phiếu: <span style="display: inline-block; width: 20px; height: 20px; background-color: {{ $data['position']->ballot_color }}; border: 1px solid #ccc; vertical-align: middle;"></span>
                | Số ứng viên: {{ $data['position']->candidates()->count() }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">STT</th>
                        <th style="width: 60%;">Tên ứng viên</th>
                        <th style="width: 15%;">Số phiếu</th>
                        <th style="width: 15%;">Tỷ lệ (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['results'] as $result)
                        <tr>
                            <td>{{ $result['candidate_number'] }}</td>
                            <td>{{ $result['name'] }}</td>
                            <td>{{ $result['vote_count'] }}</td>
                            <td>{{ $result['percentage'] }}%</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">TỔNG CỘNG</td>
                        <td>{{ $data['total_votes'] }}</td>
                        <td>100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <p>Hệ thống Kiểm đếm Phiếu Bầu Cử</p>
        <p>Ngày xuất báo cáo: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
