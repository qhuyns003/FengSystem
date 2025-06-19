<?php
$current_page = 'products';
ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas <?php echo isset($product) ? 'fa-edit' : 'fa-plus'; ?> me-2"></i>
            <?php echo isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới'; ?>
        </h2>
        <a href="index.php?action=products" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="index.php?action=<?php echo isset($product) ? 'update_product' : 'store_product'; ?>" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="needs-validation" 
                  novalidate>
                
                <?php if (isset($product)): ?>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo isset($product) ? htmlspecialchars($product['name']) : ''; ?>" 
                                           required>
                                    <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Chọn danh mục</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                <?php echo (isset($product) && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Nhà cung cấp <span class="text-danger">*</span></label>
                                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                                        <option value="">Chọn nhà cung cấp</option>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?php echo $supplier['id']; ?>" 
                                                <?php echo (isset($product) && in_array($supplier['id'], $product['supplier_ids'] ?? [])) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($supplier['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" 
                                              id="description" 
                                              name="description" 
                                              rows="4"><?php echo isset($product) ? htmlspecialchars($product['description']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Price and Stock -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Giá và tồn kho</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="price" 
                                                       name="price" 
                                                       value="<?php echo (isset($product['price']) ? $product['price'] : ''); ?>" 
                                                       required 
                                                       min="0"
                                                       step="0.01">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập giá sản phẩm</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="quantity" 
                                                   name="quantity" 
                                                   value="<?php echo (isset($product['quantity']) ? $product['quantity'] : (isset($product) ? '' : '0')); ?>" 
                                                   required 
                                                   min="0"
                                                   readonly disabled>
                                            <div class="invalid-feedback">Vui lòng nhập số lượng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Image Upload -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hình ảnh sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <?php if (isset($product) && !empty($product['image'])): ?>
                                        <div class="mb-3">
                                            <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                                 alt="Current product image" 
                                                 class="img-thumbnail mb-2" 
                                                 style="max-width: 200px;">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <label for="image" class="form-label">Chọn hình ảnh</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           <?php echo !isset($product) ? 'required' : ''; ?>>
                                    <div class="form-text">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</div>
                                    <div class="invalid-feedback">Vui lòng chọn hình ảnh sản phẩm</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        <?php echo isset($product) ? 'Cập nhật' : 'Thêm mới'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.createElement('img');
            preview.src = e.target.result;
            preview.className = 'img-thumbnail mb-2';
            preview.style.maxWidth = '200px';
            
            var container = document.querySelector('.card-body');
            var existingPreview = container.querySelector('img');
            if (existingPreview) {
                container.removeChild(existingPreview);
            }
            container.insertBefore(preview, container.firstChild);
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>

<?php
$content = ob_get_clean();
require_once 'views/layouts/admin.php';
?> 