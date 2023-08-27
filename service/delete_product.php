<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';

if ($username_local === null || $role !== 1) {
    header("Location: " . "/ShopShoe/src/home.php");
    exit;
}

// Xử lý form khi người dùng gửi
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['product_id'];

    try {
        // Tạo kết nối đến CSDL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Xóa sản phẩm dựa trên id
        $stmt = $conn->prepare("SELECT path_image FROM shoes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $path_image = ($stmt->fetch())['path_image'];
        $path_image = str_replace("/ShopShoe", "..", $path_image);

        // Xóa sản phẩm dựa trên id
        $stmt = $conn->prepare("DELETE FROM shoes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Kiểm tra số hàng bị ảnh hưởng bởi câu lệnh DELETE
        $rows_affected = $stmt->rowCount();

        if ($rows_affected > 0) {
            // Sản phẩm đã được xóa thành công
            echo '<script>alert("Xóa sản phẩm thành công!")</script>';

            // Tìm file ảnh
            if (file_exists($path_image)) { 
                // Xóa file ảnh
                unlink($path_image);
            }
        } else {
            // Không tìm thấy sản phẩm với id tương ứng hoặc đã có lỗi xảy ra trong quá trình xóa
            echo '<script>alert("Thất bại! ID không trùng với bất kỳ sản phẩm nào")</script>';
        }
        echo '<script>window.location.href = "/ShopShoe/src/home.php"</script>';
    } catch (PDOException $e) {
        echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
    }
}
