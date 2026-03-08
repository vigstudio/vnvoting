# System Overview

## Tổng quan dự án

Hệ thống Kiểm đếm Phiếu Bầu Cử là một ứng dụng nội bộ hỗ trợ quản lý và kiểm đếm kết quả bầu cử tại địa phương.
Ứng dụng được thiết kế ưu tiên cho người lớn tuổi với giao diện lớn, chữ to, thao tác bằng phím tắt đơn giản.

## Tech Stack

- **Backend**: Laravel 12.x, PHP 8.x
- **Frontend**: Livewire 4.x, Tailwind CSS v4, Alpine.js
- **Database**: SQLite (hoặc MySQL/PostgreSQL chuẩn của Laravel)
- **Testing**: Pest 4.x
- **Export**: Laravel Excel (maatwebsite/excel), DomPDF

## Kiến trúc tính năng (Modules)

1. **Authentication & Authorization**
    - Sử dụng role-based access control (Admin và Vote Counter).
    - Middleware kiểm tra quyền.

2. **Admin Management**
    - Quản lý cuộc bầu cử (Elections).
    - Quản lý cấp chức vụ (Positions) với màu phiếu tùy chỉnh để dễ phân loại.
    - Quản lý ứng viên (Candidates) theo số thứ tự hiển thị.

3. **Vote Counting (Kiểm đếm phiếu)**
    - UI thiết kế theo block (nhập số phiếu thực tế có trước, sau đó nhập dãy số ứng viên).
    - Validation Real-time: cảnh báo nếu phiếu nhập vượt quá +-50% so với số thực tế.
    - Hỗ trợ thao tác nhập nhanh qua bàn phím (Enter để nhập block, dãy số cách nhau dấu phẩy).

4. **Reporting & Export**
    - Xuất kết quả bầu cử theo định dạng Excel (multi-sheet cho từng chức vụ).
    - Xuất PDF kết quả nhanh.
    - Hiển thị Live Kết Quả trong lúc đang kiểm phiếu.
