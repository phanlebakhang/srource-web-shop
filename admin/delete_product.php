<?php
// Kết nối cơ sở dữ liệu
// config.php
$servername = "localhost"; // hoặc IP của máy chủ cơ sở dữ liệu
$username = "root"; // Tên đăng nhập
$password = ""; // Mật khẩu
$dbname = "fptshop"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu ID được truyền vào URL
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Sản phẩm không hợp lệ!";
    exit();
}

$product_id = $_GET['id'];

// Truy vấn để lấy hình ảnh của sản phẩm
$sql_images = "SELECT image_path FROM product_images WHERE product_id = ?";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();
$result_images = $stmt_images->get_result();

// Xóa các hình ảnh liên quan đến sản phẩm từ thư mục
while ($image = $result_images->fetch_assoc()) {
    // Xóa ảnh phụ
    $additional_image_path = $_SERVER['DOCUMENT_ROOT'] . '/image/' . $image['image_path'];
    if (file_exists($additional_image_path)) {
        unlink($additional_image_path); // Xóa ảnh phụ
    }
}

// Xóa hình ảnh trong bảng product_images
$sql_delete_images = "DELETE FROM product_images WHERE product_id = ?";
$stmt_delete_images = $conn->prepare($sql_delete_images);
$stmt_delete_images->bind_param("i", $product_id);
$stmt_delete_images->execute();

// Xóa sản phẩm khỏi cơ sở dữ liệu
$sql_delete_product = "DELETE FROM products WHERE id = ?";
$stmt_delete_product = $conn->prepare($sql_delete_product);
$stmt_delete_product->bind_param("i", $product_id);
$stmt_delete_product->execute();

if ($stmt_delete_product->affected_rows > 0) {
    echo "Sản phẩm và các hình ảnh liên quan đã được xóa!";
    header("Location: /admin/manage_products.php");
    exit();
} else {
    echo "Lỗi khi xóa sản phẩm!";
}

$conn->close();
?>
