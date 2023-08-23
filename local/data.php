<?php
$username_local = isset($_SESSION['username']) ? $_SESSION['username'] : null ;
$role = null;

if ($username_local !== null) {
    try {
        // Kết nối đến cơ sở dữ liệu MySQL
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Thực hiện truy vấn SQL
        $stmt = $conn->prepare("SELECT role FROM account WHERE username = :username");
        $stmt->bindParam(':username', $username_local);
        $stmt->execute();
        $role = (int)$stmt->fetch(PDO::FETCH_ASSOC)['role'];
    } catch (PDOException $e) {
        echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
    }
}
