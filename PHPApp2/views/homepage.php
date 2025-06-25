<?php
// Lấy 4 sản phẩm cũ nhất
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$product = new Product($conn);
$stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at ASC LIMIT 4");
$stmt->execute();
$oldestProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ | FengSystem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&display=swap" rel="stylesheet">
    <style>
        body {
            background: #fff;
            color: #111;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .navbar {
            background: #fff;
            box-shadow: none;
            border-bottom: 1.5px solid #eee;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 900;
            font-size: 2rem;
            letter-spacing: 2px;
            color: #111 !important;
        }
        .nav-link {
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0 16px;
            color: #111 !important;
            transition: color 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: #000;
            text-decoration: underline;
        }
        .navbar-nav {
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logout-btn {
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 700;
            border: 2px solid #111;
            border-radius: 4px;
            padding: 6px 18px;
            margin-left: 16px;
            background: #fff;
            color: #111 !important;
            transition: background 0.2s, color 0.2s;
        }
        .logout-btn:hover {
            background: #111;
            color: #fff !important;
        }
        .login-btn {
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 700;
            border: 2px solid #111;
            border-radius: 24px;
            padding: 6px 22px;
            margin-left: 12px;
            background: #111;
            color: #fff !important;
            text-decoration: none !important;
            transition: background 0.2s, color 0.2s, border 0.2s;
            box-shadow: none;
            display: inline-block;
        }
        .login-btn:hover {
            background: #fff;
            color: #111 !important;
            border: 2px solid #111;
            text-decoration: none !important;
        }
        .hero-banner {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            overflow: hidden;
            min-height: 60vh;
            max-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-banner img {
            width: 100vw;
            height: 70vh;
            object-fit: cover;
            filter: brightness(0.85);
        }
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-align: center;
            text-shadow: 0 2px 16px rgba(0,0,0,0.4);
        }
        .hero-content h1 {
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: 2px;
        }
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }
        .btn-main {
            background: #fff;
            color: #111;
            border: 2px solid #111;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 36px;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        .btn-main:hover {
            background: #111;
            color: #fff;
        }
        .section-title {
            font-size: 2.2rem;
            font-weight: 900;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .about-section {
            padding: 60px 0 40px 0;
            background: #fafafa;
        }
        .about-section .about-text {
            font-size: 1.1rem;
            line-height: 1.7;
        }
        .about-img {
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            width: 100%;
            max-width: 500px;
        }
        .products-section {
            padding: 60px 0 40px 0;
        }
        .product-card {
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1.5px solid #111;
            background: #fff;
            color: #111;
            transition: box-shadow 0.2s, transform 0.2s;
            overflow: hidden;
        }
        .product-card img {
            height: 260px;
            object-fit: cover;
            width: 100%;
        }
        .product-card .card-body {
            text-align: center;
        }
        .product-card .card-title {
            font-weight: 700;
            font-size: 1.1rem;
        }
        .product-card .card-text {
            font-size: 1rem;
        }
        .product-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            transform: translateY(-6px) scale(1.03);
        }
        .contact-section {
            background: #fafafa;
            padding: 60px 0;
        }
        .video-container {
            margin-bottom: 40px;
            text-align: center;
        }
        .video-container iframe {
            max-width: 100%;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .footer {
            background: #fff;
            color: #111;
            border-top: 1.5px solid #111;
            padding: 24px 0 12px 0;
            text-align: center;
            font-size: 1rem;
        }
        @media (max-width: 768px) {
            .hero-content h1 { font-size: 2rem; }
            .hero-banner img { height: 40vh; }
            .about-img { max-width: 100%; }
        }
        .video-section {
            padding: 60px 0;
            background: #fff;
        }
        .video-container {
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
        }
        .video-container iframe {
            max-width: 100%;
            width: 900px;
            height: 506px;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        @media (max-width: 992px) {
            .video-container iframe {
                width: 100%;
                height: 400px;
            }
        }
        @media (max-width: 576px) {
            .video-container iframe {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">FengSystem</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Liên hệ</a></li>
                </ul>
                <a class="login-btn" href="index.php?action=login">Đăng nhập</a>
            </div>
        </div>
    </nav>
    <section class="hero-banner">
        <img src="uploads/products/1684462193-lim-logo-08.avif" alt="Banner FengSystem">
        <div class="hero-content">
            <h1>Chào mừng đến với FengSystem</h1>
            <p>Khám phá bộ sưu tập thời trang mới nhất của chúng tôi, nơi phong cách và chất lượng gặp nhau.</p>
            <a href="#products" class="btn btn-main">Khám phá sản phẩm</a>
        </div>
    </section>
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 mb-4 mb-lg-0 text-center">
                    <img src="uploads\products\4c8ff5e63eb198f217f16020c6de5794.jpg" class="about-img" alt="Giới thiệu FengSystem">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title">About Us</h2>
                    <p class="about-text">FengSystem là thương hiệu thời trang hàng đầu, mang đến cho bạn những sản phẩm chất lượng cao với thiết kế tinh tế và hiện đại. Chúng tôi cam kết mang lại trải nghiệm mua sắm tuyệt vời cho khách hàng với dịch vụ tận tâm và sản phẩm đa dạng, phù hợp với mọi phong cách.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="products" class="products-section">
        <div class="container">
            <h2 class="section-title">On Sale</h2>
            <div class="row">
                <?php foreach ($oldestProducts as $product): ?>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card product-card h-100">
                        <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text text-muted">Giá: <?= number_format($product['price']) ?> đ</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Thêm section mới cho video -->
    <section class="video-section">
        <div class="container">
            <div class="video-container">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/3K6PRfAp6O4" 
                        title="YouTube video player" frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
            </div>
        </div>
    </section>
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title">Contact</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card p-4 shadow-sm border border-dark">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên</label>
                                <input type="text" class="form-control border border-dark" id="name" placeholder="Nhập họ tên">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control border border-dark" id="email" placeholder="Nhập email">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Nội dung</label>
                                <textarea class="form-control border border-dark" id="message" rows="3" placeholder="Nhập nội dung liên hệ"></textarea>
                            </div>
                            <button type="submit" class="btn btn-main w-100">Gửi liên hệ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="container">
            <span>&copy; <?= date('Y') ?> FengSystem. All rights reserved.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 