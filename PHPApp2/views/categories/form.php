<?php
$current_page = 'categories';
$is_edit = isset($category->id);
ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-tags me-2"></i>
            <?php echo $is_edit ? 'Sửa danh mục' : 'Thêm danh mục mới'; ?>
        </h2>
        <a href="index.php?action=categories" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="index.php?action=<?php echo $is_edit ? 'update_category' : 'store_category'; ?>" method="POST">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $category->id; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="<?php echo $is_edit ? htmlspecialchars($category->name) : ''; ?>" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="3"><?php echo $is_edit ? htmlspecialchars($category->description) : ''; ?></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        <?php echo $is_edit ? 'Cập nhật' : 'Thêm mới'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'views/layouts/admin.php';
?> 