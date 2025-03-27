<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem chi tiết đơn hàng.'); window.location.href='/user/login.php';</script>";
    exit;
}

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if ($order_id === null) {
    echo "<script>alert('Mã đơn hàng không hợp lệ.'); window.location.href='/shop/order_history.php';</script>";
    exit;
}

// Truy vấn để lấy thông tin đơn hàng
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

// Kiểm tra nếu đơn hàng không tồn tại
if (!$order) {
    echo "<script>alert('Đơn hàng không tồn tại hoặc bạn không có quyền xem.'); window.location.href='/shop/order_history.php';</script>";
    exit;
}

// Lấy chi tiết các sản phẩm trong đơn hàng
$sql_items = "SELECT oi.*, p.name, p.price FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute([$order_id]);
$order_items = $stmt_items->fetchAll();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng - FPT Shop</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="FPT Shop">
        </div>
        <nav>
            <ul>
                <li><a href="/index.php">Trang chủ</a></li>
                <li><a href="/shop/products.php">Sản phẩm</a></li>
                <li><a href="/shopcart.php">Giỏ hàng</a></li>
                <li><a href="/shop/order_history.php">Lịch sử mua hàng</a></li>
                <li><a href="/user/profile.php">Xin chào, <?php echo $_SESSION['username']; ?></a></li>
                <li><a href="/user/logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="order-details-section">
    <div class="container">
        <h2>Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
        <p>Tổng tiền: <?php echo number_format($order['total_amount'], 2); ?> VND</p>
        <p>Trạng thái: <?php echo ucfirst($order['status']); ?></p>
        <p>Ngày đặt: <?php echo $order['created_at']; ?></p>

        <h3>Danh sách sản phẩm</h3>
        <table class="order-items-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng cộng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price'], 2); ?> VND</td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> VND</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<footer>
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

</footer>

</body>
</html>
