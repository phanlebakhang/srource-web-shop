<?php
// Kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Lấy thông tin sản phẩm từ ID
$sql = "SELECT id, name, category_id, price_vnd, description, image, brand FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra xem có dữ liệu trả về không
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("Sản phẩm không tồn tại.");
}

// Truy vấn các hình ảnh phụ của sản phẩm
$sql_images = "SELECT image_path FROM product_images WHERE product_id = ?";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();
$additional_images_result = $stmt_images->get_result();

// Lọc sản phẩm gợi ý: Theo danh mục và thương hiệu của sản phẩm hiện tại
$sql_suggestion = "SELECT id, name, price_vnd, image, brand FROM products WHERE category_id = ? AND brand = ? AND id != ?";

$stmt_suggestion = $conn->prepare($sql_suggestion);
$stmt_suggestion->bind_param("isi", $product['category_id'], $product['brand'], $product_id);
$stmt_suggestion->execute();
$suggestions = $stmt_suggestion->get_result();

// Kiểm tra nếu có kết quả trả về
$suggestions_data = [];
if ($suggestions && $suggestions->num_rows > 0) {
    while ($suggestion = $suggestions->fetch_assoc()) {
        $suggestions_data[] = $suggestion;
    }
}

// Đóng kết nối
$conn->close();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <style>
        /* CSS cho phần Footer */
        footer {
            background-color: #f8f8f8;
            padding: 40px 0;
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
            color: #555;
            text-decoration: none;
        }
        footer .menu li a:hover {
            text-decoration: underline;
        }

        /* CSS cho chi tiết sản phẩm */
        .product-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }

        .product-detail .product-image {
            width: 100%;
            max-width: 500px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            flex: 1;
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info .product-name {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-info .product-price {
            font-size: 1.5rem;
            color: #e74c3c;
            margin-bottom: 15px;
        }

        .product-info .product-description {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }

        .product-info .buy-button {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            font-size: 1.2rem;
            text-decoration: none;
        }

        .product-info .buy-button:hover {
            background-color: #c0392b;
        }

        .additional-images {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .additional-images img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .additional-images img:hover {
            transform: scale(1.1);
        }

        .suggestions {
            margin-top: 40px;
            text-align: center;
        }

        .suggestions h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .suggestions-list {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .suggestion-item {
            width: 200px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            padding: 10px;
            transition: transform 0.3s ease;
        }

        .suggestion-item:hover {
            transform: translateY(-5px);
        }

        .suggestion-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .suggestion-item .name {
            font-weight: bold;
            margin: 10px 0;
        }

        .suggestion-item .price {
            color: #e74c3c;
            margin-bottom: 15px;
        }

        .suggestion-item .buy-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .suggestion-item .buy-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="tong">
        <a href="/index.php">Trang Chủ</a>
        <a href="/shop/products.php">Sản Phẩm</a>
        <a href="/shop/cart.php">Giỏ Hàng</a>
        <?php if ($is_logged_in): ?>
            <!-- Nếu đã đăng nhập thì ẩn các liên kết Đăng Nhập và Đăng Ký -->
        <?php else: ?>
            <a href="/user/login.php">Đăng Nhập</a>
            <a href="/user/register.php">Đăng Ký</a>
        <?php endif; ?>
    </div>

    <div class="product-detail">
        <!-- Hiển thị ảnh chính của sản phẩm -->
        <img src="/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        
        <div class="product-info">
            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
            <div class="product-price"><?php echo number_format($product['price_vnd'], 0, ',', '.') . " VNĐ"; ?></div>
            <div class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>
            <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>

            <a href="/shop/add_to_cart.php?id=<?php echo $product['id']; ?>" class="buy-button">Thêm Vào Giỏ Hàng</a>
        </div>
    </div>

    <!-- Hình ảnh phụ của sản phẩm -->
    <?php if ($additional_images_result->num_rows > 0): ?>
        <div class="additional-images">
            <?php while ($image = $additional_images_result->fetch_assoc()): ?>
                <img src="/images/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Ảnh phụ">
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <div class="suggestions">
    <h3>Sản phẩm gợi ý</h3>
    <div class="suggestions-list">
        <?php if (count($suggestions_data) > 0): ?>
            <?php foreach ($suggestions_data as $suggestion): ?>
                <div class="suggestion-item">
                    <img src="/images/<?php echo htmlspecialchars($suggestion['image']); ?>" alt="<?php echo htmlspecialchars($suggestion['name']); ?>">
                    <div class="name"><?php echo htmlspecialchars($suggestion['name']); ?></div>
                    <div class="price"><?php echo number_format($suggestion['price_vnd'], 0, ',', '.') . " VNĐ"; ?></div>
                    <a href="product_detail.php?id=<?php echo $suggestion['id']; ?>" class="buy-button">Xem Chi Tiết</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm gợi ý.</p>
        <?php endif; ?>
    </div>
</div>

    <footer>
        <div class="col-3 col-md-6">
            <h3 class="footer-head">Thiết Kế Web</h3>
            <ul class="menu">
                <li><a href="#">Họ và Tên: Phan Lê Bá Khang , Nguyễn Tuần Khôi, Phan Văn Bao, Phạm Trung Can, Trần Tiểu Linh, Nguyễn Đại Gia </a></li>
                <li><a href="#">Lớp: CCNTT 24A </a></li>
                <li><a href="#">Giảng viên: Lương Minh Giang</a></li>
            </ul>
        </div>
        <div class="col-7 col-md-6">
            <h3 class="footer-head">Trường Cao đẳng Kinh tế - Kỹ thuật Cần Thơ</h3>
            <ul class="menu">
                <li><a href="#">Điện thoại: (84-0292) 3826072</a></li>
                <li><a href="#">Email: ktktct@ctec.edu.vn</a></li>
                <li><a href="#">Địa chỉ: Số 9, đường Cách mạng Tháng tám, Phường An Hòa, Quận Ninh Kiều, TP. Cần Thơ</a></li>
            </ul>
        </div>
    </footer>

</body>
</html>
