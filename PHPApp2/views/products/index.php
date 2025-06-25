<?php
$current_page_set = 'products';
ob_start();
?>

<style>
    .product-image {
        width: 80px; /* Tăng kích thước chiều rộng */
        height: auto; /* Giữ tỉ lệ ảnh */
        object-fit: cover; /* Đảm bảo ảnh không bị méo */
        border-radius: 4px; /* Bo tròn góc ảnh nhẹ */
    }
    .table th, .table td {
        vertical-align: middle; /* Căn giữa nội dung ô */
    }
    .table tr:hover {
        background-color: #f5f5f5; /* Hiệu ứng hover nhẹ */
    }
    /* Tùy chỉnh thêm về font chữ, màu sắc nếu cần */
    /* body { font-family: 'Arial', sans-serif; } */
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h2>
        <div>
            <a href="index.php?action=import_form" class="btn btn-success me-2">
                <i class="fas fa-plus-square me-2"></i>Nhập kho
            </a>
            <a href="index.php?action=create_product" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-3">
                <input type="hidden" name="action" value="products">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Không có sản phẩm nào</td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td>
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                 class="product-image">
                                        <?php else: ?>
                                            <img src="assets/images/no-image.png" alt="No image" class="product-image">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?></td>
                                    <td><?php echo number_format($product['price']); ?> VNĐ</td>
                                    <td>
                                        <span class="badge bg-<?php echo $product['quantity'] > 0 ? 'success' : 'danger'; ?>">
                                            <?php echo $product['quantity']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?action=edit_product&id=<?php echo $product['id']; ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=delete_product&id=<?php echo $product['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')"
                                               title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php
            // Định nghĩa số sản phẩm trên mỗi trang
            $items_per_page = 10;
            
            // Tính toán các tham số phân trang
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $total_products = isset($total_products) ? (int)$total_products : 0;
            
            // Tính tổng số trang, đảm bảo không chia cho 0
            $total_pages = $total_products > 0 ? ceil($total_products / $items_per_page) : 1;
            
            // Đảm bảo current_page không vượt quá total_pages
            $current_page = min($current_page, $total_pages);
            $current_page = max(1, $current_page);
            
            // Chỉ hiển thị phân trang nếu có nhiều hơn 1 trang
            if ($total_pages > 1):
            ?>
            <div class="mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php
                        // Giới hạn số trang hiển thị
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        // Tạo URL cơ bản
                        $base_url = "?action=products";
                        if (isset($_GET['search'])) {
                            $base_url .= "&search=" . urlencode($_GET['search']);
                        }
                        if (isset($_GET['category'])) {
                            $base_url .= "&category=" . $_GET['category'];
                        }
                        
                        // Nút Previous
                        if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $base_url . '&page=' . ($current_page - 1); ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif;
                        
                        // Trang đầu tiên
                        if ($start_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $base_url . '&page=1'; ?>">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif;
                        endif;
                        
                        // Các trang số
                        for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $base_url . '&page=' . $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor;
                        
                        // Trang cuối cùng
                        if ($end_page < $total_pages):
                            if ($end_page < $total_pages - 1): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $base_url . '&page=' . $total_pages; ?>"><?php echo $total_pages; ?></a>
                            </li>
                        <?php endif;
                        
                        // Nút Next
                        if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $base_url . '&page=' . ($current_page + 1); ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'views/layouts/admin.php';
?> 