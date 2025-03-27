<?php
session_start();

// Kiểm tra xem giỏ hàng có được khởi tạo hay không
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Khởi tạo giỏ hàng nếu chưa có
}

// Kiểm tra nếu có yêu cầu thêm sản phẩm vào giỏ
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];

    // Kết nối cơ sở dữ liệu để lấy thông tin sản phẩm
    include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    // Lấy thông tin sản phẩm từ bảng `products`
    $sql = "SELECT id, name, price FROM products WHERE id = " . $product_id;
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng, nếu có thì cập nhật số lượng
        $product_exists = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if (isset($cart_item['product_id']) && $cart_item['product_id'] == $product['id']) {
                $cart_item['quantity']++;
                $product_exists = true;
                break;
            }
        }

        // Nếu sản phẩm chưa có trong giỏ, thêm sản phẩm mới vào giỏ hàng
        if (!$product_exists) {
            $_SESSION['cart'][] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    $conn->close();

    // Chuyển hướng về trang giỏ hàng
    header("Location: cart.php");
    exit;
}

// Kiểm tra nếu có yêu cầu xóa sản phẩm khỏi giỏ
if (isset($_GET['remove_from_cart'])) {
    $product_id_to_remove = $_GET['remove_from_cart'];

    // Duyệt qua giỏ hàng và xóa sản phẩm theo ID
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if (isset($cart_item['product_id']) && $cart_item['product_id'] == $product_id_to_remove) {
            unset($_SESSION['cart'][$key]);  // Xóa sản phẩm khỏi giỏ hàng
            break;
        }
    }

    // Đảm bảo giỏ hàng không bị đánh số lại không mong muốn
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Tính tổng giá trị giỏ hàng
$total_price = 0;
foreach ($_SESSION['cart'] as $cart_item) {
    // Kiểm tra và đảm bảo 'price' và 'quantity' là số
    $price = isset($cart_item['price']) ? floatval($cart_item['price']) : 0;
    $quantity = isset($cart_item['quantity']) ? intval($cart_item['quantity']) : 0;
    
    // Tính tổng cho từng sản phẩm
    $total_price += $price * $quantity;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - FPT Shop</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

    <div class="navbar">
        <a href="/index.php">Trang Chủ</a>
        <a href="/shop/products.php">Sản Phẩm</a>
        <a href="/shop/cart.php">Giỏ Hàng</a>
    </div>

    <div class="cart-container">
        <h1>Giỏ Hàng của bạn</h1>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Giỏ hàng của bạn đang trống.</p>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($_SESSION['cart'] as $cart_item) {
                        $name = isset($cart_item['name']) ? htmlspecialchars($cart_item['name']) : 'Sản phẩm không xác định';
                        $price = isset($cart_item['price']) ? floatval($cart_item['price']) : 0;
                        $quantity = isset($cart_item['quantity']) ? intval($cart_item['quantity']) : 0;
                        $total = $price * $quantity;

                        // Hiển thị thông tin giỏ hàng
                        echo '<tr>';
                        echo '<td>' . $name . '</td>';
                        echo '<td>' . number_format($price, 0, ',', '.') . ' VNĐ</td>';
                        echo '<td>' . $quantity . '</td>';
                        echo '<td>' . number_format($total, 0, ',', '.') . ' VNĐ</td>';
                        echo '<td><a href="/shop/cart.php?remove_from_cart=' . (isset($cart_item['product_id']) ? $cart_item['product_id'] : '') . '" class="remove-button">Xóa</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <div class="total-price">
                Tổng cộng: <?php echo number_format($total_price, 0, ',', '.') . ' VNĐ'; ?>
            </div>

            <a href="/shop/checkout.php" class="buy-button">Tiến Hành Thanh Toán</a>
        <?php endif; ?>
    </div>

</body>
</html>
