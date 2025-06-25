<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Supplier.php';
require_once 'config/database.php';

class ProductController {
    private $db;
    private $product;
    private $category;
    private $supplier;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->category = new Category($this->db);
        $this->supplier = new Supplier($this->db);
    }

    public function index() {
        // Get all categories for filter
        $categories = $this->category->read()->fetchAll(PDO::FETCH_ASSOC);

        // Get search and filter parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category_id = isset($_GET['category']) ? $_GET['category'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 10;

        // Get products with pagination
        $products = $this->product->readWithPagination($search, $category_id, $page, $per_page);
        $total_products = $this->product->getTotalProducts($search, $category_id);
        $total_pages = max(1, ceil($total_products / $per_page));

        require_once 'views/products/index.php';
    }

    public function create() {
        $categories = $this->category->read()->fetchAll(PDO::FETCH_ASSOC);
        $suppliers = $this->supplier->read()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/products/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->price = $_POST['price'];
            $this->product->quantity = 0;
            $this->product->category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/products/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $new_filename = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $this->product->image = $new_filename;
                }
            }

            if ($this->product->create()) {
                // Lấy ID sản phẩm vừa tạo
                $product_id = $this->db->lastInsertId();
                // Lấy supplier_id từ form (radio)
                if (!empty($_POST['supplier_id'])) {
                    $supplier_id = $_POST['supplier_id'];
                    // Thêm vào bảng product_suppliers
                    $stmt = $this->db->prepare("INSERT INTO product_suppliers (product_id, supplier_id, price) VALUES (?, ?, ?)");
                    $stmt->execute([$product_id, $supplier_id, $this->product->price]);
                }
                header('Location: index.php?action=products');
                exit();
            }
        }
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $this->product->id = $_GET['id'];
            if ($this->product->readOne()) {
                $categories = $this->category->read()->fetchAll(PDO::FETCH_ASSOC);
                $suppliers = $this->supplier->read()->fetchAll(PDO::FETCH_ASSOC);
                // Chuyển object sang array để view sử dụng
                $product = [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'description' => $this->product->description,
                    'price' => $this->product->price,
                    'quantity' => $this->product->quantity,
                    'image' => $this->product->image,
                    'category_id' => $this->product->category_id,
                    'supplier_ids' => $this->product->supplier_ids ?? [],
                ];
                require_once 'views/products/form.php';
            } else {
                header('Location: index.php?action=products');
                exit();
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->id = $_POST['id'];
            
            // Lấy ảnh cũ trước khi gán các thuộc tính mới
            $this->product->readOne();
            $old_image = $this->product->image;
            
            // Gán các thuộc tính mới từ POST
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->price = $_POST['price'];
            $this->product->quantity = !empty($_POST['quantity']) ? $_POST['quantity'] : 0;
            $this->product->category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/products/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Delete old image if exists
                if (!empty($old_image)) {
                    $old_image_path = $upload_dir . $old_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $new_filename = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $this->product->image = $new_filename;
                }
            } else {
                // Không upload ảnh mới, giữ lại ảnh cũ
                $this->product->image = $old_image;
            }

            if ($this->product->update()) {
                // Cập nhật nhà cung cấp
                if (!empty($_POST['supplier_id'])) {
                    // Xóa tất cả nhà cung cấp cũ
                    $stmt = $this->db->prepare("DELETE FROM product_suppliers WHERE product_id = ?");
                    $stmt->execute([$this->product->id]);
                    
                    // Thêm nhà cung cấp mới
                    $supplier_id = $_POST['supplier_id'];
                    $stmt = $this->db->prepare("INSERT INTO product_suppliers (product_id, supplier_id, price) VALUES (?, ?, ?)");
                    $stmt->execute([$this->product->id, $supplier_id, $this->product->price]);
                }
                
                header('Location: index.php?action=products');
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->product->id = $_GET['id'];
            
            // Delete product image if exists
            if ($this->product->readOne() && !empty($this->product->image)) {
                $image_path = 'uploads/products/' . $this->product->image;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            if ($this->product->delete()) {
                header('Location: index.php?action=products');
                exit();
            }
        }
    }

    public function statistics() {
        $stats = $this->product->getStatistics();
        $category_stats = $this->product->getCategoryStatistics();
        $monthly_stats = $this->product->getMonthlyStatistics();
        $top_selling_categories = $this->product->getTopSellingCategories();
        $category_distribution = $this->product->getCategoryDistribution();
        
        // Thay đổi thống kê xu hướng giá trị thành thống kê chi phí nhập hàng
        $monthly_trend = $this->product->getMonthlyImportCostTrend();
        
        $top_stock_products = $this->product->getTopStockProducts(10);
        $top_suppliers_by_value = $this->product->getTopSuppliersByValue(999);
        
        // Chuẩn bị dữ liệu cho biểu đồ
        $chart_data = [
            'category_labels' => [],
            'category_values' => [],
            'monthly_labels' => [],
            'monthly_values' => []
        ];
        
        foreach ($category_distribution as $category) {
            $chart_data['category_labels'][] = $category['category_name'];
            $chart_data['category_values'][] = $category['product_count'];
        }
        
        foreach ($monthly_trend as $month) {
            $chart_data['monthly_labels'][] = date('m/Y', strtotime($month['month'] . '-01'));
            $chart_data['monthly_values'][] = $month['total_import_cost'];
        }
        
        require_once 'views/products/statistics.php';
    }

    public function importForm() {
        // Lấy danh sách sản phẩm với thông tin nhà cung cấp
        $query = "SELECT DISTINCT p.*, 
                        GROUP_CONCAT(DISTINCT s.id) as supplier_ids,
                        GROUP_CONCAT(DISTINCT s.name) as supplier_names,
                        GROUP_CONCAT(DISTINCT ps.price) as supplier_prices
                 FROM products p
                 LEFT JOIN product_suppliers ps ON p.id = ps.product_id
                 LEFT JOIN suppliers s ON ps.supplier_id = s.id
                 GROUP BY p.id, p.name, p.description, p.price, p.quantity, p.image, p.category_id
                 ORDER BY p.name ASC";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Chuyển đổi chuỗi supplier_ids, supplier_names và supplier_prices thành mảng
        foreach ($products as &$product) {
            $supplier_ids = !empty($product['supplier_ids']) ? explode(',', $product['supplier_ids']) : [];
            $supplier_names = !empty($product['supplier_names']) ? explode(',', $product['supplier_names']) : [];
            $supplier_prices = !empty($product['supplier_prices']) ? explode(',', $product['supplier_prices']) : [];
            $product['suppliers'] = [];
            
            for ($i = 0; $i < count($supplier_ids); $i++) {
                if (!empty($supplier_ids[$i])) {
                    $product['suppliers'][] = [
                        'id' => $supplier_ids[$i],
                        'name' => $supplier_names[$i],
                        'price' => $supplier_prices[$i] ?? 0
                    ];
                }
            }
        }
        
        require_once 'views/products/import_form.php';
    }

    public function importStore() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $supplier_id = $_POST['supplier_id'];
            $import_quantity = (int)$_POST['import_quantity'];
            $import_price = (float)$_POST['import_price'];
            // Tăng tồn kho
            $this->product->id = $product_id;
            if ($this->product->readOne()) {
                $new_quantity = $this->product->quantity + $import_quantity;
                $stmt = $this->db->prepare("UPDATE products SET quantity = ? WHERE id = ?");
                $stmt->execute([$new_quantity, $product_id]);
            }
            // Cập nhật hoặc thêm mới product_suppliers
            $stmt = $this->db->prepare("SELECT * FROM product_suppliers WHERE product_id = ? AND supplier_id = ?");
            $stmt->execute([$product_id, $supplier_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                // Cộng dồn số lượng nhập và cập nhật giá nhập
                $new_import_quantity = $row['import_quantity'] + $import_quantity;
                $stmt = $this->db->prepare("UPDATE product_suppliers SET import_quantity = ?, price = ?, created_at = NOW() WHERE product_id = ? AND supplier_id = ?");
                $stmt->execute([$new_import_quantity, $import_price, $product_id, $supplier_id]);
            } else {
                // Thêm mới dòng product_suppliers
                $stmt = $this->db->prepare("INSERT INTO product_suppliers (product_id, supplier_id, price, import_quantity, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$product_id, $supplier_id, $import_price, $import_quantity]);
            }
            header('Location: index.php?action=products');
            exit();
        }
    }

    public function importCostStatistics() {
        $db = (new Database())->getConnection();
        $product = new Product($db);
        
        // Lấy thống kê chi phí nhập hàng theo tháng
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    SUM(import_price * quantity) as total_import_cost
                FROM products 
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC";
                
        $stmt = $db->prepare($query);
        $stmt->execute();
        $statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $current_page = 'import_statistics';
        ob_start();
        ?>
        <div class="container-fluid">
            <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i>Thống kê chi phí nhập hàng</h2>
            
            <div class="card">
                <div class="card-body">
                    <canvas id="importCostChart"></canvas>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Bảng thống kê chi tiết</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tháng</th>
                                    <th>Tổng chi phí nhập hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistics as $stat): ?>
                                <tr>
                                    <td><?= date('m/Y', strtotime($stat['month'] . '-01')) ?></td>
                                    <td><?= number_format($stat['total_import_cost'], 0, ',', '.') ?> VNĐ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('importCostChart').getContext('2d');
            const data = <?= json_encode($statistics) ?>;
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => {
                        const date = new Date(item.month + '-01');
                        return date.toLocaleDateString('vi-VN', { month: 'short', year: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Chi phí nhập hàng',
                        data: data.map(item => item.total_import_cost),
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Biểu đồ chi phí nhập hàng theo tháng'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        });
        </script>
        <?php
        $content = ob_get_clean();
        require_once 'views/layouts/admin.php';
    }

    public function import_product() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new Product($this->db);
            $product->id = $_POST['id'];
            
            // Kiểm tra xem sản phẩm có tồn tại không
            if (!$product->checkProductExists()) {
                // Nếu là sản phẩm mới, cần có giá
                if (!isset($_POST['price']) || empty($_POST['price'])) {
                    $_SESSION['error'] = "Vui lòng nhập giá cho sản phẩm mới";
                    header("Location: index.php?action=import_product&id=" . $product->id);
                    exit;
                }
                $product->price = $_POST['price'];
            }
            
            $quantity = $_POST['quantity'];
            
            if ($product->import($quantity)) {
                $_SESSION['success'] = "Nhập kho thành công";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi nhập kho";
            }
        }
        
        header("Location: index.php?action=products");
        exit;
    }
} 