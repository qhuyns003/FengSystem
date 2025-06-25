<?php
require_once 'config/database.php';
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if (!isset($_SESSION['is_logged_in'])) {
    if ($action === 'login') {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['is_logged_in'] = true;
                header('Location: index.php');
                exit;
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
            }
        }
        include 'views/auth/login.php';
        exit;
    } else {
        include 'views/homepage.php';
        exit;
    }
}

require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Supplier.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/CategoryController.php';
require_once 'controllers/SupplierController.php';

switch ($action) {
    case 'logout':
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    case 'products':
        $controller = new ProductController();
        $controller->index();
        break;
    case 'create_product':
        $controller = new ProductController();
        $controller->create();
        break;
    case 'store_product':
        $controller = new ProductController();
        $controller->store();
        break;
    case 'edit_product':
        $controller = new ProductController();
        $controller->edit();
        break;
    case 'update_product':
        $controller = new ProductController();
        $controller->update();
        break;
    case 'delete_product':
        $controller = new ProductController();
        $controller->delete();
        break;
    case 'categories':
        $controller = new CategoryController();
        $controller->index();
        break;
    case 'create_category':
        $controller = new CategoryController();
        $controller->create();
        break;
    case 'store_category':
        $controller = new CategoryController();
        $controller->store();
        break;
    case 'edit_category':
        $controller = new CategoryController();
        $controller->edit();
        break;
    case 'update_category':
        $controller = new CategoryController();
        $controller->update();
        break;
    case 'delete_category':
        $controller = new CategoryController();
        $controller->delete();
        break;
    case 'suppliers':
        $controller = new SupplierController();
        $controller->index();
        break;
    case 'create_supplier':
        $controller = new SupplierController();
        $controller->create();
        break;
    case 'store_supplier':
        $controller = new SupplierController();
        $controller->store();
        break;
    case 'edit_supplier':
        $controller = new SupplierController();
        $controller->edit();
        break;
    case 'update_supplier':
        $controller = new SupplierController();
        $controller->update();
        break;
    case 'delete_supplier':
        $controller = new SupplierController();
        $controller->delete();
        break;
    case 'statistics':
        $controller = new ProductController();
        $controller->statistics();
        break;
    case 'import_form':
        $controller = new ProductController();
        $controller->importForm();
        break;
    case 'import_store':
        $controller = new ProductController();
        $controller->importStore();
        break;
    case 'import_statistics':
        $controller = new ProductController();
        $controller->importCostStatistics();
        break;
    default:
        // Dashboard view
        require_once 'views/dashboard.php';
        break;
} 