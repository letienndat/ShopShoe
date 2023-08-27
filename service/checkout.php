<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedProductIDs = isset($_POST['option']) ? $_POST['option'] : array();

    if (sizeof($selectedProductIDs) > 0) {
        $username_ = isset($_POST['username']) ? $_POST['username'] : "";

        $root = $_SERVER['DOCUMENT_ROOT'];
        require_once $root . '/ShopShoe/database/info_connect_db.php';

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Xây dựng câu truy vấn SQL sử dụng Prepared Statement
            $query = "SELECT SUM(price * quantity) AS total_price FROM shop_card 
                  INNER JOIN shoes ON shop_card.shoe_id = shoes.id 
                  WHERE shop_card.shoe_id IN (" . str_repeat("?,", count($selectedProductIDs) - 1) . "?) 
                  AND shop_card.username = ?";

            $stmt = $conn->prepare($query);

            $stmt->execute(array_merge($selectedProductIDs, array($username_)));

            // Lấy tổng giá tiền
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalPrice = $result['total_price'];

            $placeholders = implode(',', array_fill(0, count($selectedProductIDs), '?'));

            $stmt = $conn->prepare("DELETE FROM shop_card WHERE shoe_id IN ($placeholders) AND username = ?");
            $selectedProductIDs[] = $username_;
            $stmt->execute($selectedProductIDs);

            $response = array("message" => "Cám ơn bạn đã thanh toán " . number_format($totalPrice, 0, ",", ".") . " đ", "status" => 2);
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (PDOException $e) {
            $response = array("message" => "Error! " . $e->getMessage(), "status" => 0);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    } else {
        $response = array("message" => "Vui lòng chọn đơn trước khi thực hiện thanh toán!", "status" => 1);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
