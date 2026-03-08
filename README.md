# Hệ thống Kiểm đếm Phiếu Bầu Cử

Hệ thống hỗ trợ kiểm đếm phiếu bầu cử tại địa phương, được thiết kế đơn giản để người lớn tuổi dễ sử dụng.

## Tính năng

- 🗳️ **Quản lý Cuộc Bầu Cử**: Tạo và quản lý nhiều cuộc bầu cử
- 📋 **Cấp Chức Vụ Linh Hoạt**: Tạo unlimited số cấp chức vụ (Bí thư, Chủ tịch, Phó Chủ tịch, v.v.)
- 🎨 **Màu Phiếu Riêng Biệt**: Mỗi cấp chức vụ có màu phiếu riêng để dễ phân biệt
- 👥 **Quản Lý Ứng Viên**: Thêm/sửa/xóa ứng viên với số thứ tự
- ✍️ **Kiểm Phiếu Nhanh**: Nhập nhanh theo số thứ tự (1,2,3) và nhấn Enter
- 📊 **Kết Quả Trực Tiếp**: Xem kết quả theo thời gian thực
- ✅ **Kiểm Tra Ngưỡng**: Tự động so sánh số phiếu nhập vs thực tế (50-150%)
- 📥 **Xuất Báo Cáo**: Export Excel (multi-sheet) và PDF

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (hoặc MySQL/PostgreSQL)

## Cài đặt

```bash
# Clone repository
git clone <repo-url>
cd vnvoting

# Cài đặt dependencies
composer install
npm install

# Copy file .env
cp .env.example .env
php artisan key:generate

# Tạo database và migrate
touch database/database.sqlite
php artisan migrate

# Tạo user admin
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);
```

## Chạy ứng dụng

```bash
# Development (chạy 4 terminal)
composer run dev

# Hoặc chạy riêng lẻ:
php artisan serve
npm run dev
php artisan queue:listen
php artisan pail
```

## Sử dụng

### 🗳️ VoteCore - Hệ Thống Quản Lý Bầu Cử & Kiểm Phiếu Chuyên Nghiệp

![VoteCore Architecture](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat&logo=laravel) ![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9.svg?style=flat&logo=livewire) ![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-38B2AC.svg?style=flat&logo=tailwind-css)

**VoteCore** là một ứng dụng nền tảng web mã nguồn mở được thiết kế để số hóa và tối ưu hóa quá trình kiểm đếm phiếu bầu thủ công. Với giao diện **SaaS Command Center** chuẩn mực, tĩnh lặng và tập trung vào trải nghiệm người dùng (UX), hệ thống loại bỏ những sai sót thường gặp khi đếm phiếu bằng tay, đồng thời cung cấp báo cáo và phân tích dữ liệu trực tiếp theo thời gian thực.

---

## ✨ Các Tính Năng Nổi Bật

- **Giao diện Flat Design Hiện Đại:** Thiết kế chuẩn "SaaS Pro Max" với hệ thống thẻ bài (Cards), đường viền thanh thoát, phối màu Slate/Blue chuyên nghiệp giúp giảm mỏi mắt cho người kiểm phiếu trong thời gian dài.
- **Tốc Độ Nhập Liệu Tối Đa:** Giao diện nhập phiếu tương tác trực tiếp qua ma trận nút bấm (Grid Buttons). Lượt chọn phản hồi tức thì (Instant feedback) kết hợp phím tắt Alpine.js giúp bạn đếm phiếu kể cả khi chỉ dùng bàn phím rảnh tay.
- **Cơ Chế Theo Dõi "Lô Phiếu" (Block Voting):** Khai báo trước số lượng tờ phiếu cứng có trên tay trước khi đếm. Hệ thống tự động cảnh báo sai lệch, thiếu hụt hoặc vượt mức, đảm bảo tỷ lệ kiểm chuẩn tới 100%.
- **Hoàn Tác Lịch Sử (Undo/History):** Khung lịch sử nhúng thời gian thực giúp đối soát các lá phiếu vừa thả xuống. Ấn nhầm? Có ngay nút "Hoàn tác" để hệ thống tự động trừ lùi điểm.
- **Xử Lý Phiếu Không Hợp Lệ:** Nút bấm riêng biệt để ghi nhận các lá phiếu gạch xóa, tẩy xóa sai quy chế, bóc tách dữ liệu tuyệt đối với phiếu Hợp Lệ.
- **Báo Cáo Tức Thì (Real-time Analytics):** Bảng tổng sắp Dashboard Admin tự động tính toán tổng số phiếu Phát Ra, Hợp Lệ, Không Hợp Lệ, tỷ lệ `%` chiếm đóng cho mỗi ứng viên mà không cần nếm xuất Excel.

---

## 👥 Phân Quyền & Vai Trò

Hệ thống hoạt động với hai nhóm tài khoản chính:

