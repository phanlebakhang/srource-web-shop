<?php 
session_start();

include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem lịch sử mua hàng.'); window.location.href='/user/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Truy vấn để lấy các đơn hàng của người dùng
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);  // Sử dụng MySQLi để chuẩn bị câu truy vấn
$stmt->bind_param("i", $user_id);  // Liên kết tham số với câu truy vấn
$stmt->execute();  // Thực thi câu truy vấn
$result = $stmt->get_result();  // Lấy kết quả truy vấn

// Chuyển đổi kết quả thành mảng
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng - FPT Shop</title>
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
                <li><a href="/shop/order_history.php">Lịch sử mua hàng</a></li>
                <li><a href="/user/profile.php">Xin chào, <?php echo $_SESSION['username']; ?></a></li>
                <li><a href="/user/logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="order-history-section">
    <div class="container">
        <h2>Lịch sử mua hàng của bạn</h2>
        
        <?php if (empty($orders)): ?>
            <p>Bạn chưa có đơn hàng nào.</p>
        <?php else: ?>
            <table class="order-history-table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo number_format($order['total_amount'], 2); ?> VND</td>
                            <td><?php echo ucfirst($order['status']); ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                            <td><a href="/shop/order_details.php?order_id=<?php echo $order['id']; ?>">Xem chi tiết</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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
