<?php
$current_page_set = 'categories';
ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tags me-2"></i>Quản lý danh mục</h2>
        <a href="index.php?action=create_category" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm danh mục mới
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có danh mục nào</td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?action=edit_category&id=<?php echo $category['id']; ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=delete_category&id=<?php echo $category['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"
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
            // Định nghĩa số danh mục trên mỗi trang
            $items_per_page = 10;
            
            // Tính toán các tham số phân trang
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $total_categories = isset($total_categories) ? (int)$total_categories : 0;
            
            // Tính tổng số trang, đảm bảo không chia cho 0
            $total_pages = $total_categories > 0 ? ceil($total_categories / $items_per_page) : 1;
            
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
                        $base_url = "?action=categories";
                        if (isset($_GET['search'])) {
                            $base_url .= "&search=" . urlencode($_GET['search']);
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