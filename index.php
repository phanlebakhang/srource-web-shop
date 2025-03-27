<?php
session_start(); // Khởi tạo session

// Kết nối tới cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Thay đổi nếu cần
$password = ""; // Thay đổi nếu cần
$dbname = "fptshop"; // Thay đổi tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập chưa
$is_logged_in = isset($_SESSION['username']); // Kiểm tra nếu có thông tin người dùng trong session

// Kiểm tra xem người dùng có phải là admin không
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Kiểm tra role là 'admin'
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB TCEGY</title>
    <link rel="stylesheet" href="/styless.css">
    <link rel="stylesheet" href="/index.css">
</head>

<body>

    <!-- Header -->
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
                    <li><a href="/shop/order_details.php">Lịch sử mua hàng</a></li>

                    <?php if ($is_logged_in): ?>
                    <!-- Hiển thị tên người dùng đã đăng nhập -->
                    <li><a href="user/profile.php">Xin chào, <?php echo $_SESSION['username']; ?></a></li>

                    <!-- Nếu là admin, hiển thị liên kết để thêm sản phẩm -->
                    <?php if ($is_admin): ?>
                    <li><a href="/admin/add_product.php">Thêm sản phẩm</a></li>
                    <?php endif; ?>

                    <!-- Thêm link đăng xuất -->
                    <li><a href="user/logout.php">Đăng xuất</a></li>
                    <?php else: ?>
                    <!-- Nếu chưa đăng nhập, hiển thị link đăng nhập và đăng ký -->
                    <li><a href="user/login.php">Đăng nhập</a></li>
                    <li><a href="user/register.php">Đăng Ký</a></li>
                    <?php endif; ?>

                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <div class="carousel">
        <div class="carousel-images">
            <img src="/images/banner.png" alt="Hình 2" />
            <img src="/images/banner1.jpg" alt="Hình 3" />[]
            <img src="/images/banner2.jpg" alt="Hình 4" />
        </div>

        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <button class="next" onclick="nextSlide()">&#10095;</button>
    </div>

    <script src="/java/scriptt.js"></script>

    <!-- Danh mục sản phẩm -->
    <section class="categories">
        <div class="container">
            <h2>Danh mục sản phẩm</h2>
            <div class="category-list">
                <div class="category-item">
                    <a href="shop/products.php?category=1">
                        <img src="/images/iphone.jpg" alt="iPhone">
                        <p>iPhone</p>
                    </a>
                </div>
                <div class="category-item">
                    <a href="/shop/products.php?category=5">
                        <img src="/images/laptop.jpg" alt="Laptop">
                        <p>Laptop</p>
                    </a>
                </div>
                <div class="category-item">
                    <a href="/shop/products.php?category=9">
                        <img src="/images/apple-watch.jpg" alt="Apple Watch">
                        <p>Apple Watch</p>
                    </a>
                </div>
                <div class="category-item">
                    <a href="/shop/products.php?category=10">
                        <img src="/images/samsung.png" alt="Samsung">
                        <p>Samsung</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sản phẩm mới nhất -->
    <section class="featured-products">
        <div class="container">
            <h2>Sản phẩm mới nhất</h2>
            <div class="product-list">
                <?php
            // Lấy danh sách sản phẩm mới nhất
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4"; // Thêm sản phẩm mới nhất
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Lặp qua tất cả các sản phẩm
                while($row = $result->fetch_assoc()) {
                    $product_name = $row['name'];
                    $product_price_vnd = $row['price_vnd'];  // Đổi từ 'price' sang 'price_vnd'
                    $product_image = "images/" . $row['image'];  // Đảm bảo đường dẫn hình ảnh chính xác
                    $product_id = $row['id'];

                    // Nếu giá không có trong cơ sở dữ liệu, có thể hiển thị giá mặc định hoặc thông báo lỗi
                    if (empty($product_price_vnd)) {
                        $product_price_vnd = "Giá chưa có";
                    }

                    echo '<div class="product-item">';
                    echo '<img src="' . $product_image . '" alt="' . $product_name . '">';
                    echo '<h3>' . $product_name . '</h3>';
                    echo '<p>' . number_format($product_price_vnd, 0, ',', '.') . ' VND</p>'; // Hiển thị giá VND
                    echo '<a href="/shop/product_detail.php?id=' . $product_id . '">Xem chi tiết</a>';
                    echo '</div>';
                }
            } else {
                echo "Không có sản phẩm nào.";
            }
            ?>
            </div>
        </div>
    </section>

    <!-- Gợi ý sản phẩm -->
    <section class="suggested-products">
        <div class="container">
            <h2>Gợi ý sản phẩm cho bạn</h2>
            <!-- <marquee scrollamount="20" loop="10"
       width="100%" height="auto">  -->
            <div class="product-list">
                <?php
            // Lấy danh sách sản phẩm nổi bật (có thể là sản phẩm cùng danh mục hoặc được đánh giá cao)
            $sql = "SELECT * FROM products ORDER BY RAND() LIMIT 4"; // Lấy sản phẩm ngẫu nhiên
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Lặp qua các sản phẩm gợi ý
                while($row = $result->fetch_assoc()) {
                    $product_name = $row['name'];
                    $product_price_vnd = $row['price_vnd'];  // Đổi từ 'price' sang 'price_vnd'
                    $product_image = "images/" . $row['image'];
                    $product_id = $row['id'];

                    echo '<div class="product-item">';
                    echo '<img src="' . $product_image . '" alt="' . $product_name . '">';
                    echo '<h3>' . $product_name . '</h3>';
                    echo '<p>' . number_format($product_price_vnd, 0, ',', '.') . ' VND</p>';
                    echo '<a href="/shop/product_detail.php?id=' . $product_id . '">Xem chi tiết</a>';
                    echo '</div>';
                }
            } else {
                echo "Không có sản phẩm gợi ý.";
            }
            ?>

            </div>
            <!-- </marquee>
        <marquee scrollamount="20" loop="10"
       width="100%" height="auto">  -->
            <div class="product-list">
                <?php
            // Lấy danh sách sản phẩm nổi bật (có thể là sản phẩm cùng danh mục hoặc được đánh giá cao)
            $sql = "SELECT * FROM products ORDER BY RAND() LIMIT 4"; // Lấy sản phẩm ngẫu nhiên
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Lặp qua các sản phẩm gợi ý
                while($row = $result->fetch_assoc()) {
                    $product_name = $row['name'];
                    $product_price_vnd = $row['price_vnd'];  // Đổi từ 'price' sang 'price_vnd'
                    $product_image = "images/" . $row['image'];
                    $product_id = $row['id'];

                    echo '<div class="product-item">';
                    echo '<img src="' . $product_image . '" alt="' . $product_name . '">';
                    echo '<h3>' . $product_name . '</h3>';
                    echo '<p>' . number_format($product_price_vnd, 0, ',', '.') . ' VND</p>';
                    echo '<a href="/shop/product_detail.php?id=' . $product_id . '">Xem chi tiết</a>';
                    echo '</div>';
                }
            } else {
                echo "Không có sản phẩm gợi ý.";
            }
            ?>
            </div>
            <!-- </marquee> -->

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="col-3 col-md-6">
                <h3 class="footer-head">Thiết Kết Web</h3>
                <ul class="menu">
                    <li><a href="#">Họ và Tên: Phan Lê Bá Khang , Nguyễn Tuần Khôi, Phan Văn Bao, Phạm Trung Can, Trần
                            Tiểu Linh, Nguyễn Đại Gia </a></li>
                    <li><a href="#">Lớp: CCNTT 24A </a></li>
                    <li><a href="#">Giảng viên: Lương Minh Giang</a></li>
                </ul>
            </div>
            <div class="col-7 col-md-6">
                <h3 class="footer-head">Trường Cao đẳng Kinh tế - Kỹ thuật Cần Thơ</h3>
                <ul class="menu">
                    <li><a href="#">Điện thoại: (84-0292) 3826072</a></li>
                    <li><a href="#">Email: ktktct@ctec.edu.vn</a></li>
                    <li><a href="#">Địa chỉ: Số 9, đường Cách mạng Tháng tám, Phường An Hòa, Quận Ninh Kiều, TP. Cần
                            Thơ</a></li>
                </ul>
            </div>
        </div>
    </footer>


</body>

</html>