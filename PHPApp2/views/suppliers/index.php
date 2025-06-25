<?php
$current_page_set = 'suppliers';
ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck me-2"></i>Quản lý nhà cung cấp</h2>
        <a href="index.php?action=create_supplier" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm nhà cung cấp mới
        </a>
    </div>

    <!-- Suppliers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên nhà cung cấp</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($suppliers)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Không có nhà cung cấp nào</td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($suppliers as $supplier): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($supplier['name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($supplier['description'], 0, 50)) . '...'; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['address']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $supplier['status'] ? 'success' : 'danger'; ?>">
                                            <?php echo $supplier['status'] ? 'Hoạt động' : 'Không hoạt động'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($supplier['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?action=edit_supplier&id=<?php echo $supplier['id']; ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=delete_supplier&id=<?php echo $supplier['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')"
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
            // Định nghĩa số nhà cung cấp trên mỗi trang
            $items_per_page = 10;
            
            // Tính toán các tham số phân trang
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $total_suppliers = isset($total_suppliers) ? (int)$total_suppliers : 0;
            
            // Tính tổng số trang, đảm bảo không chia cho 0
            $total_pages = $total_suppliers > 0 ? ceil($total_suppliers / $items_per_page) : 1;
            
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
                        $base_url = "?action=suppliers";
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