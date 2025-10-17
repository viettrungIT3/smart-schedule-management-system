# ScheduleFlow API Documentation

## 🎯 **Overview**

ScheduleFlow API cung cấp RESTful endpoints cho Smart Schedule Management System. Tất cả endpoints đều trả về JSON format.

**Base URL:** `http://localhost:8088`

---

## 📋 **API Endpoints**

### **Health Check**
```http
GET /health
```
**Response:**
```json
{
  "status": "ok"
}
```

---

## 👥 **User Management (FU-01)**

### **List All Users**
```http
GET /api/users
```
**Response:**
```json
[
  {
    "id": "1",
    "role": "admin",
    "full_name": "Admin One",
    "email": "admin@example.com",
    "status": "active",
    "created_at": "2025-10-15 14:23:44"
  }
]
```

### **Get User by ID**
```http
GET /api/users/{id}
```
**Response:**
```json
{
  "id": "1",
  "role": "admin",
  "full_name": "Admin One",
  "email": "admin@example.com",
  "status": "active",
  "created_at": "2025-10-15 14:23:44"
}
```

### **Create User**
```http
POST /api/users
Content-Type: application/json

{
  "full_name": "John Doe",
  "email": "john@example.com",
  "password": "P@ssw0rd!",
  "role": "teacher",
  "status": "active"
}
```
**Response:**
```json
{
  "id": 7
}
```

### **Update User**
```http
PUT /api/users/{id}
Content-Type: application/json

{
  "full_name": "John Smith",
  "status": "inactive"
}
```
**Response:**
```json
{
  "success": true
}
```

### **Delete User**
```http
DELETE /api/users/{id}
```
**Response:**
```json
{
  "success": true
}
```

---

## 📅 **Schedule Management (FU-02, FU-05)**

### **Generate Schedules**
```http
POST /api/schedules/generate?reset=1
```
**Query Parameters:**
- `reset` (optional): `1` để xóa lịch chưa áp dụng trước khi generate

**Response:**
```json
{
  "created": 7
}
```

### **Apply Generated Schedules**
```http
POST /api/schedules/apply
```
**Response:**
```json
{
  "applied": 1
}
```

### **Search Schedules**
```http
GET /api/schedules/search?class_id=1&teacher_id=2&weekday=1&page=1&per_page=10
```
**Query Parameters:**
- `class_id` (optional): Filter by class ID
- `teacher_id` (optional): Filter by teacher ID
- `weekday` (optional): Filter by weekday (1-5)
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 10, max: 50)

**Response:**
```json
{
  "page": 1,
  "per_page": 10,
  "total": 2,
  "data": [
    {
      "id": "1",
      "class_id": "1",
      "subject_id": "1",
      "teacher_id": "2",
      "room_id": "1",
      "timeslot_id": "1",
      "weekday": "1",
      "is_applied": "1",
      "class_name": "10A1",
      "subject_name": "Toán",
      "room_name": "P101",
      "timeslot_name": "Tiết 1"
    }
  ]
}
```

### **Get Schedules by Class**
```http
GET /api/schedules/class/{class_id}
```
**Response:**
```json
[
  {
    "id": "1",
    "class_id": "1",
    "subject_id": "1",
    "teacher_id": "2",
    "room_id": "1",
    "timeslot_id": "1",
    "weekday": "1",
    "is_applied": "1",
    "subject_name": "Toán",
    "room_name": "P101",
    "timeslot_name": "Tiết 1"
  }
]
```

### **Get Schedules by Teacher**
```http
GET /api/schedules/teacher/{teacher_id}
```
**Response:**
```json
[
  {
    "id": "1",
    "class_id": "1",
    "subject_id": "1",
    "teacher_id": "2",
    "room_id": "1",
    "timeslot_id": "1",
    "weekday": "1",
    "is_applied": "1",
    "class_name": "10A1",
    "subject_name": "Toán",
    "room_name": "P101",
    "timeslot_name": "Tiết 1"
  }
]
```

---

## 👨‍🏫 **Teaching Assignments (FU-03)**

### **List All Assignments**
```http
GET /api/assignments?page=1&per_page=10
```
**Response:**
```json
{
  "page": 1,
  "per_page": 10,
  "total": 2,
  "data": [
    {
      "id": "1",
      "teacher_id": "2",
      "class_id": "1",
      "subject_id": "1",
      "periods_per_week": "4",
      "teacher_name": "Teacher A",
      "subject_name": "Toán",
      "class_name": "10A1"
    }
  ]
}
```

### **Get Assignments by Class**
```http
GET /api/assignments/class/{class_id}
```
**Response:**
```json
[
  {
    "id": "1",
    "teacher_id": "2",
    "class_id": "1",
    "subject_id": "1",
    "periods_per_week": "4",
    "teacher_name": "Teacher A",
    "subject_name": "Toán"
  }
]
```

