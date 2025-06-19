<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Supplier.php';

class DashboardController {
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
        // Prepare heat map data
        $heatMapData = $this->getHeatMapData();
        
        // Prepare calendar events
        $calendarEvents = $this->getCalendarEvents();
        
        // Prepare rankings
        $productRankings = $this->getProductRankings();
        $supplierRankings = $this->getSupplierRankings();
        
        // Prepare smart notifications
        $notifications = $this->getSmartNotifications();
        
        require_once 'views/dashboard.php';
    }

    private function getHeatMapData() {
        $query = "SELECT 
                    DATE_FORMAT(ps.created_at, '%Y-%m-%d') as date,
                    COUNT(*) as count
                 FROM product_suppliers ps
                 WHERE ps.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
                 GROUP BY DATE_FORMAT(ps.created_at, '%Y-%m-%d')";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                $row['date'],
                $row['count']
            ];
        }
        
        return $data;
    }

    private function getCalendarEvents() {
        $query = "SELECT 
                    ps.created_at as start,
                    CONCAT('Nhập hàng: ', p.name, ' - ', ps.import_quantity, ' sản phẩm') as title,
                    '#3788d8' as backgroundColor
                 FROM product_suppliers ps
                 JOIN products p ON ps.product_id = p.id
                 WHERE ps.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                 UNION ALL
                 SELECT 
                    p.created_at as start,
                    CONCAT('Thêm mới: ', p.name) as title,
                    '#28a745' as backgroundColor
                 FROM products p
                 WHERE p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getProductRankings() {
        $query = "SELECT 
                    p.name,
                    SUM(ps.import_quantity) as quantity
                 FROM products p
                 JOIN product_suppliers ps ON p.id = ps.product_id
                 WHERE ps.created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                 GROUP BY p.id, p.name
                 ORDER BY quantity DESC
                 LIMIT 10";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getSupplierRankings() {
        $query = "SELECT 
                    s.name,
                    SUM(ps.price * ps.import_quantity) as value
                 FROM suppliers s
                 JOIN product_suppliers ps ON s.id = ps.supplier_id
                 WHERE ps.created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                 GROUP BY s.id, s.name
                 ORDER BY value DESC
                 LIMIT 10";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getSmartNotifications() {
        $notifications = [];
        
        // Check low stock products
        $query = "SELECT COUNT(*) as count 
                 FROM products 
                 WHERE quantity < 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $lowStock = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($lowStock['count'] > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Sản phẩm sắp hết hàng',
                'message' => "Có {$lowStock['count']} sản phẩm sắp hết hàng. Vui lòng kiểm tra và nhập thêm."
            ];
        }
        
        // Check recent imports
        $query = "SELECT COUNT(*) as count 
                 FROM product_suppliers 
                 WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $recentImports = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($recentImports['count'] > 0) {
            $notifications[] = [
                'type' => 'success',
                'icon' => 'fa-check-circle',
                'title' => 'Nhập hàng gần đây',
                'message' => "Đã nhập {$recentImports['count']} lô hàng trong 7 ngày qua."
            ];
        }
        
        // Check price changes
        $query = "SELECT COUNT(*) as count 
                 FROM product_suppliers 
                 WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 AND price != (
                     SELECT price 
                     FROM product_suppliers ps2 
                     WHERE ps2.product_id = product_suppliers.product_id 
                     AND ps2.supplier_id = product_suppliers.supplier_id
                     AND ps2.created_at < product_suppliers.created_at
                     ORDER BY ps2.created_at DESC 
                     LIMIT 1
                 )";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $priceChanges = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($priceChanges['count'] > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'fa-chart-line',
                'title' => 'Thay đổi giá',
                'message' => "Có {$priceChanges['count']} thay đổi về giá trong 30 ngày qua."
            ];
        }
        
        return $notifications;
    }
} 