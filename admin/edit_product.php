<?php
// Kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');  

// Kiểm tra nếu ID được truyền vào URL
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Sản phẩm không hợp lệ!";
    exit();
}

$product_id = $_GET['id'];

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Sản phẩm không tồn tại!";
    exit();
}

$product = $result->fetch_assoc();

// Lấy danh sách các danh mục từ cơ sở dữ liệu để hiển thị trong dropdown
$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);

// Xử lý khi người dùng gửi thông tin chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra sự tồn tại của dữ liệu trong $_POST và gán giá trị mặc định nếu không có
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $price_vnd = isset($_POST['price']) ? $_POST['price'] : 0;  // Chỉnh sửa giá trị là `price_vnd`
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : 0;

    // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu, sử dụng `price_vnd` thay cho `price`
    $update_sql = "UPDATE products SET name = ?, price_vnd = ?, description = ?, category_id = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdssi", $name, $price_vnd, $description, $category_id, $product_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        echo "Sản phẩm đã được cập nhật!";
        header("Location: /admin/manage_products.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật sản phẩm!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Chỉnh sửa sản phẩm</h1>
    </div>
</header>

<div class="container">
    <form method="post">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>

        <label for="price">Giá (VNĐ):</label>
        <input type="text" name="price" id="price" value="<?= htmlspecialchars($product['price_vnd']) ?>" required><br>

        <label for="description">Mô tả:</label>
        <textarea name="description" id="description"><?= htmlspecialchars($product['description']) ?></textarea><br>

        <label for="category_id">Danh mục:</label>
        <select name="category_id" id="category_id" required>
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <button type="submit">Cập nhật sản phẩm</button>
    </form>

    <!-- Nút quay lại trang index.php -->
    <a href="/index.php" class="back-button">Quay lại trang chủ</a>
</div>

</body>
</html>

