# Database Schema

## Tổng quan

Hệ thống sử dụng cơ sở dữ liệu quan hệ, với các bảng chính để quản lý cuộc bầu cử và quá trình kiểm phiếu.

## Cấu trúc Bảng (Tables)

### `users`

- Lưu thông tin tài khoản đăng nhập và phân quyền.
- **Cột quan trọng**: `role` (enum: 'admin', 'vote_counter').

### `elections` (Cuộc Bầu Cử)

- **Cột quan trọng**: `title` (Tên), `description`, `start_date`, `end_date`, `status` (active/completed).
- **Quan hệ**: 1 Election có nhiều Positions.

### `positions` (Chức Vụ / Cấp Bầu Cử)

- **Cột quan trọng**: `election_id`, `title` (Bí thư, Chủ tịch...), `ballot_color` (Mã màu phiếu), `max_votes` (Số ứng viên tối đa được chọn trên 1 phiếu), `sort_order`.
- **Quan hệ**: 1 Position có nhiều Candidates và nhiều Ballots.

### `candidates` (Ứng Viên)

- **Cột quan trọng**: `position_id`, `name`, `sort_order` (Số thứ tự ứng viên trên phiếu).
- **Quan hệ**: 1 Candidate thuộc 1 Position, có nhiều Votes.

### `ballots` (Lô Sinh Phiếu / Block Nhập)

- Đại diện cho một block/phiếu bầu được nhập vào bởi thư ký, hoặc đại diện cho 1 xấp phiếu.
- **Cột quan trọng**: `position_id`, `expected_count` (Số lượng phiếu thực tế của xấp), `entered_count` (Số phiếu đã nhập trong hệ thống), `is_complete` (Đã chốt lô phiếu chưa).
- **Quan hệ**: 1 Ballot có nhiều Votes.

### `votes` (Phiếu Bầu Thực Tế Bóc Tách)

- Lưu từng lượt chọn của cử tri đối với ứng viên.
- **Cột quan trọng**: `ballot_id`, `candidate_id`.
- **Quan hệ**: Gắn kết Ballot và Candidate.
