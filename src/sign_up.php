<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
    <link rel="stylesheet" href="../public/css/sign_up.css" />
    <title>Đăng Ký</title>
</head>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';
include '../service/redirect.php';
?>

<?php

$first_name = $_POST['first-name'];
$last_name = $_POST['last-name'];
$username_ = $_POST['username'];
$password_ = $_POST['password'];

if (isset($first_name) && isset($last_name) && isset($username_) && isset($password_)) {
    try {
        // Kết nối đến cơ sở dữ liệu MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Chuẩn bị truy vấn INSERT Account
        $stmt = $conn->prepare("INSERT INTO account (username, password, role) VALUES (:username, :password, 0)");

        // Thực hiện INSERT Account
        $stmt->bindParam(':username', $username_);
        $stmt->bindParam(':password', $password_);
        $stmt->execute();

        // Chuẩn bị truy vấn INSERT User
        $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, username) VALUES (:first_name, :last_name, :username)");

        // Thực hiện INSERT User
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username_);
        $stmt->execute();

        echo '<script>
                alert("Tài khoản đã được tạo thành công");
                window.location.href="/ShopShoe/src/sign_in.php";
            </script>';
    } catch (PDOException $e) {
        echo "<script>alert('Xin lỗi, tài khoản đã tồn tại!')</script>";
    }
}
?>

<body>
    <?php
    include '../inc/header.php';
    ?>

    <div class="container-signup">
        <div class="container-sub-1">
            <ul class="breadcrumb">
                <li><a href="/ShopShoe/src/home.php">Trang chủ<i class="fa fa-angle-right"></i></a></li>
                <li><a href="/ShopShoe/src/sign_in.php">Tài khoản<i class="fa fa-angle-right"></i></a></li>
                <li><a href="/ShopShoe/src/sign_up.php">Đăng ký</a></li>
            </ul>
        </div>

        <div class="container-sub-2">
            <div class="content">
                <h1 class="title-signup">Đăng Ký Tài Khoản</h1>
                <p>Nếu bạn đã đăng ký tài khoản, vui lòng đăng nhập <a href="/ShopShoe/src/sign_in.php" class="link-login">Tại đây</a></p>
                <p><strong>Lưu ý:</strong> Các mục dấu <strong>màu đỏ</strong> không được bỏ trống & phải điền đầy đủ, chính xác</p>
                <form id="sign-up" action="" method="POST">
                    <fieldset class="profile">
                        <legend>Thông tin cá nhân</legend>
                        <div class="form-group">
                            <label for="first-name" class="form-label col-sm-2">Họ<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="first-name" class="form-control" name="first-name" placeholder="Họ" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last-name" class="form-label col-sm-2">Tên<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="last-name" class="form-control" name="last-name" placeholder="Tên" autocomplete="one-time-code">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="username">
                        <legend>Tên tài khoản</legend>
                        <div class="form-group">
                            <label for="username" class="form-label col-sm-2">Tên Tài Khoản<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="username" class="form-control" name="username" placeholder="Tên Tài Khoản" autocomplete="one-time-code">
                                <span class="note-input">Yêu cầu từ 3 tới 20 ký tự (a-z, A-Z, 0-9, _)</span>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="password">
                        <legend>Mật khẩu</legend>
                        <div class="form-group pad">
                            <label for="password" class="form-label col-sm-2">Mật Khẩu<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Mật Khẩu" autocomplete="one-time-code">
                                <span class="note-input">Yêu cầu từ 8 ký tự trở lên (chứa a-z, A-Z, 0-9, !@#$%^&*()-_+=)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="form-label col-sm-2">Nhập Lại Mật Khẩu<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="password" id="password-confirm" class="form-control" name="password-confirm" placeholder="Nhập Lại Mật Khẩu" autocomplete="one-time-code">
                            </div>
                        </div>
                    </fieldset>
                    <div class="button-submit">
                        <input type="submit" onclick="validate(event)" value="Đăng ký">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    include '../inc/footer.php';
    ?>
</body>

<script src="../public/js/validate.js"></script>
<script>
    const validate = () => {
        const res = Validate({
            rules: [
                isRequired('#first-name'),
                isRequired('#last-name'),
                isUsername('#username'),
                isPassword('#password'),
                confirmPassword('#password', '#password-confirm')
            ]
        })

        if (!res) {
            event.preventDefault()
        }
    }
</script>

</html>