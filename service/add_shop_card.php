<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng của người dùng chưa
    $stmt = $conn->prepare("SELECT * FROM shop_card WHERE username = :username AND shoe_id = :shoe_id");
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':shoe_id', $data['shoe_id']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng
        $stmt = $conn->prepare("UPDATE shop_card SET quantity = quantity + :quantity, time = :time WHERE username = :username AND shoe_id = :shoe_id");
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':time', $data['time']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':shoe_id', $data['shoe_id']);
        $stmt->execute();
    } else {
        // Sản phẩm chưa tồn tại trong giỏ hàng, thêm mới vào
        $stmt = $conn->prepare("INSERT INTO shop_card (username, shoe_id, quantity, time) VALUES (:username, :shoe_id, :quantity, :time)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':shoe_id', $data['shoe_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':time', $data['time']);
        $stmt->execute();
    }

    echo json_encode(array('message' => 'Thêm sản phẩm vào giỏ hàng thành công'));
} catch (PDOException $e) {
    echo json_encode(array('message' => $e->getMessage()));
}
