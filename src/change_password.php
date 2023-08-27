<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
    <link rel="stylesheet" href="../public/css/profile.css" />
    <title>Đổi Mật Khẩu</title>
</head>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';
if ($username_local === null) {
    header("Location: " . "/ShopShoe/src/home.php");
    exit;
}
?>

<?php

$username_ = $username_local;
$old_password = $_POST['old-password'];
$new_password = $_POST['new-password'];

if (isset($old_password) && isset($new_password)) {
    try {
        // Kết nối đến cơ sở dữ liệu MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Chuẩn bị truy vấn UPDATE
        $updateQuery = "UPDATE account SET password = :new_password WHERE username = :username AND password = :old_password";

        // Thực hiện truy vấn UPDATE
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':new_password', $new_password);
        $stmt->bindParam(':username', $username_);
        $stmt->bindParam(':old_password', $old_password);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<script>alert("Thay đổi mật khẩu thành công!")</script>';
            echo '<script>window.location.href="/ShopShoe/src/home.php"</script>';
        } else {
            echo '<script>alert("Thay đổi mật khẩu thất bại, hãy thử lại!")</script>';
            echo '<script>window.location.href="/ShopShoe/src/change_password.php"</script>';
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

    <div class="container-signup">
        <div class="container-sub-1">
            <ul class="breadcrumb">
                <li><a href="/ShopShoe/src/home.php">Trang chủ<i class="fa fa-angle-right"></i></a></li>
                <li><a href="/ShopShoe/src/profile.php">Tài khoản<i class="fa fa-angle-right"></i></a></li>
                <li><a href="/ShopShoe/src/change_password.php">Đổi mật khẩu</a></li>
            </ul>
        </div>

        <div class="container-sub-2">
            <div class="content">
                <h1 class="title-signup">Đổi Mật Khẩu</h1>
                <p><strong>Lưu ý:</strong> Các mục dấu <strong>màu đỏ</strong> không được bỏ trống & phải điền đầy đủ, chính xác</p>
                <form id="change-password" action="/ShopShoe/src/change_password.php" method="POST">
                    <fieldset class="username">
                        <legend>Tên tài khoản</legend>
                        <div class="form-group">
                            <label for="username" class="form-label col-sm-2">Tên Tài Khoản<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="username" class="form-control" value="<?php echo $username_ ?>" disabled autocomplete="one-time-code">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="password">
                        <legend>Đổi mật khẩu</legend>
                        <div class="form-group">
                            <label for="old-password" class="form-label col-sm-2">Mật Khẩu Cũ<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="password" id="old-password" class="form-control" name="old-password" placeholder="Mật Khẩu Cũ" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group pad">
                            <label for="new-password" class="form-label col-sm-2">Mật Khẩu Mới<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="password" id="new-password" class="form-control" name="new-password" placeholder="Mật Khẩu Mới" autocomplete="one-time-code">
                                <span class="note-input">Yêu cầu từ 8 ký tự trở lên (chứa a-z, A-Z, 0-9, !@#$%^&*()-_+=)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password-confirm" class="form-label col-sm-2">Nhập Lại Mật Khẩu Mới<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="password" id="new-password-confirm" class="form-control" placeholder="Nhập Lại Mật Khẩu Mới" autocomplete="one-time-code">
                            </div>
                        </div>
                    </fieldset>
                    <div class="button-submit">
                        <input type="submit" onclick="validate(event)" value="Lưu thay đổi">
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
                isUsername('#username'),
                isRequired('#old-password'),
                isPassword('#new-password'),
                confirmPassword('#new-password', '#new-password-confirm')
            ]
        })

        if (!res) {
            event.preventDefault()
        }
    }
</script>

</html>