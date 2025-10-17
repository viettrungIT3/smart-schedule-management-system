

### **TÀI LIỆU ĐẶC TẢ YÊU CẦU PHẦN MỀM (SRS)**

**Tên dự án:** Hệ Thống Quản Lý Thời Gian Biểu Thông Minh (Smart Schedule Management System - SSMS)
**Phiên bản tài liệu:** 1.0
**Ngày:** 24/05/2024
**Tác giả:** [Tên của bạn/Nhóm của bạn]

---

### **1. Giới Thiệu**

#### **1.1 Mục Đích**
Tài liệu này mô tả chi tiết các yêu cầu chức năng và phi chức năng cho hệ thống "Smart Schedule Management System" (SSMS). SSMS là một nền tảng web được thiết kế để số hóa và tối ưu hóa công tác quản lý, phân công và theo dõi thời gian biểu trong một môi trường giáo dục có quy mô lớn (nhiều lớp, nhiều học sinh, nhiều giáo viên). Đối tượng sử dụng tài liệu này bao gồm Ban Giám hiệu, Quản trị viên, Nhà phát triển và Nhóm Kiểm thử.

#### **1.2 Phạm Vi**
Hệ thống sẽ cho phép:
- **Quản trị viên:** Quản lý toàn bộ dữ liệu nền tảng (người dùng, lớp học, môn học, phòng học).
- **Giáo viên:** Đăng ký lịch dạy, xem lịch cá nhân, điểm danh học sinh.
- **Học sinh:** Xem lịch học của lớp mình, xem điểm danh cá nhân.
- **Tất cả người dùng:** Nhận thông báo về các thay đổi lịch học.
Hệ thống tập trung vào việc tự động hóa việc xếp lịch, giảm thiểu xung đột và cung cấp một giao diện trực quan, dễ sử dụng.

#### **1.3 Định Nghĩa, Từ Viết Tắt và Thuật Ngữ**
- **Admin (Quản trị viên):** Người có quyền cao nhất trong hệ thống.
- **Teacher (Giáo viên):** Người dạy học, được phân công giảng dạy các môn.
- **Student (Học sinh):** Người học, thuộc về một lớp học cụ thể.
- **Class (Lớp học):** Một tập hợp học sinh (ví dụ: 10A1, 10A2).
- **Subject (Môn học):** Một bộ môn được giảng dạy (ví dụ: Toán, Lý).
- **Schedule (Thời gian biểu/Lịch học):** Một bản ghi thời gian cụ thể cho một tiết học, bao gồm Thời gian, Lớp, Môn học, Giáo viên, Phòng học.
- **Timeslot (Tiết học):** Một khoảng thời gian cố định trong ngày (ví dụ: Tiết 1: 7h00 - 7h45).
- **Xung đột lịch:** Tình huống một giáo viên hoặc một phòng học được phân công hai tiết trong cùng một khung giờ.

#### **1.4 Tài Liệu Tham Khảo**
- [Có thể tham khảo các tài liệu về Quy trình nghiệp vụ xếp lịch của nhà trường, nếu có]

#### **1.5 Tổng Quan**
Tài liệu này được chia thành các phần mô tả tổng thể hệ thống, các yêu cầu chức năng cụ thể, các yêu cầu phi chức năng, và các giao diện bên ngoài.

---

### **2. Mô Tả Tổng Quan**

#### **2.1 Mô Tả Hệ Thống**
SSMS là một ứng dụng web, nơi mà các quy trình xếp lịch thủ công, phức tạp được thay thế bằng một hệ thống tự động, thông minh và tập trung. Hệ thống đảm bảo tính chính xác, minh bạch và cung cấp thông tin kịp thời đến tất cả các bên liên quan.

#### **2.2 Chức Năng Hệ Thống (Tóm tắt)**
- Quản lý người dùng (thêm, sửa, xóa, phân quyền).
- Quản lý danh mục (lớp học, môn học, phòng học, khung giờ).
- Tự động/Tùy chỉnh xếp lịch học dựa trên các ràng buộc.
- Hiển thị lịch học dưới dạng bảng (theo tuần/theo ngày) cho từng đối tượng.
- Quản lý điểm danh học sinh theo từng tiết học.
- Gửi thông báo về lịch học và thay đổi.

