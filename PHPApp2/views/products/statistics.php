<?php
$current_page_set = 'statistics';
ob_start();
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-chart-bar me-2"></i>Thống kê sản phẩm</h2>

    <!-- Tổng quan -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Tổng số sản phẩm</h5>
                    <h2 class="card-text">
                        <?php
                        $product = new Product((new Database())->getConnection());
                        echo number_format($product->getTotalProducts());
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Tổng giá trị hàng tồn kho</h5>
                    <h2 class="card-text"><?php echo number_format($stats['total_value']); ?> VNĐ</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Tổng số nhà cung cấp</h5>
                    <h2 class="card-text">
                        <?php
                        $supplier = new Supplier((new Database())->getConnection());
                        echo number_format($supplier->getTotalSuppliers());
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Nhà cung cấp đang hoạt động</h5>
                    <h2 class="card-text">
                        <?php
                        echo number_format($supplier->getActiveSuppliers());
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Phân bố sản phẩm theo danh mục</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Xu hướng chi phí nhập hàng theo tháng</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm theo danh mục -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sản phẩm theo danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['products_by_category'] as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['category_name'] ?? 'Chưa phân loại'); ?></td>
                                    <td><?php echo number_format($category['product_count']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sản phẩm sắp hết hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Danh mục</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['low_stock_products'] as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?php echo number_format($product['quantity']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê chi tiết theo danh mục -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Thống kê chi tiết theo danh mục</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Danh mục</th>
                            <th>Số sản phẩm</th>
                            <th>Tổng số lượng</th>
                            <th>Tổng giá trị</th>
                            <th>Giá trung bình</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($category_stats as $stat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stat['category_name'] ?? 'Chưa phân loại'); ?></td>
                            <td><?php echo number_format($stat['total_products']); ?></td>
                            <td><?php echo number_format($stat['total_quantity']); ?></td>
                            <td><?php echo number_format($stat['total_value']); ?> VNĐ</td>
                            <td><?php echo number_format($stat['average_price']); ?> VNĐ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Thống kê theo tháng -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Thống kê chi phí nhập hàng theo tháng</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Chi phí nhập hàng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthly_trend as $stat): ?>
                        <tr>
                            <td><?php echo date('m/Y', strtotime($stat['month'] . '-01')); ?></td>
                            <td><?php echo number_format($stat['total_import_cost']); ?> VNĐ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top sản phẩm tồn kho cao nhất -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Top sản phẩm tồn kho cao nhất</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Số lượng tồn</th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_stock_products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?></td>
                            <td><?php echo number_format($product['quantity']); ?></td>
                            <td><?php echo number_format($product['price']); ?> VNĐ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <canvas id="topStockChart" height="120"></canvas>
        </div>
    </div>

    <!-- Top nhà cung cấp lớn theo giá trị -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Top nhà cung cấp lớn theo giá trị</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên nhà cung cấp</th>
                            <th>Số sản phẩm cung cấp</th>
                            <th>Tổng giá trị</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_suppliers_by_value as $supplier): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                            <td><?php echo number_format($supplier['total_products']); ?></td>
                            <td><?php echo number_format($supplier['total_value']); ?> VNĐ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <canvas id="topSupplierChart" height="120"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ phân bố sản phẩm theo danh mục
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($chart_data['category_labels']); ?>,
        datasets: [{
            data: <?php echo json_encode($chart_data['category_values']); ?>,
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right'
            }
        }
    }
});

// Biểu đồ xu hướng chi phí nhập hàng
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_map(function($stat) {
            return date('m/Y', strtotime($stat['month'] . '-01'));
        }, $monthly_trend)); ?>,
        datasets: [{
            label: 'Chi phí nhập hàng',
            data: <?php echo json_encode(array_map(function($stat) {
                return $stat['total_import_cost'];
            }, $monthly_trend)); ?>,
            borderColor: '#36A2EB',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' VNĐ';
                    }
                }
            }
        }
    }
});

// Biểu đồ top sản phẩm tồn kho cao nhất
const topStockCtx = document.getElementById('topStockChart').getContext('2d');
new Chart(topStockCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_map(function($p){return $p['name'];}, $top_stock_products)); ?>,
        datasets: [{
            label: 'Số lượng tồn',
            data: <?php echo json_encode(array_map(function($p){return (int)$p['quantity'];}, $top_stock_products)); ?>,
            backgroundColor: '#36A2EB',
        }]
    },
    options: {
        responsive: true,
        plugins: {legend: {display: false}},
        scales: {y: {beginAtZero: true}}
    }
});

// Biểu đồ top nhà cung cấp lớn theo giá trị
const topSupplierCtx = document.getElementById('topSupplierChart').getContext('2d');
new Chart(topSupplierCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_map(function($s){return $s['name'];}, $top_suppliers_by_value)); ?>,
        datasets: [{
            label: 'Tổng giá trị (VNĐ)',
            data: <?php echo json_encode(array_map(function($s){return (int)$s['total_value'];}, $top_suppliers_by_value)); ?>,
            backgroundColor: '#FF6384',
        }]
    },
    options: {
        responsive: true,
        plugins: {legend: {display: false}},
        scales: {y: {beginAtZero: true}}
    }
});
</script>

<?php
$content = ob_get_clean();
require_once 'views/layouts/admin.php';
?> 