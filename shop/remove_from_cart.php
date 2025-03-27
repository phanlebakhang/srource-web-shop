<?php
session_start();

// Kiểm tra nếu giỏ hàng có sản phẩm
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Kiểm tra nếu giỏ hàng có sản phẩm
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                // Xóa sản phẩm khỏi giỏ hàng
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }

    // Sau khi xóa, chuyển hướng về giỏ hàng
    header('Location: /shop/cart.php');
    exit();
}
?>