#### **2.3 Đặc Điểm Người Dùng**
- **Quản trị viên:** Có kiến thức về CNTT, hiểu rõ nghiệp vụ nhà trường.
- **Giáo viên:** Có kỹ năng sử dụng máy tính cơ bản, chủ yếu sử dụng hệ thống để xem và đăng ký lịch.
- **Học sinh:** Thành thạo thiết bị di động và web, chủ yếu sử dụng để tra cứu.

#### **2.4 Ràng Buộc**
- Hệ thống phải là một ứng dụng web, có thể truy cập qua các trình duyệt phổ biến (Chrome, Firefox, Edge).
- Phải hỗ trợ tiếng Việt.
- Tuân thủ Luật An ninh mạng và các quy định về bảo vệ dữ liệu cá nhân.

---

### **3. Yêu Cầu Hệ Thống**

#### **3.1 Yêu Cầu Chức Năng**

**3.1.1 Quản lý Người dùng và Phân quyền (FU-01)**
- **Mô tả:** Hệ thống phải quản lý được thông tin và phân quyền cho ba nhóm người dùng: Admin, Teacher, Student.
- **Yêu cầu con:**
    - **FU-01.1:** Admin có thể thêm, sửa, xóa, vô hiệu hóa tài khoản của tất cả người dùng.
    - **FU-01.2:** Admin có thể phân công Giáo viên chủ nhiệm cho các Lớp học.
    - **FU-01.3:** Mỗi người dùng chỉ có thể đăng nhập bằng một vai trò duy nhất.
    - **FU-01.4:** Học sinh được tạo hàng loạt bằng file Excel (CSV).

**3.1.2 Quản lý Danh mục (FU-02)**
- **Mô tả:** Hệ thống phải quản lý các danh mục cốt lõi để phục vụ cho việc xếp lịch.
- **Yêu cầu con:**
    - **FU-02.1:** Quản lý Lớp học (Tên lớp, Sĩ số, Giáo viên chủ nhiệm).
    - **FU-02.2:** Quản lý Môn học (Tên môn, Mô tả).
    - **FU-02.3:** Quản lý Phòng học (Tên phòng, Chức năng - ví dụ: Phòng Lab Lý, Phòng máy tính).
    - **FU-02.4:** Quản lý Khung giờ (Thiết lập các tiết học trong ngày, ví dụ: Tiết 1: 7h00-7h45).

**3.1.3 Quản lý Phân công Giảng dạy (FU-03)**
- **Mô tả:** Admin phải có khả năng phân công giáo viên phụ trách các môn học cho từng lớp.
- **Yêu cầu con:**
    - **FU-03.1:** Phân công Giáo viên A dạy Môn X cho Lớp Y, với số tiết/tuần.

**3.1.4 Tạo và Quản lý Thời gian biểu (FU-04)**
- **Mô tả:** Đây là chức năng cốt lõi của hệ thống.
- **Yêu cầu con:**
    - **FU-04.1 (Tự động xếp lịch):** Hệ thống tự động tạo ra một thời gian biểu cho tất cả các lớp dựa trên các phân công giảng dạy (FU-03) và các ràng buộc (một giáo viên không thể dạy hai lớp cùng lúc, một phòng học không thể được dùng bởi hai lớp cùng lúc).
    - **FU-04.2 (Xếp lịch thủ công):** Admin có thể chỉnh sửa, kéo thả trực tiếp trên giao diện lịch để điều chỉnh lịch tự động.
    - **FU-04.3 (Kiểm tra xung đột):** Hệ thống phải cảnh báo ngay lập tức khi Admin tạo ra một xung đột lịch (về giáo viên hoặc phòng học) trong quá trình xếp lịch thủ công.
    - **FU-04.4 (Áp dụng lịch):** Lịch học sau khi hoàn tất sẽ được "Áp dụng" và trở thành lịch học chính thức cho một kỳ học/tuần học.

**3.1.5 Hiển thị và Tra cứu Lịch học (FU-05)**
- **Mô tả:** Mỗi người dùng có thể xem lịch học liên quan đến mình.
- **Yêu cầu con:**
    - **FU-05.1:** Giáo viên xem được lịch dạy của bản thân trong tuần/dưới dạng lịch tuần.
    - **FU-05.2:** Học sinh xem được lịch học của lớp mình trong tuần.
    - **FU-05.3:** Admin xem được toàn bộ lịch của tất cả các lớp, tất cả giáo viên.
    - **FU-05.4:** Có chức năng lọc và tìm kiếm lịch theo Lớp, Giáo viên.

