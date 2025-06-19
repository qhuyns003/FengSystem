<?php
$current_page = 'import_form';
ob_start();
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-box me-2"></i>Nhập kho</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="post" action="index.php?action=import_store">
                <div class="mb-3">
                    <label for="product_id" class="form-label">Sản phẩm <span class="text-danger">*</span></label>
                    <select class="form-select" id="product_id" name="product_id" required>
                        <option value="">Chọn sản phẩm</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id'] ?>" 
                                    data-suppliers='<?= htmlspecialchars(json_encode($product['suppliers'] ?? [])) ?>'>
                                <?= htmlspecialchars($product['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Nhà cung cấp <span class="text-danger">*</span></label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required disabled>
                        <option value="">Chọn nhà cung cấp</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="import_quantity" class="form-label">Số lượng nhập <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="import_quantity" name="import_quantity" required min="1">
                </div>

                <div class="mb-3">
                    <label for="import_price" class="form-label">Giá nhập <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="import_price" name="import_price" required min="0" step="0.01">
                        <span class="input-group-text">VNĐ</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Nhập kho
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('product_id').addEventListener('change', function() {
    const supplierSelect = document.getElementById('supplier_id');
    const selectedOption = this.options[this.selectedIndex];
    const suppliers = JSON.parse(selectedOption.dataset.suppliers || '[]');
    
    // Reset supplier select and price input
    supplierSelect.innerHTML = '<option value="">Chọn nhà cung cấp</option>';
    document.getElementById('import_price').value = ''; // Xóa giá khi đổi sản phẩm
    
    if (suppliers.length > 0) {
        suppliers.forEach(supplier => {
            const option = document.createElement('option');
            option.value = supplier.id;
            option.textContent = supplier.name;
            option.dataset.price = supplier.price; // Lưu giá vào dataset
            supplierSelect.appendChild(option);
        });
        supplierSelect.disabled = false;
    } else {
        supplierSelect.disabled = true;
    }
});

document.getElementById('supplier_id').addEventListener('change', function() {
    const priceInput = document.getElementById('import_price');
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.dataset.price) {
        priceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2); // Điền giá và định dạng 2 số thập phân
    } else {
        priceInput.value = ''; // Xóa giá nếu không có
    }
});
</script>

<?php
$content = ob_get_clean();
require_once 'views/layouts/admin.php';
?> 