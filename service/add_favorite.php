<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra xem dữ liệu đã tồn tại trong cơ sở dữ liệu hay chưa
    $selectStmt = $conn->prepare("SELECT * FROM user_shoe_favorites WHERE username = :username AND shoe_id = :shoe_id");
    $selectStmt->bindParam(':username', $data['username']);
    $selectStmt->bindParam(':shoe_id', $data['shoe_id']);
    $selectStmt->execute();

    if ($selectStmt->rowCount() > 0) {
        // Nếu dữ liệu đã tồn tại, xóa nó đi
        $deleteStmt = $conn->prepare("DELETE FROM user_shoe_favorites WHERE username = :username AND shoe_id = :shoe_id");
        $deleteStmt->bindParam(':username', $data['username']);
        $deleteStmt->bindParam(':shoe_id', $data['shoe_id']);
        $deleteStmt->execute();

        $response = array('message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích');
    } else {
        // Nếu dữ liệu chưa tồn tại, thêm dữ liệu mới vào
        $insertStmt = $conn->prepare("INSERT INTO user_shoe_favorites (username, shoe_id) VALUES (:username, :shoe_id)");
        $insertStmt->bindParam(':username', $data['username']);
        $insertStmt->bindParam(':shoe_id', $data['shoe_id']);
        $insertStmt->execute();

        $response = array('message' => 'Đã thêm sản phẩm vào danh sách yêu thích');
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
}
