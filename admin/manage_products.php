<?php
// Kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/config.php'); 

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$sql = "SELECT products.id, products.name, products.price_vnd, products.description, categories.name AS category_name 
        FROM products 
        JOIN categories ON products.category_id = categories.id";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn: " . $conn->error); // Nếu có lỗi trong câu lệnh SQL
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="/assets/styles.css">  <!-- Liên kết đến file CSS -->
</head>
<body>

<header>
    <div class="container">
        <h1>Quản lý sản phẩm</h1>
    </div>
</header>

<div class="container">
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= number_format($product['price_vnd'], 0, ',', '.') ?> VND</td> <!-- Hiển thị price_vnd -->
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                    <td>
                        <a href="/admin/edit_product.php?id=<?= $product['id'] ?>">Chỉnh sửa</a> |
                        <a href="/admin/delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Nút quay lại trang chủ -->
    <a href="/index.php" class="back-button">Quay lại trang chủ</a>
</div>

</body>
</html>
