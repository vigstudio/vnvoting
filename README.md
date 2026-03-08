# VnVoting: Enterprise Election Management Platform

**VnVoting** là nền tảng quản trị và kiểm đếm phiếu bầu cử, được thiết kế để đáp ứng các tiêu chuẩn khắt khe nhất về tính toàn vẹn dữ liệu, kiểm soát truy cập phân quyền và khả năng xử lý số lượng phiếu bầu lớn (High-throughput ballot entry).

Hệ thống cung cấp giải pháp chuyển đổi số toàn diện cho các tổ chức, hiệp hội và cơ quan ban ngành, thay thế quy trình kiểm phiếu thủ công bằng công nghệ tự động hóa, đảm bảo tính minh bạch và độ chính xác tuyệt đối.

## Tính năng Cốt lõi (Core Features)

- � **Phân Quyền Định Danh (RBAC):** Hệ thống phân quyền chặt chẽ giữa Quản Trị Viên (Admin) và Nhân Viên Kiểm Phiếu (Counters), đảm bảo tính cô lập dữ liệu.
- 🗳️ **Quản Trị Nhiều Kỳ Bầu Cử Đồng Thời:** Khả năng thiết lập và chạy song song nhiều chiến dịch bầu cử với cấu trúc tổ chức phức tạp.
- 🏢 **Cấu Trúc Chức Vụ Linh Hoạt:** Không giới hạn số lượng chức vụ cấp cao (Bí thư, Chủ tịch, Ban Thường vụ...). Hỗ trợ phân loại màu sắc phiếu bầu thông minh.
- ⚡ **Giao Diện Nhập Liệu Tốc Độ Cao:** Bảng điều khiển (Entry Grid) tối ưu hóa bằng Alpine.js cho phép nhân viên nhập liệu liên tục thông qua phím tắt, chuột và màn hình cảm ứng, loại bỏ bottleneck truyền thống.
- 🛡️ **Kiểm Soát Tính Hợp Lệ (Ballot Integrity):** Tự động phát hiện và ngăn chặn nhập trùng, chặn phiếu vượt định mức (Overvoting), đồng thời theo dõi sát sao tỷ lệ Cấp Phát / Thu Hồi và Phiếu Không Hợp Lệ.
- � **Real-time Analytics Dashboard:** Trung tâm điều khiển cung cấp dữ liệu trực tiếp về tiến độ kiểm phiếu, biểu đồ phân bổ phần trăm và bảng xếp hạng ứng viên theo thời gian thực.
- 📑 **Truy Xuất Báo Cáo Chuyên Sâu:** Tự động tổng hợp và xuất báo cáo kết quả chi tiết dưới định dạng Excel (Multi-sheet) phục vụ lưu trữ văn thư.

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

### 🗳️ VnVoting - Nền Tảng Quản Trị Bầu Cử Đám Mây

![VnVoting Architecture](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat&logo=laravel) ![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9.svg?style=flat&logo=livewire) ![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-38B2AC.svg?style=flat&logo=tailwind-css)

**VnVoting** không chỉ là phần mềm đếm phiếu, đây là một hệ sinh thái toàn diện được tối ưu hóa cho tốc độ, độ tin cậy và sự minh bạch trong môi trường bầu cử quy mô lớn.

Hệ thống được thiết kế theo tiêu chuẩn UI/UX "SaaS Command Center", loại bỏ hoàn toàn sự lộn xộn, tối đa hóa không gian thao tác và tự động đối soát chéo (Cross-verification) ngay tại thời điểm nhập liệu.

---

## ✨ Cấu Trúc Vận Hành (Operational Features)

- **Giao Diện Phẳng Chuyên Nghiệp (Enterprise Flat Design):** Áp dụng ngôn ngữ thiết kế tối giản, loại bỏ shadow/gradient thừa, sử dụng Typography tỷ lệ vàng để duy trì sự tập trung tuyệt đối cho giao dịch viên trong suốt hàng giờ kiểm đếm liên tục.
- **Tốc Độ Nhập Liệu Cực Đại (High-Speed Entry Grid):** Chuyển đổi từ mô hình gõ text truyền thống sang Ma Trận Nút Bấm (Grid Mode). Tương tác với độ trễ 0ms (Mô hình Optimistic UI) thông qua nhấp chuột, màn hình cảm ứng, hoặc hoàn toàn bằng phím tắt (Keyboard-first).
- **Cơ Chế Theo Dõi Lô Phiếu Kép (Dual-Layer Batch Tracking):** Hệ thống yêu cầu khai báo khối lượng phần cứng (số phiếu giấy có trên tay) trước khi bắt đầu. Mọi chênh lệch (dư/thiếu) so với số liệu điện tử đều kích hoạt chuông cảnh báo (Validation Alert) khẩn cấp.
- **Kiểm Soát Tính Hợp Lệ Chuyên Sâu:** Tách biệt hoàn toàn luồng xử lý phiếu Hợp Lệ và Không Hợp Lệ.
- **Theo Dõi Vệt Kiểm Toán (Audit Trail - Lịch Sử Lô Phiếu):** Cửa sổ History View cung cấp lịch sử các lá phiếu vừa thả xuống theo thời gian thực. Giao dịch viên hoàn toàn làm chủ được "nút Hoàn Tác (Undo)" tại cấp độ Micro-action mà không làm hỏng tính toàn vẹn của cả Lô báo cáo.

