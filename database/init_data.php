<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/obj/shoe.php';

try {
    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    $shoes = array();

    for ($i=1; $i <= 32; $i++) { 
        $id = strtoupper(uniqid());
        $path_temp = '../temp/images/converse (' . $i . ').jpg';
        $path_image = '../public/images/' . $id . '.jpg';
        $type = null;
        if ($i <= 7) {
            $type = 'classic';
        } else if ($i <= 12) {
            $type = 'chuck_1970s';
        } else if ($i <= 18) {
            $type = 'chuck_2';
        } else if ($i <= 27) {
            $type = 'seasonal';
        } else {
            $type = 'sneaker';
        }
        copy($path_temp, $path_image);
        $path_image = '/ShopShoe/public/images/' . $id . '.jpg';
        array_push($shoes, new Shoe($id, $path_image, "Converse Chuck Taylor All Star Festival Smoothie", random_int(800, 2000) * 1000, $type, "Converse", "Việt Nam", "Textile", "Thiết kế cổ cao cá tính giúp bảo vệ an toàn vùng mắt cá chân"));
    }

    // Chuẩn bị truy vấn INSERT
    $stmt = $conn->prepare("INSERT INTO shoes (id, path_image, title, price, type, brain, manufacture, material, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Thực hiện INSERT cho từng đôi giày
    foreach ($shoes as $shoe) {
        $stmt->bind_param('sssssssss', $shoe->id, $shoe->path_image, $shoe->title, $shoe->price, $shoe->type, $shoe->brain, $shoe->manufacture, $shoe->material, $shoe->description);
        $stmt->execute();
    }

    echo "Dữ liệu đã được thêm vào cơ sở dữ liệu thành công!";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$conn = null;
