Chắc chắn rồi! Dựa trên tài liệu SRS ở trên, tôi sẽ giúp bạn thiết lập các **Rules (Quy tắc)** cho team dev. Các rules này sẽ đảm bảo dự án được phát triển một cách đồng bộ, hiệu quả và dễ bảo trì.

---

### **QUY TẮC LÀM VIỆC CHO NHÓM PHÁT TRIỂN (TEAM RULES)**
**Dự án:** Smart Schedule Management System (SSMS)
**Phiên bản:** 1.0

---

### **1. Quy Tắc về Quy Trình Phát Triển & Công Cụ (Development Process & Tools)**

#### **1.1 Mô Hình Phát Triển**
- **Quy trình:** Áp dụng mô hình **Scrum** với các Sprint kéo dài 2 tuần.
- **Cuộc họp bắt buộc:**
    - **Daily Stand-up:** 15 phút mỗi sáng, báo cáo: Hôm qua làm gì? Hôm nay làm gì? Có khó khăn gì?
    - **Sprint Planning:** Lên kế hoạch cho Sprint tiếp theo.
    - **Sprint Review & Retrospective:** Kết thúc Sprint để demo và cải thiện quy trình.

#### **1.2 Quy ước đặt tên ticket:**
    - `[feat]`: Cho tính năng mới (Ví dụ: `[feat] FU-01 - ...`)
    - `[bug]`: Cho lỗi (Ví dụ: `[bug] ...`)
    - ...

### **2. Quy Tắc về Mã Nguồn (Code Rules)**

#### **2.1 Hệ Thống Quản Lý Phiên Bản (Git)**
- **Công cụ:** Git
- **Hosting:** GitHub, GitLab hoặc Azure Repos.
- **Mô hình branching:**
    - `master`: Nhánh chính, luôn ở trạng thái ổn định, chỉ được merge từ `develop` qua Pull Request.
    - `develop`: Nhánh phát triển, tích hợp tất cả các tính năng.
    - `[số-thứ-tu]/feature/[tên-tính-năng]`: Nhánh tính năng, tách từ `develop`. Ví dụ: `01/feature/FU-01-user-management`.
    - `[sô-thư-tự]/hotfix/[tên-sửa-lỗi]`: Nhánh sửa lỗi khẩn cấp, tách từ `master`.
    - Merge: tự tạo theo số thứ tự sử dụng git cli kèm theo số thứ tự task

#### **2.2 Quy Tắc Commit Code**
- **Cấu trúc:**
    ```
    <type>: <short description>
    
    <long description (if needed)>
    ```
    - **Các type phổ biến:** `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`.

#### **2.3 Quy Tắc Kiểm Tra Mã Nguồn (Code Review)**
- **Bắt buộc:** Mọi Pull Request (PR) phải được **ít nhất** review trước khi merge.
- **Người tạo PR:**
    - Mô tả rõ ràng những thay đổi.
    - Liên kết PR với ticket.
    - Kiểm tra xem code có build được không trước khi tạo PR.
- **Người review:**
    - Tập trung vào logic, cấu trúc, bảo mật và hiệu năng.
    - Nhận xét rõ ràng, mang tính xây dựng.

---

### **3. Quy Tắc Kỹ Thuật (Technical Rules)**

#### **3.1 Kiến Trúc & Công Nghệ**
- **Quy ước:**
    - Tuân thủ kiến trúc MVC (hoặc Clean Architecture) một cách thống nhất.
    - Tách biệt rõ ràng giữa các layer: Controller, Model,...

#### **3.2 Quy ước Viết Code**
- **Đặt tên biến/hàm:** 
- **Độ dài hàm:** Một hàm không nên dài quá 30-50 dòng.
- **Comment code:** Chỉ comment cho những logic phức tạp, khó hiểu. **Tuyệt đối không comment những code hiển nhiên.**
- **File và Folder:**
    - Đặt tên file rõ ràng, thể hiện nội dung.
    - Cấu trúc thư mục phải đồng nhất cho cả team.

#### **3.3 Xử Lý Lỗi & Bảo Mật**
- **Xử lý lỗi:** Bắt buộc sử dụng try-catch cho các thao tác I/O (database, API call).
- **Logging:** Ghi log đầy đủ cho các lỗi hệ thống và các sự kiện quan trọng.
- **Bảo mật:**
    - sử dụng file .env(nhớ gitignore)
    - Không hardcode password, secret key trong code.
    - Validate dữ liệu đầu vào từ phía client **và server**.
    - Sử dụng parameterized queries để chống SQL Injection.

---

### **4. Quy Tắc về Cơ Sở Dữ Liệu (Database Rules)**

- **Quy ước đặt tên bảng và cột:** Sử dụng **snake_case**.
    - Ví dụ: Bảng `users`, Cột `full_name`, `created_at`.
- **Mọi thay đổi cấu trúc DB** (tạo bảng, sửa cột) phải được thực hiện qua **script migration**.
- **Không được** trực tiếp sửa database trên môi trường Production.

---

### **5. Quy Tắc về Kiểm Thử (Testing Rules)**

- **Unit Test:** Bắt buộc viết unit test cho các function, service quan trọng. Đặt chỉ tiêu **code coverage > 80%**.
- **Integration Test:** Viết test cho các API chính.
- **Quy trình:**
    - Code phải pass hết các test case trước khi tạo PR.
    - Test tự động chạy trên CI/CD pipeline khi có PR mới.

---

### **6. Quy Tắc Triển Khai (Deployment Rules)**

- **Môi trường:**
    - **Development (Dev):** Cho dev test.
    - **Staging/UAT:** Môi trường giống Production để QA và khách hàng test.
    - **Production (Prod):** Môi trường chính thức.
- **Triển khai:**
    - Triển khai lên Staging và Production phải được thực hiện qua **CI/CD pipeline**.
    - Chỉ **Tech Lead/Manager** mới có quyền approve deploy lên Production.

---

### **7. Quy Tắc Tài Liệu (Documentation Rules)**

- **Bắt buộc cập nhật:** Mọi thay đổi về API phải được cập nhật ngay lập tức vào tài liệu (Sử dụng Swagger/OpenAPI).
- **Tài liệu kỹ thuật:** Được lưu trong thư mục `/docs` của repository, viết bằng Markdown.
- **Kiến thức:** Mọi thành viên phải ghi lại các vấn đề khó và giải pháp (ví dụ trên Confluence/Notion) để chia sẻ.

---

### **8. Xử Lý Vi Phạm**

- **Nhắc nhở:** Lần đầu vi phạm sẽ được nhắc nhở trực tiếp.
- **Thảo luận nhóm:** Vi phạm lặp lại sẽ được đem ra thảo luận trong cuộc họp Retrospective để tìm giải pháp chung.
- **Mục đích:** Các quy tắc nhằm giúp team làm việc hiệu quả hơn, không phải để trừng phạt.

---

**Lời kết:** Các quy tắc này nên được cả team thảo luận và thống nhất ngay từ đầu dự án. Chúng có thể được điều chỉnh linh hoạt trong các buổi họp Retrospective để phù hợp hơn với tình hình thực tế.