### **Get Assignments by Teacher**
```http
GET /api/assignments/teacher/{teacher_id}
```
**Response:**
```json
[
  {
    "id": "1",
    "teacher_id": "2",
    "class_id": "1",
    "subject_id": "1",
    "periods_per_week": "4",
    "class_name": "10A1",
    "subject_name": "Toán"
  }
]
```

### **Create Assignment**
```http
POST /api/assignments
Content-Type: application/json

{
  "class_id": 1,
  "teacher_id": 2,
  "subject_id": 1,
  "periods_per_week": 4
}
```
**Response:**
```json
{
  "id": 3
}
```

### **Update Assignment**
```http
PUT /api/assignments/{id}
Content-Type: application/json

{
  "periods_per_week": 5
}
```
**Response:**
```json
{
  "success": true
}
```

### **Delete Assignment**
```http
DELETE /api/assignments/{id}
```
**Response:**
```json
{
  "success": true
}
```

---

## 📝 **Attendance Management (FU-06)**

### **Get Attendance by Schedule**
```http
GET /api/attendance/schedule/{schedule_id}
```
**Response:**
```json
[
  {
    "id": "1",
    "schedule_id": "1",
    "student_id": "4",
    "status": "present",
    "note": null,
    "created_at": "2025-10-16 14:46:40"
  }
]
```

### **Mark Attendance**
```http
POST /api/attendance/schedule/{schedule_id}
Content-Type: application/json

[
  {
    "student_id": 4,
    "status": "present",
    "note": null
  },
  {
    "student_id": 5,
    "status": "absent",
    "note": "sick"
  }
]
```
**Response:**
```json
{
  "done": 2,
  "results": [
    {
      "student_id": 4,
      "updated": true
    },
    {
      "student_id": 5,
      "created": true
    }
  ]
}
```

---

## 🔔 **Notifications (FU-07)**

### **Get Notifications by User**
```http
GET /api/notifications/user/{user_id}
```
**Response:**
```json
[
  {
    "id": "1",
    "user_id": "2",
    "title": "Schedule Change",
    "message": "Room P101 changed to P102",
    "is_read": "0",
    "created_at": "2025-10-16 14:16:38"
  }
]
```

### **Create Notification**
```http
POST /api/notifications
Content-Type: application/json

{
  "user_id": 2,
  "title": "Schedule Change",
  "message": "Room P101 changed to P102"
}
```
**Response:**
```json
{
  "id": 7
}
```

### **Mark Notification as Read**
```http
POST /api/notifications/{id}/read
```
**Response:**
```json
{
  "id": 7,
  "is_read": 1
}
```

---

## 🚨 **Error Responses**

### **400 Bad Request**
```json
{
  "error": "Invalid payload"
}
```

### **404 Not Found**
```json
{
  "error": "Not found"
}
```

### **422 Validation Error**
```json
{
  "errors": {
    "email": "The email field is required."
  }
}
```

---

## 🧪 **Testing**

### **Health Check**
```bash
bash scripts/tests/health.sh
```

### **User Management**
```bash
bash scripts/tests/user/users.sh
bash scripts/tests/user/create_user.sh
bash scripts/tests/user/show_user.sh 1
bash scripts/tests/user/update_user.sh 1
bash scripts/tests/user/delete_user.sh 1
```

### **Schedule Management**
```bash
bash scripts/tests/schedule/generate_schedule.sh
bash scripts/tests/schedule/generate_with_reset.sh
bash scripts/tests/schedule/apply_schedule.sh
bash scripts/tests/schedule/search.sh 1 2 1
bash scripts/tests/schedule/by_class.sh 1
bash scripts/tests/schedule/by_teacher.sh 2
```

### **Teaching Assignments**
```bash
bash scripts/tests/assignments/list_all.sh
bash scripts/tests/assignments/list_by_class.sh 1
bash scripts/tests/assignments/list_by_teacher.sh 2
bash scripts/tests/assignments/create.sh 1 2 1
bash scripts/tests/assignments/update.sh 1 5
bash scripts/tests/assignments/delete.sh 1
```

### **Attendance**
```bash
bash scripts/tests/attendance/attendance.sh
```

### **Notifications**
```bash
bash scripts/tests/notifications/notifications.sh
bash scripts/tests/notifications/create_notification.sh 2 "Test" "Message"
bash scripts/tests/notifications/mark_read.sh 1
bash scripts/tests/notifications/list_by_user.sh 2
```

---

## 📊 **Status Codes**

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request data |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation errors |
| 500 | Internal Server Error - Server error |

---

**ScheduleFlow API** - Comprehensive RESTful API for Smart Schedule Management System