### 1. 🛡️ Ban Tổ Chức (Quản Trị Viên / Admin)

- Là người thiết lập và giám sát chiến dịch bầu cử.
- Khởi tạo chức vụ cần bầu (Chủ tịch, PT. Giám đốc, v.v.)
- Thêm mã định danh, màu sắc riêng biệt và thông tin ứng cử viên.
- Sở hữu "Trung Tâm Điều Khiển" (Dashboard) với đồ thị phân bổ tỷ lệ phần trăm phiếu bầu hợp lệ của toàn bộ ứng viên.
- Giám sát tiến độ đếm phiếu và hiệu suất của từng nhân viên cấp dưới trực tiếp trên bảng theo dõi.

### 2. 📝 Ban Kiểm Phiếu (Vote Counter)

- Là lực lượng nòng cốt thực hiện công tác nhập liệu từ những lá phiếu giấy.
- Giao diện được tối giản hóa tối đa, triệt tiêu mọi menu cấu hình không liên quan.
- Chọn một "Chiến dịch", bắt đầu "Lô phiếu" và liên tục bấm (hoặc gõ số) mã ứng viên để ghi nhận.
- Sở hữu bảng "Báo Cáo Của Tôi" (My Reports) để đối soát số lượng Lô và Số phiếu mình đã chịu trách nhiệm đếm trong ngày.

---

## 🚀 Hướng Dẫn Cài Đặt (Dành Cho Lập Trình Viên)

Hệ thống được xây dựng trên ngôn ngữ **PHP 8.3**, Framwork **Laravel 11 / 12**, **Livewire 3** và CSDL **SQLite** (Mặc định, có thể đổi sang MySQL/PostgreSQL).

```bash
# 1. Clone hoặc tải Repository về máy tính
git clone https://github.com/your-org/vnvoting.git
cd vnvoting

# 2. Cài đặt các gói phụ thuộc PHP và Node.js
composer install
npm install

# 3. Thiết lập biến môi trường
cp .env.example .env
php artisan key:generate

# 4. Migrate database và tạo dữ liệu (User Admin mặc định)
php artisan migrate:fresh --seed

# 5. Build mã nguồn Frontend (Tailwind/Vite)
npm run build

# 6. Khởi chạy Server
php artisan serve
```

---

## 📖 Hướng Dẫn Sử Dụng Chi Tiết

### Dành Cho Quản Trị Viên (Admin)

1. **Đăng nhập:** Truy cập hệ thống và dùng tài khoản gán quyền Admin.
2. **Khởi tạo Chiến Dịch:** Nhấn nút **"Quản lý Bầu cử"** ở ngay màn hình chính. Bấm **"Tạo Cuộc Bầu Cử Mới"**.
3. **Thêm Chức Vụ:** Trong giao diện cấu hình cuộc bầu cử, thêm các chức vụ đang tranh cử. Hãy nhớ chọn một mã Nhãn (Color Tag) để dễ nhận diện.
4. **Thêm Ứng Viên:** Chuyển qua tab "Danh sách ứng viên", nhập tên và đính kèm vào chức vụ tương ứng. Hệ thống sẽ cấp một SBD (Số Báo Danh).
5. Sau khi công tác kiểm phiếu kết thúc, ra ngay trang Home chọn **"Dashboard Tổng Hợp"** để xem Tỷ lệ % cuối.

### Dành Cho Ban Kiểm Phiếu (Counter)

1. Cầm trên tay một tệp phiếu cứng đã được phát (VD: Một tệp được kẹp sẵn 50 phiếu).
2. Tại hệ thống, chọn **"Phòng Kiểm Phiếu"**.
3. Bấm **"Khởi tạo Lô Phiếu Mới"**, chọn chức vụ bạn đang đếm và gõ số **50** vào ô _Số lượng thực tế trên tay_.
4. Sử dụng bàn phím điện thoại/máy tính hoặc Chuột để bấm vào các ô Mã số Ứng Viên tương ứng với các dấu tích trên tờ biên lai.
    - _Ghi chú:_ Nếu lá phiếu đó bị gạch xóa sai luật, hãy bấm vào nút màu đỏ nổi bật **"PHIẾU KHÔNG HỢP LỆ"**.
5. Sau khi bấm đủ các nhãn ứng viên cho một lá, bấm **"Ghi Nhận Lá Phiếu Này"** (Phím tắt: `Enter`).
6. Khi nhập hết 50 phiếu, nếu quá trình nhập không có lỗi, thanh màu xanh lục sẽ báo hiệu Thành Công. Bấm **"Khóa Lô"** để hệ thống lưu dữ liệu và đưa cho Quản Trị Viên.

## Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Livewire 4, Tailwind CSS v4, Alpine.js
- **Database**: SQLite
- **Testing**: Pest 4
- **Export**: Laravel Excel (maatwebsite/excel), DomPDF

## License

MIT
