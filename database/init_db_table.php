<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';

try {
    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = new mysqli($servername, $username, $password);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối không thành công: " . $conn->connect_error);
    }

    // Tạo cơ sở dữ liệu
    $createDBQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($createDBQuery) === TRUE) {
        echo "Cơ sở dữ liệu đã được tạo hoặc đã tồn tại.<br>";
    } else {
        echo "Lỗi khi tạo cơ sở dữ liệu: " . $conn->error . "<br>";
    }

    // // Chọn cơ sở dữ liệu mới tạo
    $conn->select_db($dbname);

    // Đặt chế độ kiểm soát lỗi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    echo "Tạo database ShopShoe thành công!" . "<br>";

    // Tạo bảng shoes
    $createTableQuery = "CREATE TABLE IF NOT EXISTS shoes (
        id VARCHAR(20) PRIMARY KEY,
        path_image VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        price INT(11) NOT NULL,
        type VARCHAR(255) NOT NULL,
        brain VARCHAR(255) NOT NULL,
        manufacture VARCHAR(255) NOT NULL,
        material VARCHAR(255) NOT NULL,
        description TEXT NOT NULL
    )";
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tạo bảng Shoes thành công!" . "<br>";
    } else {
        echo "Tạo bảng Shoes không thành công!" . "<br>";
    }

    // Tạo bảng Account
    $createTableQuery = "CREATE TABLE IF NOT EXISTS account (
        username VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin PRIMARY KEY,
        password VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
        role int(11) NOT NULL
    )";
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tạo bảng Account thành công!" . "<br>";
    } else {
        echo "Tạo bảng Account không thành công!" . "<br>";
    }

    // Tạo bảng User
    $createTableQuery = "CREATE TABLE IF NOT EXISTS user (
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        username VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin PRIMARY KEY,
        FOREIGN KEY (username) REFERENCES account(username) ON DELETE CASCADE ON UPDATE CASCADE
    )";
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tạo bảng User thành công!" . "<br>";
    } else {
        echo "Tạo bảng User không thành công!" . "<br>";
    }

    // Tạo bảng User_Shoe_Favorites
    $createTableQuery = "CREATE TABLE IF NOT EXISTS user_shoe_favorites (
        username VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin,
        shoe_id VARCHAR(255),
        PRIMARY KEY (username, shoe_id),
        FOREIGN KEY (username) REFERENCES user(username) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (shoe_id) REFERENCES shoes(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tạo bảng User_Shoe_Favorites thành công!" . "<br>";
    } else {
        echo "Tạo bảng User_Shoe_Favorites không thành công!" . "<br>";
    }

    // Tạo bảng Shop_Card
    $createTableQuery = "CREATE TABLE IF NOT EXISTS shop_card (
        username VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin,
        shoe_id VARCHAR(255),
        quantity INT(11) NOT NULL,
        time DATETIME NOT NULL,
        PRIMARY KEY (username, shoe_id),
        FOREIGN KEY (username) REFERENCES user(username) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (shoe_id) REFERENCES shoes(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tạo bảng Shop_Card thành công!" . "<br>";
    } else {
        echo "Tạo bảng Shop_Card không thành công!" . "<br>";
    }

    // Chuẩn bị truy vấn INSERT
    $username_ = "admin";
    $password_ = "admin";
    $role = 1;
    $stmt = $conn->prepare("INSERT INTO account (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username_, $password_, $role);

    if ($stmt->execute()) {
        echo "Tạo tài khoản admin thành công!" . "<br>";
    } else {
        echo "Tạo tài khoản admin không thành công!" . "<br>";
    }

    // Chuẩn bị truy vấn INSERT
    $first_name = "Lê";
    $last_name = "Tiến Đạt";
    $username_ = "admin";
    $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, username) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $first_name, $last_name, $username_);

    if ($stmt->execute()) {
        echo "Tạo thông tin cho admin thành công!" . "<br>";
    } else {
        echo "Tạo thông tin cho admin không thành công!" . "<br>";
    }

} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$conn = null;
