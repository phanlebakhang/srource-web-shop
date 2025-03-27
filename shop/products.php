<?php
// Bắt đầu phiên làm việc
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm - FPT Shop</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script src="script.js" defer></script> <!-- Kết nối JS -->
    <style>
        /* CSS cho phần Footer */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Navbar */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .navbar a:hover {
            background-color: #575757;
        }

        /* Category Menu */
        .category-menu {
            display: flex;
            justify-content: center;
            background-color: #f2f2f2;
            padding: 10px 0;
        }

        .category-menu a {
            text-decoration: none;
            padding: 10px 20px;
            color: #555;
        }

        .category-menu a:hover {
            background-color: #ddd;
            color: #000;
        }

        /* Subcategory Menu */
        .subcategory-menu {
            display: flex;
            justify-content: center;
            background-color: #e7e7e7;
            padding: 10px 0;
        }

        .subcategory-menu a {
            text-decoration: none;
            padding: 10px 20px;
            color: #333;
        }

        .subcategory-menu a:hover {
            background-color: #ccc;
        }

        /* Product Container */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            padding: 20px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 20px;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
        }

        /* Ảnh nền mờ */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            padding: 20px;
            position: relative;
            background-image: url('/images/banner.jpg'), linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)); /* Đặt ảnh nền và hiệu ứng mờ mạnh hơn */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: 1;
            border-radius: 10px; 
        }

        .product-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); 
            z-index: -1; 
            border-radius: 10px; 
        }

        /* Sản phẩm */
        .product-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 20px;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s ease;
            z-index: 2;
        }

        /* Nội dung sản phẩm */
        .product-info {
            padding: 20px;
            text-align: center;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 1.1rem;
            color: #f29d35;
            margin-bottom: 10px;
        }

        .product-category {
            font-size: 1rem;
            color: #666;
            margin-bottom: 10px;
        }

        .buy-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .buy-button:hover {
            background-color: #45a049;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
            text-align: center;
        }

        footer .footer-head {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        footer .menu {
            list-style: none;
            padding: 0;
        }

        footer .menu li {
            margin: 5px 0;
        }

        footer .menu li a {
            color: #f8f8f8;
            text-decoration: none;
        }

        footer .menu li a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <div class="navbar">
        <a href="/index.php">Trang Chủ</a>
        <a href="/shop/products.php">Sản Phẩm</a>
        <a href="/shop/cart.php">Giỏ Hàng </a>
        <?php if (!$is_logged_in): ?>
            <a href="/user/login.php">Đăng Nhập</a>
            <a href="/user/register.php">Đăng Ký</a>
        <?php else: ?>
            <a href="/user/profile.php">Trang Cá Nhân</a>
            <a href="/user/logout.php">Đăng Xuất</a>
        <?php endif; ?>
    </div>

    <!-- Menu danh mục -->
    <div class="category-menu">
        <a href="/shop/products.php?category=6">Điện thoại</a> <!-- Gộp các điện thoại vào một danh mục -->
        <a href="/shop/products.php?category=4">Tivi</a>
        <a href="/shop/products.php?category=5">Laptop</a>
    </div>

    <!-- Thanh tìm kiếm -->
    <div class="search-container">
        <form action="/shop/products.php" method="get">
            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm...">
        </form>
    </div>

    <div class="product-container">
        <?php
        // Kết nối cơ sở dữ liệu và lấy danh sách sản phẩm
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "fptshop";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        // Lấy các tham số từ URL
        $category_id = isset($_GET['category']) ? $_GET['category'] : null;
        $brand = isset($_GET['brand']) ? $_GET['brand'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        // Xây dựng câu lệnh SQL tùy thuộc vào các tham số
        $sql = "SELECT p.id, p.name, p.price_VND, p.description, p.image, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE 1";

        if ($category_id) {
            $sql .= " AND p.category_id = " . $category_id;
        }

        // Kiểm tra nếu có tham số brand và thêm vào truy vấn
        if ($brand) {
            $sql .= " AND p.brand = '" . $conn->real_escape_string($brand) . "'";
        }

        // Thêm điều kiện tìm kiếm nếu có
        if ($search) {
            $sql .= " AND p.name LIKE '%" . $conn->real_escape_string($search) . "%'";
        }

        $result = $conn->query($sql);
        $conn->close();

        // Hiển thị sản phẩm
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="product-card">
                    <img src="/images/' . $row['image'] . '" alt="' . $row['name'] . '" class="product-image">
                    <div class="product-info">
                        <div class="product-name">' . htmlspecialchars($row['name']) . '</div>
                        <div class="product-price">' . number_format($row['price_VND'], 0, ',', '.') . ' VNĐ</div>
                        <div class="product-category">Danh mục: ' . htmlspecialchars($row['category_name']) . '</div>
                        <a href="/shop/product_detail.php?id=' . $row['id'] . '" class="buy-button">Xem Chi Tiết</a>
                    </div>
                </div>';
            }
        } else {
            echo '<p>Không có sản phẩm nào để hiển thị.</p>';
        }
        ?>
    </div>

    <footer>
        <div class="container">
            <div class="col-3 col-md-6">
                <h3 class="footer-head">Thiết Kết Web</h3>
                <ul class="menu">
                    <li><a href="#">Họ và Tên: Phan Lê Bá Khang , Nguyễn Tuần Khôi, Phan Văn Bao, Phạm Trung Can, Trần Tiểu Linh, Nguyễn Đại Gia</a></li>
                    <li><a href="#">Lớp: CCNTT 24A</a></li>
                    <li><a href="#">Giảng viên: Lương Minh Giang</a></li>
                </ul>
            </div>
            <div class="col-7 col-md-6">
                <h3 class="footer-head">Trường Cao đẳng Kinh tế - Kỹ thuật Cần Thơ</h3>
                <ul class="menu">
                    <li><a href="#">Điện thoại: (84-0292)   3826072</a></li>
                    <li><a href="#">Email: ktktct@ctec.edu.vn</a></li>
                    <li><a href="#">Địa chỉ: Khu 2, đường Nguyễn Văn Cừ, quận Ninh Kiều, TP. Cần Thơ</a></li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>
