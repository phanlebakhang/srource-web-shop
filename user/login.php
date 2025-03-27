<?php
session_start();
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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin']; // Lưu giá trị is_admin vào session
        header('Location: /index.php');
        exit();
    } else {
        echo "<div class='error-message'>Invalid credentials</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FPT Shop</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Link tới file CSS của bạn -->
    
</head>
<body>

<!-- Thêm background mờ -->
<div class="background"></div>

<div class="login-container">
    <h2>Đăng Nhập</h2>
    <form method="POST">
        <input type="email" name="email" class="input-field" placeholder="Email" required>
        <input type="password" name="password" class="input-field" placeholder="Password" required>
        <button type="submit" class="button">Đăng Nhập</button>
    </form>
    
    <div class="signup-link">
        <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
</div>

</body>
</html>
