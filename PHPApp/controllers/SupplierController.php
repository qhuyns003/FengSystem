<?php
require_once 'models/Supplier.php';
require_once 'config/database.php';

class SupplierController {
    private $db;
    private $supplier;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->supplier = new Supplier($this->db);
    }

    public function index() {
        // Lấy trang hiện tại từ URL, mặc định là 1
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $items_per_page = 10;
        
        // Tính offset cho LIMIT trong SQL
        $offset = ($current_page - 1) * $items_per_page;
        
        // Lấy tổng số nhà cung cấp
        $total_suppliers = $this->supplier->count();
        
        // Lấy danh sách nhà cung cấp với phân trang
        $suppliers = $this->supplier->read($items_per_page, $offset)->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/suppliers/index.php';
    }

    public function create() {
        require_once 'views/suppliers/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->supplier->name = $_POST['name'];
            $this->supplier->email = $_POST['email'];
            $this->supplier->phone = $_POST['phone'];
            $this->supplier->address = $_POST['address'];
            $this->supplier->description = $_POST['description'];
            $this->supplier->status = isset($_POST['status']) ? 1 : 0;

            if ($this->supplier->create()) {
                header('Location: index.php?action=suppliers');
                exit();
            }
        }
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $this->supplier->id = $_GET['id'];
            if ($this->supplier->readOne()) {
                $supplier = $this->supplier;
                require_once 'views/suppliers/form.php';
            } else {
                header('Location: index.php?action=suppliers');
                exit();
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->supplier->id = $_POST['id'];
            $this->supplier->name = $_POST['name'];
            $this->supplier->email = $_POST['email'];
            $this->supplier->phone = $_POST['phone'];
            $this->supplier->address = $_POST['address'];
            $this->supplier->description = $_POST['description'];
            $this->supplier->status = isset($_POST['status']) ? 1 : 0;

            if ($this->supplier->update()) {
                header('Location: index.php?action=suppliers');
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->supplier->id = $_GET['id'];
            if ($this->supplier->delete()) {
                header('Location: index.php?action=suppliers');
                exit();
            }
        }
    }
} 