**3.1.6 Quản lý Điểm danh (FU-06)**
- **Mô tả:** Giáo viên có thể điểm danh học sinh trong mỗi tiết học.
- **Yêu cầu con:**
    - **FU-06.1:** Trong lịch dạy của mình, Giáo viên có thể click vào một tiết học cụ thể để điểm danh.
    - **FU-06.2:** Hệ thống hiển thị danh sách học sinh của lớp đó. Giáo viên có thể chọn trạng thái (Có mặt, Vắng, Có phép, Muộn).
    - **FU-06.3:** Học sinh có thể xem lịch sử điểm danh của cá nhân.

**3.1.7 Quản lý Thông báo (FU-07)**
- **Mô tả:** Hệ thống thông báo kịp thời các thay đổi về lịch học.
- **Yêu cầu con:**
    - **FU-07.1:** Khi Admin thay đổi lịch học (hủy tiết, đổi phòng, dời lịch), hệ thống tự động gửi thông báo đến Giáo viên và Lớp học liên quan.
    - **FU-07.2:** Thông báo hiển thị trên web và có thể gửi qua Email.

#### **3.2 Yêu Cầu Phi Chức Năng**

**3.2.1 Yêu Cầu về Hiệu Suất**
- **Thời gian phản hồi:** Thời gian tải trang dưới 3 giây trong điều kiện bình thường.
- **Khả năng chịu tải:** Hệ thống hỗ trợ đồng thời ít nhất 500 người dùng truy cập và sử dụng.
- **Thời gian xếp lịch tự động:** Thuật toán xếp lịch tự động phải hoàn thành trong vòng dưới 5 phút cho quy mô 50 lớp học.

**3.2.2 Yêu Cầu về Bảo Mật**
- Tất cả mật khẩu phải được mã hóa.
- Người dùng phải đăng nhập để truy cập hệ thống.
- Phân quyền chi tiết: Học sinh không thể truy cập chức năng của Giáo viên/Admin.
- Dữ liệu truyền tải giữa máy khách và máy chủ phải được mã hóa (sử dụng HTTPS).

**3.2.3 Yêu Cầu về Tính Khả Dụng**
- Giao diện người dùng trực quan, dễ học, dễ sử dụng.
- Người dùng không có chuyên môn CNTT có thể sử dụng sau ít hơn 1 giờ làm quen.
- Hệ thống phải có hướng dẫn sử dụng (Help) cơ bản.

**3.2.4 Yêu Cầu về Độ Tin Cậy**
- Thời gian hoạt động (Uptime) đạt 99.5%.
- Có cơ chế sao lưu dữ liệu tự động hàng tuần. Có thể khôi phục dữ liệu trong vòng 2 giờ nếu có sự cố.

**3.2.5 Yêu Cầu Khác**
- **Khả năng bảo trì:** Mã nguồn phải được ghi chú rõ ràng, dễ dàng để sửa đổi, nâng cấp.
- **Khả năng mở rộng:** Kiến trúc hệ thống cho phép dễ dàng thêm các module mới trong tương lai (ví dụ: Quản lý điểm, Học phí).

---

### **4. Các Yêu Cầu Bổ Sung**

#### **4.1 Giao Diện Bên Ngoài**
- **Giao diện người dùng:** Phải có thiết kế Responsive, hoạt động tốt trên cả máy tính để bàn, máy tính bảng và điện thoại thông minh.
- **API:** Hệ thống có thể cung cấp API trong tương lai để tích hợp với các hệ thống khác (ví dụ: Hệ thống quản lý nhân sự).

#### **4.2 Tài Liệu**
- **Tài liệu người dùng:** Cung cấp tài liệu hướng dẫn sử dụng chi tiết cho từng nhóm người dùng (Admin, Giáo viên, Học sinh).
- **Tài liệu kỹ thuật:** Tài liệu dành cho nhà phát triển và quản trị viên hệ thống.

---

**Lưu ý:** Đây là một bản SRS tổng quát. Trong quá trình phát triển thực tế, mỗi yêu cầu chức năng (FU-xx) cần được triển khai thành các **User Story** chi tiết hơn với các tiêu chí chấp nhận rõ ràng để nhóm phát triển code và nhóm QA kiểm thử.