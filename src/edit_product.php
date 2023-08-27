<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
    <link rel="stylesheet" href="../public/css/add_product.css" />
    <title>Chỉnh Sửa Sản Phẩm</title>
</head>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/ShopShoe/database/info_connect_db.php';
require_once $root . '/ShopShoe/local/data.php';

if ($username_local === null || $role !== 1) {
    header("Location: " . "/ShopShoe/src/home.php");
    exit;
}

// Xử lý form khi người dùng gửi
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_GET['product_id'];

    // Kiểm tra xem người dùng đã gửi hình ảnh lên hay chưa
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        // Đường dẫn thư mục lưu trữ hình ảnh
        $uploadDir = '/public/images/';

        $imageFileName = $id . '.' . array_pop(explode(".", $_FILES['image']['name']));

        // Đường dẫn tệp tạm thời của hình ảnh
        $tempImageFile = $_FILES['image']['tmp_name'];

        $destinationPath = '.' . $uploadDir . $imageFileName;

        // Kiểm tra nếu tên file đã tồn tại trong thư mục đích
        if (file_exists($destinationPath)) {
            echo '<script>alert("Có! ' . $destinationPath . '")</script>';
            // Xóa file cũ trước khi di chuyển file mới
            unlink($destinationPath);
        }

        // Di chuyển hình ảnh vào thư mục lưu trữ
        move_uploaded_file($tempImageFile, $destinationPath);
    }

    $title = $_POST['title'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $brain = $_POST['brain'];
    $manufacture = $_POST['manufacture'];
    $material = $_POST['material'];
    $description = $_POST['description'];

    // Tiến hành lưu thông tin sản phẩm vào cơ sở dữ liệu
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Tiến hành cập nhật thông tin sản phẩm vào CSDL
        $stmt = $conn->prepare("UPDATE shoes SET title = :title, price = :price, type = :type, brain = :brain, manufacture = :manufacture, material = :material, description = :description WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':brain', $brain);
        $stmt->bindParam(':manufacture', $manufacture);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo '<script>alert("Cập nhật thông tin thành công!")</script>';
        echo '<script>window.location.href = "/ShopShoe/src/detail.php?product_id=' . $id . '"</script>';
    } catch (PDOException $e) {
        echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
        echo '<script>window.location.href = "/ShopShoe/src/edit_product.php?product_id=' . $id . '"</script>';
    }

    $conn = null;
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $product_id = $_GET['product_id'];

    if (!isset($product_id)) {
        header("Location: " . "/ShopShoe/src/home.php");
        exit;
    } else {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sử dụng truy vấn SQL để kiểm tra ID và lấy thông tin tương ứng
        $stmt = $conn->prepare("SELECT * FROM shoes WHERE id = :id");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result) {
            $path_image = $result['path_image'];
            $title = $result['title'];
            $price = $result['price'];
            $type = $result['type'];
            $brain = $result['brain'];
            $manufacture = $result['manufacture'];
            $material = $result['material'];
            $description = $result['description'];
        } else {
            // ID không tồn tại trong bảng shoes
            header("Location: " . "/ShopShoe/src/home.php");
            exit;
        }
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
                <li><a href="/ShopShoe/src/profile.php">Quản trị viên<i class="fa fa-angle-right"></i></a></li>
                <li><a href="<?php echo '/ShopShoe/src/edit_product.php?product_id=' . $product_id ?>">Chỉnh sửa sản phẩm</a></li>
            </ul>
        </div>

        <div class="container-sub-2">
            <div class="content">
                <h1 class="title-add-product">Chỉnh sửa sản phẩm</h1>
                <p><strong>Lưu ý:</strong> Các mục dấu <strong>màu đỏ</strong> không được bỏ trống & phải điền đầy đủ, chính xác</p>
                <form id="add-product" action="<?php echo '/ShopShoe/src/edit_product.php?product_id=' . $product_id ?>" method="POST" enctype="multipart/form-data">
                    <fieldset class="info-product">
                        <legend>Thông tin sản phẩm</legend>
                        <div class="form-group">
                            <label for="id" class="form-label col-sm-2">ID<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="id" class="form-control" value="<?php echo $product_id ?>" name="id" placeholder="ID" disabled autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group-image">
                            <label for="image" class="form-label col-sm-2">Ảnh<sup>*</sup>:</label>
                            <div class="col-sm-10 form-image">
                                <img id="image-preview" src="<?php echo $path_image ?>" alt="Preview" style="display: block; max-width: 150px; max-height: 150px;">
                                <input type="file" id="image" name="image" accept=".png, .jpg, .jpeg, .webp" autocomplete="one-time-code" onchange="change_image(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="form-label col-sm-2">Tên<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="title" class="form-control" value="<?php echo $title ?>" name="title" placeholder="Tên" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label col-sm-2">Giá<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="number" min="0" id="price" class="form-control" value="<?php echo $price ?>" name="price" placeholder="Giá" autocomplete="one-time-code" oninput="input_price(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="form-label col-sm-2">Thể loại<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <select id="type" class="form-control" name="type">
                                    <option <?php echo ($type === 'classic' ? 'selected' : '') ?> value="classic">Classic</option>
                                    <option <?php echo ($type === 'chuck_1970s' ? 'selected' : '') ?> value="chuck_1970s">Chuck 1970S</option>
                                    <option <?php echo ($type === 'chuck_2' ? 'selected' : '') ?> value="chuck_2">Chuck II</option>
                                    <option <?php echo ($type === 'seasonal' ? 'selected' : '') ?> value="seasonal">Seasonal</option>
                                    <option <?php echo ($type === 'sneaker' ? 'selected' : '') ?> value="sneaker">Sneaker</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="brain" class="form-label col-sm-2">Thương hiệu<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="brain" class="form-control" name="brain" value="<?php echo $brain ?>" placeholder="Thương hiệu" value="Converse" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="manufacture" class="form-label col-sm-2">Sản xuất<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="manufacture" class="form-control" name="manufacture" value="<?php echo $manufacture ?>" placeholder="Sản xuất" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="material" class="form-label col-sm-2">Chất liệu<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <input type="text" id="material" class="form-control" name="material" value="<?php echo $material ?>" placeholder="Chất liệu" autocomplete="one-time-code">
                            </div>
                        </div>
                        <div class="form-group-area">
                            <label for="description" class="form-label col-sm-2">Mô tả<sup>*</sup>:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control-area" name="description" id="description" cols="30" rows="10" placeholder="Mô tả"><?php echo $description ?></textarea>
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
                isRequired('#title'),
                isRequired('#price'),
                isRequired('#brain'),
                isRequired('#manufacture'),
                isRequired('#material'),
                isRequired('#description')
            ]
        })

        if (!res) {
            event.preventDefault()
        }
    }

    const input_price = (event) => {
        const inputValue = event.target.value;

        // Sử dụng biểu thức chính quy để chỉ giữ lại các ký tự số
        event.target.value = inputValue.replaceAll(/[^\d]/g, '');
    }

    const imagePreview = document.getElementById('image-preview');

    const change_image = (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    }
</script>

</html>