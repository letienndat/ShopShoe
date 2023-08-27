<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
    <link rel="stylesheet" href="../public/css/profile.css" />
    <title>Thông Tin Tài Khoản</title>
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

$first_name = $_POST['first-name'];
$last_name = $_POST['last-name'];
$username_ = $username_local;

if (isset($first_name) && isset($last_name)) {
    try {
        // Kết nối đến cơ sở dữ liệu MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Chuẩn bị truy vấn UPDATE
        $updateQuery = "UPDATE user SET first_name = :first_name, last_name = :last_name WHERE username = :username";

        // Thực hiện truy vấn UPDATE
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username_);

        $stmt->execute();
    } catch (PDOException $e) {
        echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
    }
} else {
    try {
        // Kết nối đến cơ sở dữ liệu MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Thực hiện truy vấn SQL
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(":username", $username_);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $first_name = $user['first_name'];
            $last_name = $user['last_name'];
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
                <li><a href="/ShopShoe/src/profile.php">Tài khoản</a></li>
            </ul>
        </div>

        <div class="container-sub-2">
            <div class="content">
                <h1 class="title-signup">Thông Tin Tài Khoản</h1>
                <p>Bạn muốn đổi mật khẩu? Đổi mật khẩu <a href="/ShopShoe/src/change_password.php" class="link-login">Tại đây</a></p>
                <p><strong>Lưu ý:</strong> Các mục dấu <strong>màu đỏ</strong> không được bỏ trống & phải điền đầy đủ, chính xác</p>
                <form id="profile" action="/ShopShoe/src/profile.php" method="POST">
                    <fieldset class="username">
                        <legend>Tên tài khoản</legend>
                        <div class="form-group">
                            <label for="username" class="form-label col-sm-2">Tên Tài Khoản<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="username" class="form-control" value="<?php echo $username_ ?>" disabled autocomplete="one-time-code">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="profile">
                        <legend>Thông tin cá nhân</legend>
                        <div class="form-group">
                            <label for="first-name" class="form-label col-sm-2">Họ<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="first-name" class="form-control" name="first-name" placeholder="Họ" value="<?php echo $first_name ?>" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last-name" class="form-label col-sm-2">Tên<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="last-name" class="form-control" name="last-name" placeholder="Tên" value="<?php echo $last_name ?>" autocomplete="one-time-code">
                            </div>
                        </div>
                    </fieldset>
                    <div class="button-submit">
                        <input type="submit" onclick="validate(event)" value="Lưu thông tin">
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
                isRequired('#first-name'),
                isRequired('#last-name')
            ]
        })

        if (!res) {
            event.preventDefault()
        }
    }
</script>

</html>