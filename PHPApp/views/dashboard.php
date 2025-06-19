<?php
$current_page_set = 'dashboard';

// Kích hoạt hiển thị lỗi PHP cho mục đích debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Supplier.php';
require_once 'config/database.php';

// Khởi tạo kết nối database
$db = new Database();
$conn = $db->getConnection();

// Khởi tạo các model
$product = new Product($conn);
$category = new Category($conn);
$supplier = new Supplier($conn);

// Khởi tạo biến dữ liệu với giá trị mặc định
$stats = ['total_products' => 0, 'total_value' => 0, 'low_stock_products' => [], 'latest_products' => []];
$totalCategories = 0;
$totalSuppliers = 0;
$activeSuppliers = 0;
$newlyImportedProductsList = [];
$errorMessage = null;

try {
    // Lấy thống kê tổng quan
    $stats = $product->getStatistics(); // Vẫn lấy stats tổng quan và low_stock, latest từ đây
    $totalCategories = $category->count(); // Sử dụng phương thức count()
    $totalSuppliers = $supplier->getTotalSuppliers();
    $activeSuppliers = $supplier->getActiveSuppliers(); // Lấy nhà cung cấp đang hoạt động

    // Lấy thống kê chi tiết (sản phẩm mới nhập)
    $newlyImportedProductsList = $product->getNewlyImportedProducts();

} catch (Exception $e) {
    // Nếu có lỗi, thông báo sẽ hiển thị do error_reporting được bật
    // Không cần set $errorMessage ở đây nữa trừ khi muốn hiển thị theo cách riêng
    // $errorMessage = "Có lỗi xảy ra khi lấy dữ liệu thống kê: " . $e->getMessage();
}

ob_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .stat-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 24px;
        }
        .notification-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }
        .notification-card:hover {
            transform: translateX(5px);
        }
        .notification-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-4">Dashboard</h2>
            </div>
        </div>

        <?php // Thông báo lỗi từ try...catch, có thể bị che bởi lỗi PHP display ?>
        <?php /* if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?= $errorMessage ?>
            </div>
        <?php endif; */ ?>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <i class='bx bxs-package'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Tổng sản phẩm</h5>
                                <h2 class="mb-0"><?= number_format($stats['total_products'] ?? 0) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <i class='bx bxs-category'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Tổng danh mục</h5>
                                <h2 class="mb-0"><?= number_format($totalCategories ?? 0) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <i class='bx bxs-truck'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Tổng nhà cung cấp</h5>
                                <h2 class="mb-0"><?= number_format($totalSuppliers ?? 0) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon me-3" style="background: rgba(255,255,255,0.2);">
                                <i class='bx bxs-user-detail'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Nhà cung cấp đang hoạt động</h5>
                                <h2 class="mb-0"><?= number_format($activeSuppliers ?? 0) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             </div>

        <!-- Thống kê chi tiết và thông báo -->
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sản phẩm sắp hết hàng</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($stats['low_stock_products'])): ?>
                            <?php foreach ($stats['low_stock_products'] as $product): ?>
                                <div class="notification-card p-3 mb-3 bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon me-3">
                                            <i class='bx bxs-error text-danger'></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                            <p class="mb-0 text-muted">
                                                Số lượng còn lại: <?= $product['quantity'] ?> | 
                                                Danh mục: <?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Không có sản phẩm nào sắp hết hàng</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sản phẩm mới nhất</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($stats['latest_products'])): ?>
                            <?php foreach ($stats['latest_products'] as $product): ?>
                                <div class="notification-card p-3 mb-3 bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon me-3">
                                            <i class='bx bxs-package text-primary'></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                            <p class="mb-0 text-muted">
                                                Giá: <?= number_format($product['price'] ?? 0) ?>đ | 
                                                Số lượng: <?= $product['quantity'] ?? 0 ?>
                                            </p>
                                             <small class="text-muted">
                                                Ngày tạo: <?= date('d/m/Y', strtotime($product['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Không có sản phẩm mới</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sản phẩm mới nhập gần đây</h5>
                    </div>
                    <div class="card-body">
                         <?php if (!empty($newlyImportedProductsList)): ?>
                            <?php foreach ($newlyImportedProductsList as $product): ?>
                                <div class="notification-card p-3 mb-3 bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon me-3">
                                            <i class='bx bxs-truck text-success'></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                            <p class="mb-0 text-muted">
                                                Giá: <?= number_format($product['price'] ?? 0) ?>đ | 
                                                Số lượng: <?= $product['quantity'] ?? 0 ?>
                                            </p>
                                             <small class="text-muted">
                                                Ngày nhập cuối: <?= date('d/m/Y', strtotime($product['last_import_date'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Không có sản phẩm mới nhập gần đây</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>

<?php
$content = ob_get_clean();
require_once 'views/layouts/admin.php';
?> 