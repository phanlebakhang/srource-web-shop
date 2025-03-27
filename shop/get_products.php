<?php
// Kết nối tới cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Lấy danh mục từ URL (vd: laptop.php?category=laptop)
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Kiểm tra nếu category rỗng hoặc không hợp lệ
$valid_categories = ['laptop', 'iphone', 'samsung', 'apple-watch', 'redmi', 'tivi']; // Danh sách các danh mục hợp lệ

if (empty($category) || !in_array($category, $valid_categories)) {
    die("Danh mục không hợp lệ.");
}

// Truy vấn lấy sản phẩm theo category
$sql = "SELECT * FROM products WHERE category = '$category' ORDER BY id DESC";
$result = $conn->query($sql);

// Kiểm tra nếu có sản phẩm
if ($result->num_rows > 0) {
    // Lặp qua tất cả các sản phẩm
    while($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $product_price = $row['price'];
        $product_image = "images/" . $row['image'];  // Đảm bảo đường dẫn hình ảnh chính xác
        $product_id = $row['id'];
        echo '<div class="product-item">';
        echo '<img src="' . $product_image . '" alt="' . $product_name . '">';
        echo '<h3>' . $product_name . '</h3>';
        echo '<p>' . number_format($product_price, 0, ',', '.') . ' VND</p>';
        echo '<a href="product-detail.php?id=' . $product_id . '">Xem chi tiết</a>';
        echo '</div>';
    }
} else {
    echo "Không có sản phẩm nào trong danh mục này.";
}

// Đóng kết nối
$conn->close();
?>
