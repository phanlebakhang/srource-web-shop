<?php 
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fptshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Kiểm tra nếu email đã tồn tại trong cơ sở dữ liệu
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='error-message'>Email đã tồn tại</div>";
    } else {
        // Thực hiện thêm người dùng mới vào cơ sở dữ liệu
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>Đăng ký thành công!</div>";
        } else {
            echo "<div class='error-message'>Lỗi: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - FPT Shop</title>
    <link rel="stylesheet" href="../assets/style.css">
    
</head>
<body>

<!-- Thêm background mờ -->
<div class="background"></div>

<div class="register-container">
    <h2>Đăng Ký Tài Khoản</h2>
    <form method="POST">
        <input type="text" name="username" class="input-field" placeholder="Tên người dùng" required>
        <input type="email" name="email" class="input-field" placeholder="Email" required>
        <input type="password" name="password" class="input-field" placeholder="Mật khẩu" required>
        <button type="submit" class="button">Đăng Ký</button>
    </form>
    
    <div class="signup-link">
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
    </div>
</div>

</body>
</html>
