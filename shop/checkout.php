<?php
session_start();

// Kiểm tra nếu giỏ hàng trống
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Giỏ hàng của bạn không có sản phẩm.'); window.location.href='cart.php';</script>";
    exit;
}

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
$isLoggedIn = isset($_SESSION['username']) && !empty($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - FPT Shop</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>

    <header>
        <div class="container">
            <div class="logo">
                <img src="/images/banner4.jpg" alt="FPT Shop">
            </div>
            <nav>
                <ul>
                    <li><a href="/index.php">Trang chủ</a></li>
                    <li><a href="/shop/products.php">Sản phẩm</a></li>
                    <li><a href="/shop/cart.php">Giỏ hàng</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="user/profile.php">Xin chào, <?php echo $_SESSION['username']; ?></a></li>
                        <li><a href="user/logout.php">Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="/user/login.php">Đăng nhập</a></li>
                        <li><a href="/user/register.php">Đăng Ký</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <section class="cart-section">
        <div class="container">
            <h2>Giỏ hàng của bạn</h2>
            <div class="cart-items">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng cộng</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-body">
                        <?php
                        $totalAmount = 0;  // Biến để tính tổng giá trị giỏ hàng
                        // Kiểm tra nếu giỏ hàng tồn tại trong session
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                // Kiểm tra xem các chỉ mục 'price', 'name', 'cart_id' có tồn tại không
                                $price = isset($item['price']) ? $item['price'] : 0;
                                $name = isset($item['name']) ? $item['name'] : 'Không có tên sản phẩm';
                                $cart_id = isset($item['cart_id']) ? $item['cart_id'] : '';
                                $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
                                $totalPrice = $price * $quantity;

                                echo "<tr>
                                        <td>{$name}</td>
                                        <td>" . number_format($price) . " VND</td>
                                        <td>{$quantity}</td>
                                        <td>" . number_format($totalPrice) . " VND</td>
                                        <td><a href='/shop/remove_from_cart.php?id={$cart_id}' class='remove-button'>Xóa</a></td>
                                    </tr>";
                                $totalAmount += $totalPrice;  // Cộng dồn tổng giá trị
                            }
                        } else {
                            echo "<tr><td colspan='5'>Giỏ hàng trống!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="cart-summary">
                    <p>Tổng cộng: <strong class="total-amount"><?= number_format($totalAmount) ?> VND</strong></p>

                    <?php if ($isLoggedIn): ?>
                        <!-- Nếu người dùng đã đăng nhập, hiển thị nút Thanh toán -->
                        <a href="/shop/checkout.php" class="btn checkout-btn">Thanh toán</a>
                    <?php else: ?>
                        <!-- Nếu chưa đăng nhập, yêu cầu đăng nhập hoặc đăng ký -->
                        <a href="user/login.php" class="btn checkout-btn">Đăng nhập để thanh toán</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <footer>
    <div class="container">
    <div class="col-3 col-md-6 ">
                    <h3 class="footer-head">Thiết Kết Web</h3>
                    <ul class="menu">
                    <li><a href="#">Họ và Tên: Phan Lê Bá Khang , Nguyễn Tuần Khôi, Phan Văn Bao, Phạm Trung Can, Trần Tiểu Linh, Nguyễn Đại Gia </a></li>
                    <li><a href="#">Lớp: CCNTT 24A </a></li>
                        <!-- <li><a href="#">MSV:            </a></li> -->
                        <li><a href="#">Giảng viên: Lương Minh Giang</a></li>
                    </ul>
                </div>
                <div class="col-7 col-md-6">
                    <h3 class="footer-head">Trường Cao đẳng Kinh tế - Kỹ thuật Cần Thơ</h3>
                    <ul class="menu">
                        <li><a href="#"> Điện thoại: (84-0292)   3826072</a></li>
                        <li><a href="#">Email: ktktct@ctec.edu.vn</a></li>
                        <li><a href="#">Địa chỉ: Số 9, đường Cách mạng Tháng tám, Phường An Hòa, Quận Ninh Kiều, TP. Cần Thơ</a></li>
                    </ul>
    </div>
</footer>


</body>
</html>
