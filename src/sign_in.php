<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
    <link rel="stylesheet" href="../public/css/sign_in.css" />
    <title>Đăng Nhập</title>
</head>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';
include '../service/redirect.php';
?>

<?php

$username_ = $_POST['username'];
$password_ = $_POST['password'];

if (isset($username_) && isset($password_)) {
    try {
        // Kết nối đến cơ sở dữ liệu MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Thực hiện truy vấn SQL để kiểm tra tài khoản và mật khẩu
        $stmt = $conn->prepare("SELECT * FROM account WHERE username = :username AND password = :password");
        $stmt->bindParam(":username", $username_);
        $stmt->bindParam(":password", $password_);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['username'] = $username_;
            echo '<script>window.location.href="/ShopShoe/src/home.php"</script>';
        } else {
            echo '<script>
                    alert("Sai thông tin đăng nhập, hãy thử lại!");
                    window.location.href="/ShopShoe/src/sign_in.php";
                </script>';
        }
    } catch (PDOException $e) {
        echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
    }
}
?>

<body>
    <?php
    include '../inc/header.php';
    ?>

    <div class="container-login">
        <div class="container-sub-1">
            <ul class="breadcrumb">
                <li><a href="/ShopShoe/src/home.php">Trang chủ<i class="fa fa-angle-right"></i></a></li>
                <li><a href="/ShopShoe/src/sign_in.php">Tài khoản<i class="fa fa-angle-right"></i></a></li>
                <li><a href="/ShopShoe/src/sign_in.php">Đăng nhập</li>
            </ul>
        </div>

        <div class="container-sub-2">
            <div class="content">
                <div class="col">
                    <p class="title-sub">Khách hàng mới</p>
                    <p class="content-sub">Bằng cách tạo tài khoản bạn có thể mua sắm nhanh hơn, cập nhật tình trạng đơn hàng, theo dõi những đơn hàng đã đặt và đặc biệt là sẽ được hưởng nhiều chương trình ưu đãi!</p>
                    <a href="/ShopShoe/src/sign_up.php"><button class="btn-sign">Tiếp tục</button></a>
                </div>
                <div class="col">
                    <div class="well">
                        <p class="title-sub">Khách hàng cũ</p>
                        <form action="" method="POST" class="form" id="sign-in">
                            <div class="form-group">
                                <label for="username" class="form-label">Tên tài khoản</label>
                                <input id="username" name="username" type="text" placeholder="Nhập tên tài khoản" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input id="password" name="password" type="password" placeholder="Nhập mật khẩu" class="form-control" required>
                            </div>
                            <div class="div-forget-password">
                                <a class="forget-password" href="/ShopShoe/src/forget_password.php">Quên mật khẩu<br></a>
                            </div>
                            <button class="btn-sign" onclick="validate(event)">Đăng nhập</button>
                        </form>
                    </div>
                </div>
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
                isRequired('#username'),
                isRequired('#password')
            ]
        })

        if (!res) {
            event.preventDefault()
        }
    }
</script>

</html>