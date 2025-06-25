<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts - Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: #f8f9fa; /* Nền sáng nhẹ nhàng */
            font-family: 'Arial', sans-serif; /* Font chữ cơ bản dễ đọc */
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef); /* Gradient nhẹ */
            color: #343a40; /* Màu chữ tối */
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); /* Đổ bóng sang phải */
            padding-top: 0; /* Bỏ padding trên cùng để navbar-brand khít */
        }
        .sidebar .nav-link {
            color: #5a6268; /* Màu chữ hơi xám */
            padding: 1.2rem 1.5rem; /* Tăng padding dọc */
            border-left: 4px solid transparent; /* Viền trái trong suốt ban đầu */
            transition: all 0.3s ease; /* Hiệu ứng chuyển động */
            font-weight: 500; /* Độ đậm font */
            display: flex; /* Sử dụng flexbox để căn chỉnh icon và text */
            align-items: center; /* Căn giữa theo chiều dọc */
        }
         .sidebar .nav-link i {
            margin-right: 1rem; /* Khoảng cách giữa icon và text */
            font-size: 1.1rem; /* Kích thước icon */
        }
        .sidebar .nav-link:hover {
            color: #0056b3; /* Màu xanh đậm hơn khi hover */
            background: rgba(0, 123, 255, 0.1); /* Nền xanh nhạt trong suốt khi hover */
            border-left-color: #007bff; /* Viền trái màu xanh khi hover */
        }
        .sidebar .nav-link.active {
            color: #007bff; /* Màu xanh khi active */
            background: rgba(0, 123, 255, 0.15); /* Nền xanh nhạt trong suốt khi active */
            border-left-color: #007bff; /* Viền trái màu xanh khi active */
            font-weight: bold; /* In đậm chữ khi active */
        }
        .main-content {
            padding: 20px;
        }
        .navbar-brand {
            padding: 1.5rem 1rem; /* Padding */
            font-size: 1.5rem; /* Kích thước font */
            background: #ffffff; /* Nền trắng */
            color: #343a40; /* Màu chữ */
            text-align: center; /* Căn giữa */
            border-bottom: 1px solid #dee2e6; /* Viền dưới */
            margin-bottom: 1rem; /* Khoảng cách dưới */
        }
        .brand-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em; /* Giảm letter spacing */
            text-transform: uppercase;
            font-size: 1.25rem; /* Điều chỉnh kích thước */
            color: #343a40; /* Màu chữ */
            text-align: center; /* Căn giữa */
            width: 100%;
            padding: 0.5rem 0; /* Padding */
        }
       
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto; /* Auto height on mobile */
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Đổ bóng xuống dưới */
                padding-top: auto; /* Bỏ padding top */
            }
            .sidebar .nav {
                flex-direction: row !important; /* Arrange nav items horizontally */
                 justify-content: space-around; /* Phân bố đều khoảng cách */
                 padding: 0.5rem 0; /* Padding */
            }
            .sidebar .nav-item {
                flex-grow: 1; /* Distribute space evenly */
                text-align: center; /* Center text */
            }
             .sidebar .nav-link {
                padding: 0.5rem 0.5rem; /* Adjust padding on mobile */
                border-left: none; /* Remove left border on mobile */
                border-bottom: 4px solid transparent; /* Add bottom border for active */
                flex-direction: column; /* Stack icon and text vertically */
                 font-size: 0.8rem; /* Giảm kích thước font */
            }
            .sidebar .nav-link i {
                margin-right: 0; /* Bỏ margin right */
                 margin-bottom: 0.3rem; /* Khoảng cách dưới icon */
                 font-size: 1rem; /* Kích thước icon */
            }
             .sidebar .nav-link.active {
                border-left-color: transparent; /* Remove left border */
                border-bottom-color: #007bff; /* Add bottom border */
            }
             .main-content {
                padding: 10px;
            }
             .navbar-brand {
                 padding: 1rem;
                 margin-bottom: 0; /* Bỏ khoảng cách dưới */
            }
             .brand-text {
                 font-size: 1rem;
             }
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="navbar-brand" style="text-align:center; background:#ffffff; padding:1.5rem 1rem; width:100%; margin:0; color:#343a40; border-bottom:1px solid #dee2e6;">
                    <i class="fas fa-store me-2"></i> <!-- Thay icon -->
                     FENG Admin
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page_set == 'dashboard' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page_set == 'products' ? 'active' : ''; ?>" href="index.php?action=products">
                            <i class="fas fa-box me-2"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page_set == 'categories' ? 'active' : ''; ?>" href="index.php?action=categories">
                            <i class="fas fa-tags me-2"></i>Danh mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page_set == 'suppliers' ? 'active' : ''; ?>" href="index.php?action=suppliers">
                            <i class="fas fa-truck me-2"></i>Nhà cung cấp
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page_set == 'statistics' ? 'active' : ''; ?>" href="index.php?action=statistics">
                            <i class="fas fa-chart-bar me-2"></i>Thống kê
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <div class="brand-text">FENG SYSTEM</div>
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?action=logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <?php echo $content; ?>

                <!-- Footer -->
                <footer class="footer mt-auto py-3 bg-light">
                    <div class="container text-center">
                        <span class="text-muted">© 2025 FENG System. All rights reserved.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
