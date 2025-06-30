# Note App

Ứng dụng ghi chú đơn giản gồm 2 thành phần:

* **Frontend**: Vue.js
* **Backend**: Laravel

---

## 🚀 Tính năng chính

* Tạo, xem, sửa, xóa ghi chú
* Giao diện thân thiện, dễ dùng
* Kết nối API với Laravel

---

## 📆 Cài đặt và sử dụng

### 1. Tải về dự án

```bash
git clone https://github.com/tuilahieu/note-app-1.git
cd note-app-1
```

---

## 🖥️ Chạy ứng dụng

### 📂 Cấu trúc thư mục

```
note-app-1/
├── frontend/    # Vue.js app
└── backend/api     # Laravel API
```

---

### 🧩 2. Chạy **Frontend (Vue.js)**

```bash
cd frontend
npm install
npm run serve
```

---

### 🔧 3. Chạy **Backend (Laravel)**

```bash
cd backend/api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
**cần cấu hình lại kết nối trong file .env**
php artisan serve
```

---
