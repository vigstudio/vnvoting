# Changelog

Tất cả các thay đổi đáng chú ý của dự án Hệ thống Kiểm Phiếu Bầu Cử sẽ được ghi chép tại đây.

## [1.0.0] - 2026-03-07

### Thêm Mới (Added)

- **Database & Authentication**: Khởi tạo Schema với các bảng (users, elections, positions, candidates, ballots, votes) và Role-based (admin, vote_counter).
- **Admin Dashboard**: Quản lý CRUD cuộc bầu cử, cấp chức vụ, ứng viên. Hỗ trợ chọn màu riêng cho từng loại phiếu.
- **Vote Counting Dashboard (Cực lớn)**: Giao diện nhập liệu thiết kế dành riêng cho người lớn tuổi. Chữ to, nút bấm lớn, tiếng Việt rõ ràng.
- **Vote Input Logic**: Phân tách luồng nhập phiếu theo block (xấp phiếu). Cảnh báo Threshold Validate mức chênh lệch 50% - 150%.
- **Keyboard Shortcuts**: Hỗ trợ thẻ phím Enter và Esc cho thao tác nhập phiếu nhanh.
- **Export System**: Export báo cáo đa định dạng. Excel (Multi-sheets cho từng Position), PDF (dùng DomPDF).
- **Testing Coverage**: Bao phủ Unit Tests / Feature Tests lớn (ExportTest, VoteCounterTest...) với Pest framework cho tỷ lệ an toàn cao.
