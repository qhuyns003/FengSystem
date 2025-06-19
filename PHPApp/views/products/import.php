<?php
$current_page = 'products';

require_once 'models/Product.php';
require_once 'models/Category.php';

$product = new Product((new Database())->getConnection());
$product->id = $_GET['id'] ?? null;

if ($product->id) {
    $product->readOne();
}

$isNewProduct = !$product->checkProductExists();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Nhập kho sản phẩm</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="index.php?action=import_product">
                        <input type="hidden" name="id" value="<?= $product->id ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($product->name) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số lượng nhập</label>
                            <input type="number" class="form-control" name="quantity" required min="1">
                        </div>

                        <?php if ($isNewProduct): ?>
                        <div class="mb-3">
                            <label class="form-label">Giá nhập</label>
                            <input type="number" class="form-control" name="price" required min="0" step="0.01">
                        </div>
                        <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label">Giá hiện tại</label>
                            <input type="number" class="form-control" value="<?= number_format($product->price, 2, '.', '') ?>" readonly>
                            <small class="text-muted">Không thể thay đổi giá của sản phẩm đã tồn tại</small>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Nhập kho</button>
                            <a href="index.php?action=products" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 