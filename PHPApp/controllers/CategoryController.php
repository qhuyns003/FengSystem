<?php
require_once 'models/Category.php';
require_once 'config/database.php';

class CategoryController {
    private $db;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->category = new Category($this->db);
    }

    public function index() {
        // Lấy trang hiện tại từ URL, mặc định là 1
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $items_per_page = 10;
        
        // Tính offset cho LIMIT trong SQL
        $offset = ($current_page - 1) * $items_per_page;
        
        // Lấy tổng số danh mục
        $total_categories = $this->category->count();
        
        // Lấy danh sách danh mục với phân trang
        $categories = $this->category->read($items_per_page, $offset)->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/categories/index.php';
    }

    public function create() {
        require_once 'views/categories/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];

            if ($this->category->create()) {
                header('Location: index.php?action=categories');
                exit();
            }
        }
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $this->category->id = $_GET['id'];
            if ($this->category->readOne()) {
                $category = $this->category;
                require_once 'views/categories/form.php';
            } else {
                header('Location: index.php?action=categories');
                exit();
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->id = $_POST['id'];
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];

            if ($this->category->update()) {
                header('Location: index.php?action=categories');
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->category->id = $_GET['id'];
            if ($this->category->delete()) {
                header('Location: index.php?action=categories');
                exit();
            }
        }
    }
} 