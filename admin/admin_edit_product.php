<?php
session_start(); // Khởi động session

// Kiểm tra xem người dùng đã đăng nhập chưa và có phải là admin không
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    // Nếu không phải admin, chuyển hướng về trang login hoặc thông báo lỗi
    header('Location: /user/login.php');
    exit;
}

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fptshop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin sản phẩm để chỉnh sửa
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;
$sql = "SELECT id, name, category, price, description, image FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Kiểm tra nếu không có sản phẩm
if (!$product) {
    die("Sản phẩm không tồn tại.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Xử lý upload hình ảnh
    if ($image) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Cập nhật sản phẩm trong cơ sở dữ liệu
    $update_sql = "UPDATE products SET name = ?, category = ?, price = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdssi", $name, $category, $price, $description, $image, $product_id);
    $stmt->execute();
    
    // Chuyển hướng sau khi cập nhật thành công
    header("Location: /user/products.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sản Phẩm</title>
</head>
<body>
    <h2>Chỉnh Sửa Sản Phẩm</h2>
    <form action="admin_edit_product.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="name">Tên Sản Phẩm:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

        <label for="category">Danh Mục:</label><br>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required><br><br>

        <label for="price">Giá:</label><br>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>

        <label for="description">Mô Tả:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

        <label for="image">Chọn Hình Ảnh Mới:</label><br>
        <input type="file" id="image" name="image" accept="image/*"><br><br>

        <input type="submit" value="Cập Nhật Sản Phẩm">
    </form>
</body>
</html>
