# Business Rules & Logic

## 1. Xác thực và Phân quyền (Auth & Roles)

- Hệ thống chỉ có 2 roles: `admin` và `vote_counter`.
- **Admin**: Được quyền tạo/sửa đổi các thông tin hệ thống bầu cử, cấu hình, xuất báo cáo.
- **Vote Counter (Người kiểm phiếu)**: Chỉ được phép vào khu vực nhập phiếu và xuất File cho cuộc bầu cử hiện tại.

## 2. Quy tắc Kiểm đếm Phiếu (Vote Counting)

- **Block Phiếu**: Không nhập lẻ tẻ từng dòng. Người kiểm phiếu sẽ nhận 1 "xấp phiếu" (Block) và khai báo `expected_count` (ví dụ xấp này có 50 phiếu).
- **Nhập Dữ liệu**:
    - Nhập theo số thứ tự của ứng viên trên giấy (Ví dụ: `1,2,5`).
    - Hệ thống sẽ map số thứ tự đó vào ứng viên thực tế nằm trong Cấp chức vụ.
- **Threshold Validation (Ngưỡng cảnh báo)**:
    - Nếu số phiếu nhập (`entered_count`) nằm ngoài vùng từ **50% đến 150%** so với số lượng khai báo (`expected_count`), hệ thống sẽ hiện CẢNH BÁO.
    - Vẫn có thể lưu nhưng nút Submit chốt block bị disable nếu chưa thỏa điều kiện (Hoặc theo cấu hình chặt, phải đúng khoảng threshold mới cho hoàn thành).

## 3. Quy tắc Hiển thị (UI/UX)

- Do đối tượng sử dụng là Cán bộ/Người lớn tuổi:
    - Tất cả các text phải sử dụng cỡ chữ TO.
    - Label Input chữ cực lớn.
    - Ngôn ngữ: Tiếng Việt 100%, rõ nghĩa, không dùng thuật ngữ chuyên ngành IT.
    - Ưu tiên thao tác bằng Bàn phím: `Enter` nạp dãy phiếu, `Esc` xóa input.

## 4. Quy tắc Export Kết qủa

- **Excel**:
    - Gộp chung kết quả của 1 cuộc bầu cử.
    - Phân tách các Cấp Chức Vụ bằng các Sheet (Tab) riêng biệt trong 1 file.
- **PDF**:
    - Xuất ra 1 file báo cáo tổng hợp để in ra giấy ký biên bản ngay tại chỗ.