---

## 👥 Quản Trị Vai Trò Cấp Cao (Role-Based Access Control)

VnVoting áp dụng mô hình phân quyền (RBAC) chặt chẽ giữa quản lý và vận hành trực tiếp.

### 1. 🛡️ Quản Trị Viên Hệ Thống (Administrator)

- **Quyền Lực:** Khởi tạo, tùy biến và thiết lập và giám sát chiến dịch tính trên không gian đa miền (Multi-Election Setup).
- **Cấu hình động:** Định nghĩa chức vụ ban bệ không giới hạn số lượng, thiết lập hệ thống định danh ứng cử viên.
- **Giám Sát Tức Thời:** Theo dõi sự kiện từ Master Dashboard, xem trực tiếp các biểu đồ tiến độ phiếu Phát ra / Thu vào / Không hợp lệ. Dữ liệu thay đổi live theo từng cú click của cấp dưới.
- **Truy xuất dữ liệu:** Xuất Báo cáo chuẩn hóa hệ thống (Standardized Reports) định dạng CSV/Excel.

### 2. 📝 Chuyên Viên Kiểm Đếm (Vote Counter)

- **Trọng tâm (Focus):** Truy cập vào "Phòng Kiếm Phiếu Điện Tử" được tối ưu trên mọi màn hình. Không giao diện dư thừa.
- **Tiến trình an toàn:** Luồng thao tác tuyến tính: _Chọn Chiến Dịch -> Mở Lô Mới -> Khai báo Tổng Phiếu -> Nhập Dữ Liệu -> Xác Nhận Chốt Lô._
- **My Analytics:** Khu vực "Báo Cáo Của Tôi" theo dõi cụ thể số lượng, mức sai số mà cá nhân người dùng thao tác trong ca làm việc, đảm bảo tự đối soát (Self-audit) trước khi bàn giao.

---

## 🚀 Triển Khai Hạ Tầng (Deployment Guide)

Được xây dựng phục vụ triển khai On-Premise (Nội bộ) hoặc Cloud Server, VnVoting yêu cầu ngăn xếp **PHP 8.3**, **Laravel 12.x**, **Livewire 4.x** và tối ưu với đa định dạng cơ sở dữ liệu (**MySQL/PostgreSQL/SQLite**).

```bash
# 1. Triển khai Source Code
git clone https://github.com/your-org/vnvoting.git
cd vnvoting

# 2. Cấu hình Dependencies
composer install --no-dev --optimize-autoloader
npm install

# 3. Khởi tạo Môi trường (Environment)
cp .env.example .env
php artisan key:generate

# LƯU Ý: Cấu hình DB\_CONNECTION=mysql trong file .env trước bước 4.

# 4. Di trú CSDL và thiết lập Tài khoản gốc (Root Accounts)
php artisan migrate:fresh --seed

# 5. Biên dịch Tài nguyên Front-End (Vite/Tailwind v4)
npm run build

# 6. Public/Serve Ứng dụng
php artisan serve
```

> **Ghi chú bảo mật:** Tài khoản cấp phát mặc định bao gồm `admin@vnvoting.test` và `counter@vnvoting.test` (chung mật khẩu: **password**). Quý tổ chức bắt buộc phải đổi mật khẩu và vô hiệu hóa các tài khoản giả lập này trong bản Production.

---

## 📖 Tài Liệu Hướng Dẫn Vận Hành (Operations Manual)

Vui lòng tham khảo bộ tài liệu HTML tĩnh đi kèm hoặc được hosting cục bộ sau khi triển khai hệ thống:

- **Tài liệu Quản Trị Hệ Thống (Admin Manual):** `public/docs/admin.html`
- **Tài liệu Chuyên Viên Kiểm Phiếu (Counter Manual):** `public/docs/counter.html`

_Mọi thao tác chi tiết từng nút bấm, giải thích luồng xử lý và khôi phục lỗi được ghi chép cụ thể trong tài liệu trên._

## Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Livewire 4, Tailwind CSS v4, Alpine.js
- **Database**: SQLite
- **Testing**: Pest 4
- **Export**: Laravel Excel (maatwebsite/excel), DomPDF

## License

MIT
