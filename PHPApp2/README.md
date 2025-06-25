# Hệ thống Quản lý Sản phẩm

Đây là một ứng dụng web đơn giản để quản lý sản phẩm, được xây dựng bằng PHP và MySQL.

## Yêu cầu hệ thống

- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache/Nginx)

## Cài đặt

1. Clone repository này về máy local của bạn
2. Import file `database.sql` vào MySQL để tạo cơ sở dữ liệu và bảng cần thiết
3. Cấu hình kết nối database trong file `config/database.php`:
   - Sửa `$host` nếu cần
   - Sửa `$username` và `$password` theo cấu hình MySQL của bạn
4. Đặt toàn bộ code vào thư mục web server của bạn (ví dụ: htdocs nếu dùng XAMPP)
5. Truy cập ứng dụng qua trình duyệt web

## Tính năng

- Xem danh sách sản phẩm
- Thêm sản phẩm mới
- Chỉnh sửa thông tin sản phẩm
- Xóa sản phẩm

## Cấu trúc thư mục

```
├── config/
│   └── database.php
├── controllers/
│   └── ProductController.php
├── models/
│   └── Product.php
├── views/
│   └── products/
│       ├── index.php
│       ├── create.php
│       └── edit.php
├── database.sql
├── index.php
└── README.md
```

## Sử dụng

1. Truy cập trang chủ để xem danh sách sản phẩm
2. Nhấn nút "Thêm sản phẩm mới" để tạo sản phẩm mới
3. Sử dụng các nút "Sửa" và "Xóa" để quản lý sản phẩm 