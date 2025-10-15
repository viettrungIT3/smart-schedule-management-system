## 1. 

## 2. Mô tả Dự án Ban đầu (Initial Project Description)

Sử dụng tên **ScheduleFlow** (Smart Schedule Management System).

### **Tên Dự án:** ScheduleFlow (Smart Schedule Management System - SSMS)

### **Tóm tắt (Abstract)**

**ScheduleFlow** là một hệ thống quản lý thời gian biểu dựa trên nền tảng web thế hệ mới, được thiết kế để số hóa và tối ưu hóa quy trình xếp lịch phức tạp trong các môi trường giáo dục quy mô lớn (bao gồm nhiều giáo viên, lớp học và phòng học). Hệ thống giải quyết các thách thức cố hữu của việc xếp lịch thủ công bằng cách cung cấp thuật toán **tự động xếp lịch thông minh** cùng với khả năng **kiểm tra xung đột thời gian thực**, đảm bảo tính chính xác và hiệu quả. Mục tiêu chính là cung cấp một nền tảng tập trung, minh bạch, giúp Quản trị viên, Giáo viên và Học sinh dễ dàng quản lý, theo dõi và nhận thông báo về lịch học một cách kịp thời.

### **Mục tiêu Chính**

1.  Tự động hóa quá trình xếp lịch dựa trên các ràng buộc phức tạp (Giáo viên, Lớp học, Phòng học).
2.  Cung cấp giao diện trực quan, dễ sử dụng cho việc chỉnh sửa và quản lý lịch thủ công.
3.  Đảm bảo tính toàn vẹn của dữ liệu lịch học bằng cách phát hiện và cảnh báo xung đột ngay lập tức.
4.  Cung cấp các tính năng quản lý nghiệp vụ giáo dục cốt lõi (Quản lý người dùng, Lớp học, Điểm danh).
5.  Đạt hiệu suất cao (thời gian phản hồi nhanh, chịu tải tốt) và bảo mật dữ liệu người dùng.

### **Đối tượng Người dùng Chính**

* **Quản trị viên (Admin):** Thực hiện xếp lịch, quản lý người dùng, danh mục, và hệ thống.
* **Giáo viên (Teacher):** Xem lịch dạy cá nhân, điểm danh học sinh.
* **Học sinh (Student):** Xem lịch học của lớp, xem lịch sử điểm danh cá nhân.

---

## 3. Phác thảo Cấu hình Công nghệ (Tech Stack Blueprint)

Dự án sẽ sử dụng kiến trúc container hóa với Docker để đảm bảo tính nhất quán giữa môi trường phát triển và môi trường triển khai (Production).

### **3.1. Kiến trúc Docker & Container**

| Service (Tên Container) | Công nghệ | Mục đích |
| :--- | :--- | :--- |
| **app** | PHP 8.x (với CodeIgniter 4) + Nginx/Apache | Chứa mã nguồn dự án, xử lý logic nghiệp vụ và giao diện người dùng. |
| **db** | MySQL 8.0 | Lưu trữ dữ liệu ứng dụng (lịch học, người dùng, điểm danh, v.v.). |

### **3.2. Công nghệ Xây dựng & Tự động hóa**

| Thành phần | Công nghệ | Mục đích |
| :--- | :--- | :--- |
| **Web App Framework** | CodeIgniter 4 (CI4) | Cung cấp nền tảng MVC mạnh mẽ, nhẹ và dễ bảo trì cho logic ứng dụng. |
| **Cơ sở dữ liệu** | MySQL | Hệ quản trị CSDL quan hệ tin cậy, hiệu suất cao. |
| **Tự động hóa** | **Makefile** | Đơn giản hóa các tác vụ lặp đi lặp lại (ví dụ: khởi tạo, chạy, dừng, dọn dẹp môi trường Docker, chạy migrate, test). |

### **3.3. Phác thảo Command bằng `Makefile`**

Sử dụng `Makefile` là một lựa chọn tuyệt vời để chuẩn hóa quy trình làm việc. Dưới đây là các lệnh cơ bản cần có:

| Lệnh `make` | Mô tả |
| :--- | :--- |
| `make init` | **Khởi tạo dự án lần đầu tiên:** Xây dựng images Docker, khởi động containers, chạy `composer install` trong container CI4. |
| `make up` | Khởi động containers ở chế độ nền (`docker-compose up -d`). |
| `make down` | Dừng và xóa containers (`docker-compose down`). |
| `make cli` | Mở Terminal (Command Line Interface) vào container `app` (PHP/CI4) để chạy các lệnh như `php spark migrate`. |
| `make migrate` | Chạy database migrations của CodeIgniter 4 (`php spark migrate`). |
| `make seed` | Chạy database seeder để tạo dữ liệu ban đầu (`php spark db:seed`). |
| `make test` | Chạy unit tests của CI4. |
| `make logs` | Xem nhật ký hoạt động của các container. |

---

Bạn đã có một tài liệu SRS mạnh mẽ và một kế hoạch kỹ thuật rõ ràng. Bây giờ bạn có thể bắt đầu thiết lập cấu trúc file và Docker Compose! 