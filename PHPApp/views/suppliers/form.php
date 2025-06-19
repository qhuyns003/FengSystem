<?php
$current_page = 'suppliers';
$is_edit = isset($supplier->id);
$title = $is_edit ? 'Sửa nhà cung cấp' : 'Thêm nhà cung cấp mới';
ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck me-2"></i><?php echo $title; ?></h2>
        <a href="index.php?action=suppliers" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="index.php?action=<?php echo $is_edit ? 'update_supplier' : 'store_supplier'; ?>" method="POST">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $supplier->id; ?>">
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Tên nhà cung cấp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required
                               value="<?php echo $is_edit ? htmlspecialchars($supplier->name) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required
                               value="<?php echo $is_edit ? htmlspecialchars($supplier->email) : ''; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" required
                               value="<?php echo $is_edit ? htmlspecialchars($supplier->phone) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address"
                               value="<?php echo $is_edit ? htmlspecialchars($supplier->address) : ''; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo $is_edit ? htmlspecialchars($supplier->description) : ''; ?></textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status"
                               <?php echo (!$is_edit || $supplier->status) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status">Hoạt động</label>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i><?php echo $is_edit ? 'Cập nhật' : 'Thêm mới'; ?>
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