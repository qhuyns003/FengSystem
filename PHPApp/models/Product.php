<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $image;
    public $category_id;
    public $created_at;
    public $supplier_ids;
    public $supplier_names;
    public $supplier_prices;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 ORDER BY p.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readWithPagination($search = '', $category_id = '', $page = 1, $per_page = 10) {
        $offset = ($page - 1) * $per_page;
        
        $query = "SELECT p.*, c.name as category_name,
                        GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') as supplier_names,
                        GROUP_CONCAT(DISTINCT ps.price SEPARATOR ', ') as supplier_prices
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 LEFT JOIN product_suppliers ps ON p.id = ps.product_id
                 LEFT JOIN suppliers s ON ps.supplier_id = s.id
                 WHERE 1=1";
        
        $params = array();
        
        if (!empty($search)) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        if (!empty($category_id)) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        
        $query .= " GROUP BY p.id ORDER BY p.id ASC LIMIT $per_page OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalProducts($search = '', $category_id = '') {
        $query = "SELECT COUNT(*) as total 
                 FROM " . $this->table_name . " p
                 WHERE 1=1";
        $params = array();
        if (!empty($search)) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        if (!empty($category_id)) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name,
                    description = :description,
                    price = :price,
                    quantity = :quantity,
                    image = :image,
                    category_id = :category_id,
                    created_at = :created_at";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created_at", $this->created_at);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT p.*, c.name as category_name,
                        GROUP_CONCAT(DISTINCT s.id SEPARATOR ',') as supplier_ids,
                        GROUP_CONCAT(DISTINCT s.name SEPARATOR ',') as supplier_names,
                        GROUP_CONCAT(DISTINCT ps.price SEPARATOR ',') as supplier_prices
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 LEFT JOIN product_suppliers ps ON p.id = ps.product_id
                 LEFT JOIN suppliers s ON ps.supplier_id = s.id
                 WHERE p.id = ?
                 GROUP BY p.id
                 LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->quantity = $row['quantity'];
            $this->image = $row['image'];
            $this->category_id = $row['category_id'];
            $this->created_at = $row['created_at'];
            
            // Thêm thông tin nhà cung cấp
            $this->supplier_ids = $row['supplier_ids'] ? explode(',', $row['supplier_ids']) : array();
            $this->supplier_names = $row['supplier_names'] ? explode(',', $row['supplier_names']) : array();
            $this->supplier_prices = $row['supplier_prices'] ? explode(',', $row['supplier_prices']) : array();
            
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    name = :name,
                    description = :description,
                    price = :price,
                    quantity = :quantity,
                    image = :image,
                    category_id = :category_id
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getStatistics() {
        $stats = array();
        
        // Tổng số sản phẩm
        $query = "SELECT COUNT(*) as total_products FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_products'] = $row['total_products'];
        
        // Tổng giá trị hàng tồn kho
        $query = "SELECT SUM(price * quantity) as total_value FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_value'] = $row['total_value'] ?? 0;
        
        // Số lượng sản phẩm theo danh mục
        $query = "SELECT c.name as category_name, COUNT(p.id) as product_count 
                 FROM " . $this->table_name . " p  
                 LEFT JOIN categories c ON p.category_id = c.id
                 GROUP BY c.id, c.name
                 ORDER BY product_count DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['products_by_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Sản phẩm có số lượng thấp nhất (dưới 10)
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.quantity < 10
                 ORDER BY p.quantity ASC
                 LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['low_stock_products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Sản phẩm mới nhất
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 ORDER BY p.created_at DESC
                 LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['latest_products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    public function getCategoryStatistics() {
        $query = "SELECT 
                    c.name as category_name,
                    COUNT(p.id) as total_products,
                    SUM(p.quantity) as total_quantity,
                    SUM(p.price * p.quantity) as total_value,
                    AVG(p.price) as average_price
                 FROM categories c
                 LEFT JOIN " . $this->table_name . " p ON c.id = p.category_id
                 GROUP BY c.id, c.name
                 ORDER BY total_value DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyStatistics() {
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_products,
                    SUM(quantity) as total_quantity,
                    SUM(price * quantity) as total_value
                 FROM " . $this->table_name . "
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                 ORDER BY month DESC
                 LIMIT 12";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopSellingCategories($limit = 5) {
        $query = "SELECT 
                    c.name as category_name,
                    COUNT(p.id) as total_products,
                    SUM(p.quantity) as total_quantity,
                    SUM(p.price * p.quantity) as total_value
                 FROM categories c
                 LEFT JOIN " . $this->table_name . " p ON c.id = p.category_id
                 GROUP BY c.id, c.name
                 ORDER BY total_value DESC
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryDistribution() {
        $query = "SELECT 
                    c.name as category_name,
                    COUNT(p.id) as product_count
                 FROM categories c
                 LEFT JOIN " . $this->table_name . " p ON c.id = p.category_id
                 GROUP BY c.id, c.name
                 ORDER BY product_count DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyTrend() {
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_products,
                    SUM(quantity) as total_quantity,
                    SUM(price * quantity) as total_value
                 FROM " . $this->table_name . "
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                 ORDER BY month ASC
                 LIMIT 12";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSuppliers() {
        $query = "SELECT s.*, ps.price as supplier_price 
                 FROM suppliers s 
                 INNER JOIN product_suppliers ps ON s.id = ps.supplier_id 
                 WHERE ps.product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function addSupplier($supplier_id, $price) {
        $query = "INSERT INTO product_suppliers (product_id, supplier_id, price) 
                 VALUES (:product_id, :supplier_id, :price)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":product_id", $this->id);
        $stmt->bindParam(":supplier_id", $supplier_id);
        $stmt->bindParam(":price", $price);
        
        return $stmt->execute();
    }

    public function removeSupplier($supplier_id) {
        $query = "DELETE FROM product_suppliers 
                 WHERE product_id = :product_id AND supplier_id = :supplier_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":product_id", $this->id);
        $stmt->bindParam(":supplier_id", $supplier_id);
        
        return $stmt->execute();
    }

    public function updateSupplierPrice($supplier_id, $price) {
        $query = "UPDATE product_suppliers 
                 SET price = :price 
                 WHERE product_id = :product_id AND supplier_id = :supplier_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->bindParam(":supplier_id", $supplier_id);
        
        return $stmt->execute();
    }

    public function getTopStockProducts($limit = 5) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 ORDER BY p.quantity DESC
                 LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopSuppliersByValue($limit = 5) {
        $query = "SELECT s.id, s.name, 
                        COALESCE(SUM(ps.price * p.quantity), 0) as total_value, 
                        COUNT(DISTINCT p.id) as total_products
                 FROM suppliers s
                 LEFT JOIN product_suppliers ps ON s.id = ps.supplier_id
                 LEFT JOIN products p ON ps.product_id = p.id
                 GROUP BY s.id, s.name
                 ORDER BY total_value DESC
                 LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyImportCostTrend() {
        $query = "SELECT 
                    DATE_FORMAT(ps.created_at, '%Y-%m') as month,
                    SUM(ps.price * ps.import_quantity) as total_import_cost
                FROM product_suppliers ps
                GROUP BY DATE_FORMAT(ps.created_at, '%Y-%m')
                ORDER BY month ASC";
                
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLowStockProducts() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.quantity < 10
                 ORDER BY p.quantity ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewImports() {
        $query = "SELECT i.id, i.import_date, s.name as supplier_name
                 FROM imports i
                 JOIN suppliers s ON i.supplier_id = s.id
                 WHERE i.import_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                 ORDER BY i.import_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewlyImportedProducts() {
        $query = "SELECT p.*, c.name as category_name, 
                        MAX(ps.created_at) as last_import_date,
                        GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') as supplier_names
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 LEFT JOIN product_suppliers ps ON p.id = ps.product_id
                 LEFT JOIN suppliers s ON ps.supplier_id = s.id
                 GROUP BY p.id, c.name
                 ORDER BY last_import_date DESC
                 LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function import($quantity, $price) {
        try {
            $this->conn->beginTransaction();

            // Debug: In thông tin đầu vào
            error_log("Importing product - ID: " . $this->id . ", Quantity: " . $quantity . ", Price: " . $price . ", Supplier ID: " . $this->supplier_id);

            // Kiểm tra xem sản phẩm đã tồn tại chưa
            $query = "SELECT id, price FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                // Nếu sản phẩm chưa tồn tại, tạo mới với giá nhập
                $query = "INSERT INTO " . $this->table_name . " 
                        (id, name, description, price, quantity, category_id, created_at)
                        VALUES (:id, :name, :description, :price, :quantity, :category_id, NOW())";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":id", $this->id);
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":description", $this->description);
                $stmt->bindParam(":price", $price);
                $stmt->bindParam(":quantity", $quantity);
                $stmt->bindParam(":category_id", $this->category_id);
                $stmt->execute();
                error_log("Created new product");
            } else {
                // Nếu sản phẩm đã tồn tại, chỉ cập nhật số lượng
                $query = "UPDATE " . $this->table_name . "
                        SET quantity = quantity + :quantity
                        WHERE id = :id";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":quantity", $quantity);
                $stmt->bindParam(":id", $this->id);
                $stmt->execute();
                error_log("Updated existing product quantity");
            }

            // Cập nhật hoặc thêm mới trong bảng product_suppliers
            $query = "UPDATE product_suppliers 
                     SET price = :price, created_at = NOW()
                     WHERE product_id = :product_id AND supplier_id = :supplier_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":product_id", $this->id);
            $stmt->bindParam(":supplier_id", $this->supplier_id);
            $stmt->bindParam(":price", $price);
            $result = $stmt->execute();
            
            // Nếu không có bản ghi nào được cập nhật, thêm mới
            if ($stmt->rowCount() == 0) {
                $query = "INSERT INTO product_suppliers (product_id, supplier_id, price, created_at)
                         VALUES (:product_id, :supplier_id, :price, NOW())";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":product_id", $this->id);
                $stmt->bindParam(":supplier_id", $this->supplier_id);
                $stmt->bindParam(":price", $price);
                $result = $stmt->execute();
            }
            
            error_log("Updated product_suppliers - Result: " . ($result ? "Success" : "Failed"));

            $this->conn->commit();
            error_log("Transaction committed successfully");
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error in import: " . $e->getMessage());
            return false;
        }
    }

    public function checkProductExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function getImportHistory() {
        $query = "SELECT ih.*, p.name as product_name
                 FROM import_history ih
                 JOIN " . $this->table_name . " p ON ih.product_id = p.id
                 WHERE ih.product_id = :product_id
                 ORDER BY ih.import_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 