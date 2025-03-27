<?php
session_start();

// Bật hiển thị lỗi PHP để gỡ lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối đến cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');  // Đảm bảo kết nối CSDL ở đây
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    die("Bạn không có quyền truy cập vào trang này.");
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price_vnd = $_POST['price_vnd'];  // Đổi tên thành price_vnd
    $description = $_POST['description'];

    // Xử lý upload ảnh chính (hình ảnh sản phẩm)
    $image_main = $_FILES['image_main']['name'];  // Tên file ảnh chính
    $target_dir_main = "images/";  // Đảm bảo sử dụng thư mục images cho ảnh chính

    // Kiểm tra nếu thư mục images không tồn tại, tạo nó
    if (!file_exists($target_dir_main)) {
        mkdir($target_dir_main, 0777, true);  // Tạo thư mục nếu chưa có
    }

    // Đường dẫn của ảnh chính
    $target_file_main = $target_dir_main . basename($_FILES["image_main"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file_main, PATHINFO_EXTENSION));

    // Kiểm tra nếu tệp là hình ảnh thực sự
    if (isset($_FILES["image_main"]) && $_FILES["image_main"]["error"] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES["image_main"]["tmp_name"];
        $image_info = getimagesize($image_tmp_name);
        if ($image_info === false) {
            echo "Tệp không phải là hình ảnh.";
            $uploadOk = 0;
        } else {
            // Nếu là hình ảnh, kiểm tra kích thước file
            if ($_FILES["image_main"]["size"] > 5000000) {  // Giới hạn kích thước 5MB
                echo "Xin lỗi, tệp của bạn quá lớn.";
                $uploadOk = 0;
            }

            // Kiểm tra định dạng file ảnh (JPG, PNG, JPEG, GIF)
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
                echo "Xin lỗi, chỉ chấp nhận các định dạng JPG, JPEG, PNG & GIF.";
                $uploadOk = 0;
            }
        }
    } else {
        echo "Có lỗi xảy ra khi tải tệp lên.";
        $uploadOk = 0;
    }

    // Nếu không có lỗi, tiến hành upload
    if ($uploadOk == 0) {
        echo "Xin lỗi, tệp của bạn không được tải lên.";
    } else {
        // Tiến hành upload file chính
        if (move_uploaded_file($_FILES["image_main"]["tmp_name"], $target_file_main)) {
            echo "Tệp ". basename($_FILES["image_main"]["name"]). " đã được tải lên.";

            // Sử dụng Prepared Statements để thêm sản phẩm vào cơ sở dữ liệu
            $stmt = $conn->prepare("INSERT INTO products (name, category_id, price_vnd, description, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $name, $category, $price_vnd, $description, $image_main);

            // Thực hiện câu lệnh SQL
            if ($stmt->execute()) {
                echo "Sản phẩm mới đã được thêm thành công.";

                // Lấy ID của sản phẩm vừa thêm
                $product_id = $stmt->insert_id;

                // Thêm hình ảnh phụ
                if (isset($_FILES['product_images'])) {
                    $images = $_FILES['product_images']['name'];
                    foreach ($images as $index => $image) {
                        if ($image) {
                            $image_tmp = $_FILES['product_images']['tmp_name'][$index];
                            $target_dir_sub = "image/image/";  // Sử dụng thư mục image cho ảnh phụ

                            // Kiểm tra nếu thư mục image không tồn tại, tạo nó
                            if (!file_exists($target_dir_sub)) {
                                mkdir($target_dir_sub, 0777, true);  // Tạo thư mục nếu chưa có
                            }

                            $image_path = $target_dir_sub . $image;  // Đường dẫn cho ảnh phụ
                            move_uploaded_file($image_tmp, $image_path);  // Di chuyển file

                            // Thêm hình ảnh vào bảng product_images
                            $sql_image = "INSERT INTO product_images (product_id, image_path) VALUES (?, ?)";
                            $stmt_image = $conn->prepare($sql_image);
                            $stmt_image->bind_param("is", $product_id, $image_path);
                            $stmt_image->execute();
                        }
                    }
                }
            } else {
                echo "Lỗi: " . $stmt->error;
            }

            // Đóng statement sau khi thực hiện xong
            $stmt->close();
        } else {
            echo "Xin lỗi, có lỗi xảy ra khi tải tệp lên.";
        }
    }
}

// Đóng kết nối sau khi hoàn thành
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <style>
        /* Đảm bảo CSS của bạn không bị thay đổi */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 50px;
        }

        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="number"], input[type="file"], textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Thêm Sản Phẩm Mới</h2>

<form action="add_product.php" method="POST" enctype="multipart/form-data">
    <label for="name">Tên sản phẩm:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <label for="category">Danh mục:</label><br>
    <select id="category" name="category">
        <option value="1">iPhone</option>
        <option value="2">Redmi</option>
        <option value="3">Samsung</option>
        <option value="4">Tivi</option>
        <option value="5">Laptop</option>
    </select><br><br>

    <label for="price_vnd">Giá (VND):</label><br>
    <input type="number" id="price_vnd" name="price_vnd" required><br><br>

    <label for="description">Mô tả:</label><br>
    <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

    <label for="image_main">Ảnh chính:</label><br>
    <input type="file" id="image_main" name="image_main" accept="image/image/*" required><br><br>

    <label for="product_images">Ảnh phụ (nếu có):</label><br>
    <input type="file" id="product_images" name="product_images[]" accept="image/image/*" multiple><br><br>

    <input type="submit" value="Thêm sản phẩm">
</form>

</body>
</html>
