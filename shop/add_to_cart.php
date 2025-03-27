<?php
// Kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// Lấy id sản phẩm từ URL
$product_id = $_GET['id'];

// Kiểm tra nếu giỏ hàng đã tồn tại trong session
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Lấy thông tin chi tiết sản phẩm từ cơ sở dữ liệu
$sql = "SELECT id, name, price_vnd FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra xem sản phẩm có tồn tại trong cơ sở dữ liệu không
$product = $result->fetch_assoc();
if ($product) {
    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // Nếu sản phẩm chưa có trong giỏ, thêm sản phẩm mới vào giỏ
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'name' => $product['name'],  // Lưu tên sản phẩm
            'price' => $product['price_vnd'], // Lưu giá sản phẩm
            'quantity' => 1
        ];
    }
} else {
    // Nếu sản phẩm không tồn tại trong cơ sở dữ liệu
    echo "Sản phẩm không tồn tại.";
    exit;
}

// Đóng kết nối
$conn->close();

// Chuyển hướng về trang sản phẩm sau khi thêm vào giỏ hàng
header("Location: /shop/products.php");
exit();
?>
