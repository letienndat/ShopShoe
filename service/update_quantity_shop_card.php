<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($data['operator'] === 'del') {
        $stmt = $conn->prepare("DELETE FROM shop_card WHERE username = :username AND shoe_id = :shoe_id");
    } else {
        $quantity = ($data['operator'] === '-') ? -1 : 1;

        $stmt = $conn->prepare("UPDATE shop_card SET quantity = quantity + :quantity WHERE username = :username AND shoe_id = :shoe_id");
        $stmt->bindParam(':quantity', $quantity);
    }

    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':shoe_id', $data['shoe_id']);
    $stmt->execute();

    if ($data['operator'] === 'del') {
        $response = array("status" => 0);
    } else {
        $response = array("status" => 1);
    }
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
}
