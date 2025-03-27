<?php
// Kiểm tra người dùng đã đăng nhập chưa
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /user/login.php");  // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    exit();
}

// Giả sử bạn đã có kết nối với cơ sở dữ liệu và các bảng như `users`
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$userId = $_SESSION['user_id'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - FPT Shop</title>
    <link rel="stylesheet" href="/assets/styles.css"> 
    <style>
        /* CSS cho phần Footer */
        footer {
            background-color: #f8f8f8;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
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
    </style>
    <script>
        
        window.addEventListener("DOMContentLoaded", function() {
            // Thêm hiệu ứng cho các nút khi hover
            const buttons = document.querySelectorAll(".btn, .admin-actions a");

            buttons.forEach(button => {
                button.addEventListener("mouseenter", function() {
                    this.style.transform = "scale(1.1)";
                });
                button.addEventListener("mouseleave", function() {
                    this.style.transform = "scale(1)";
                });
            });
        });
    </script>
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
                    <li><a href="/user/logout.php">Đăng xuất</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="profile-container">
        <h2>Thông tin cá nhân</h2>

        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" id="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" value="********" readonly>
        </div>

        <a href="/user/edit_profile.php" class="btn">Chỉnh sửa thông tin</a>

        <!-- Kiểm tra nếu người dùng là admin -->
        <?php if ($user['role'] == 'admin'): ?>
            <div class="admin-actions">
                <h3>Quản lý sản phẩm</h3>
                <a href="/add_product.php" class="btn">Thêm sản phẩm</a>
                <a href="/admin/manage_products.php" class="btn">Quản lý sản phẩm</a>
            </div>
        <?php endif; ?>
    </div>
    
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
