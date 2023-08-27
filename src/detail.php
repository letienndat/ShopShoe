<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
    <link rel="stylesheet" href="../public/css/detail.css" />

    <?php
    $product_id = $_GET['product_id'];

    if (!isset($product_id)) {
        echo '<script>window.location.href="/ShopShoe/src/home.php"</script>';
    }
    ?>

    <title>
        <?php
        $type_converse = $_GET['type'];

        $root = $_SERVER['DOCUMENT_ROOT'];
        require_once $root . '/ShopShoe/database/info_connect_db.php';
        require_once $root . '/ShopShoe/local/data.php';

        try {
            // Kết nối đến cơ sở dữ liệu MySQL
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Truy vấn lấy giày từ bảng "shoes"
            $stmt = $conn->query("SELECT * FROM shoes" . " WHERE id = '" . $product_id . "'");
            $stmt->execute();

            // Lấy kết quả tìm kiếm
            $shoe = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo $shoe[0]['title'];
        } catch (PDOException $e) {
            echo '<script>console.log("Lỗi: ' . $e->getMessage() . '")</script>';
        }
        ?>
    </title>

    <?php
    if (sizeof($shoe) === 0) {
        echo '<script>window.location.href="/ShopShoe/src/home.php"</script>';
    } else {
        $shoe = $shoe[0];
    }
    ?>
</head>

<body>
    <?php
    include '../inc/header.php';
    ?>

    <div class="container-content">
        <div class="container-sub-1">
            <ul class="breadcrumb">
                <li><a href="/ShopShoe/src/home.php">Trang chủ<i class="fa fa-angle-right"></i></a></li>

                <?php
                if (isset($type_converse)) {
                    $title = "All Converse";
                    switch ($type_converse) {
                        case 'classic':
                            $title = "Classic";
                            break;
                        case 'chuck_1970s':
                            $title = "Chuck 1970S";
                            break;
                        case 'chuck_2':
                            $title = "Chuck II";
                            break;
                        case 'seasonal':
                            $title = "Seasonal";
                            break;
                        case 'sneaker':
                            $title = "Sneaker";
                            break;
                    }

                ?>
                    <li><a href="<?php echo "/ShopShoe/src/home.php" . ($type_converse === 'converse' ? "" : "?type=" . $type_converse) ?>"><?php echo $title ?><i class="fa fa-angle-right"></i></a></li>
                <?php
                }
                ?>

                <li><a href=""><?php echo $shoe['title'] ?></a></li>
            </ul>
        </div>
        <div class="container-sub-2">
            <div class="col-sm-12">
                <div class="content-product-left col-sm-5">
                    <div class="image-single-box">
                        <img src="<?php echo $shoe['path_image'] ?>" alt="Ảnh sản phẩm">
                    </div>
                </div>
                <div class="content-product-right col-sm-7">
                    <div class="title-product">
                        <h1 class="title-real"><?php echo $shoe['title'] ?></h1>
                        <div class="title-id"><?php echo " - " . $shoe['id'] ?></div>
                    </div>
                    <div class="desc-product">
                        <div class="col-sm-4">
                            <div class="id-product">
                                <span><?php echo "ID: " . $shoe['id'] ?></span>
                            </div>
                            <div class="metarial-product">
                                <span><?php echo "Chất liệu: " . $shoe['material'] ?></span>
                            </div>
                            <div class="brain-product">
                                <span><?php echo "Thương hiệu: " . $shoe['brain'] ?></span>
                            </div>
                            <div class="manufacture-product">
                                <span><?php echo "Sản xuất: " . $shoe['manufacture'] ?></span>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <span class="price">
                                <span class="title-price">Giá: </span>
                                <span class="price-real"><?php echo number_format($shoe['price'], 0, ",", ".") . " đ" ?></span>
                            </span>
                            <span class="notify">
                                MIỄN PHÍ VẬN CHUYỂN TOÀN QUỐC KHI ĐẶT HÀNG ONLINE
                            </span>
                        </div>
                    </div>
                    <div class="desc-content-product">
                        <?php echo $shoe['description'] ?>
                    </div>
                    <div class="box-option">
                        <div class="quantity-box">
                            <label for="quantity-product">Số lượng</label>
                            <div class="quantity-content">
                                <span class="input-group-addon product_quantity_down" onclick="up_down_quantity('-')">-</span>
                                <input type="text" id="quantity-product" class="form-control" name="quantity" value="1" oninput="change_input_quantity(event)" onblur="blur_input(event)">
                                <span class="input-group-addon product_quantity_up" onclick="up_down_quantity('+')">+</span>
                            </div>
                        </div>
                        <div class="favorite">
                            <button class="btn" id="button-favorite" <?php echo 'onclick=click_favorite(' . ($username_local !== null ? ('true,"' . $username_local . '","' . $shoe["id"] . '"') : 'false') . ')' ?>>
                                <?php
                                // Kiểm tra xem username đã thích sản phẩm hay chưa
                                $selectStmt = $conn->prepare("SELECT * FROM user_shoe_favorites WHERE username = :username AND shoe_id = :shoe_id");
                                $selectStmt->bindParam(':username', $username_local);
                                $selectStmt->bindParam(':shoe_id', $shoe['id']);
                                $selectStmt->execute();

                                if ($selectStmt->rowCount() > 0) {
                                    // Nếu đã thích sản phẩm, thêm "fa-solid" vào class của thẻ i
                                    echo '<i class="fa-solid fa-heart"></i>';
                                } else {
                                    // Nếu chưa thích sản phẩm, thêm "fa-regular" vào class của thẻ i
                                    echo '<i class="fa-regular fa-heart"></i>';
                                }
                                ?>
                            </button>
                        </div>
                    </div>
                    <div class="box-option">
                        <button class="button-card" <?php echo 'onclick=add_shop_card(' . ($username_local !== null ? ('true,"' . $username_local . '","' . $shoe["id"] . '"') : 'false') . ')' ?>>ĐẶT HÀNG</button>
                        <?php
                        if ($role === 1) {
                        ?>
                            <div class="box-option-admin">
                                <button class="button-admin" <?php echo 'onclick=edit_product(\'' . $shoe['id'] . '\')' ?>>CHỈNH SỬA</button>
                                <button class="button-admin" <?php echo 'onclick=delete_product(\'' . $shoe['id'] . '\')' ?>>XÓA</button>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include '../inc/footer.php';
    ?>
</body>

<script>
    const click_favorite = (status, username_, shoe_id) => {
        if (status) {
            var i_favorite = document.querySelector('#button-favorite > .fa-heart')
            i_favorite.classList.contains('fa-regular') ? i_favorite.classList.replace('fa-regular', 'fa-solid') : i_favorite.classList.replace('fa-solid', 'fa-regular')

            var option = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: username_,
                    shoe_id
                }),
            }

            fetch('/ShopShoe/service/add_favorite.php', option)
                .then(response => response.json()) // Chuyển dữ liệu phản hồi thành JSON
                .then(data => {
                    // Xử lý dữ liệu phản hồi từ PHP
                    alert(data.message); // Hiển thị phản hồi trong alert
                })
                .catch(err => console.error(err))
        } else {
            alert("Rất tiếc, bạn phải đăng nhập trước!");
        }
    }

    const add_shop_card = (status, username_, shoe_id) => {
        if (status) {
            const quantity = document.querySelector('#quantity-product').value

            if (Number.isInteger(parseInt(quantity)) && parseInt(quantity) > 0) {
                var option = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: username_,
                        shoe_id,
                        quantity: document.querySelector('#quantity-product').value,
                        time: new Date()
                    }),
                }

                fetch('/ShopShoe/service/add_shop_card.php', option)
                    .then(response => response.json()) // Chuyển dữ liệu phản hồi thành JSON
                    .then(data => {
                        // Xử lý dữ liệu phản hồi từ PHP
                        alert(data.message); // Hiển thị phản hồi trong alert
                        window.location.href = '/ShopShoe/src/shop_card.php'
                    })
                    .catch(err => console.error(err))
            } else {
                alert("Xin lỗi, số lượng sản phẩm không hợp lệ!");
            }
        } else {
            alert("Rất tiếc, bạn phải đăng nhập trước!");
        }
    }

    const change_input_quantity = (event) => {
        if (event.data < '0' || event.data > '9') {
            event.target.value = event.target.value.replace(/\D/, '')
        }
    }

    const blur_input = (event) => {
        if (event.target.value === '') {
            // event.target.value = '1'
        } else {
            event.target.value = parseInt(event.target.value).toString()
        }
    }

    const up_down_quantity = (operator) => {
        input_quantity = document.querySelector('#quantity-product')
        if (operator === '-') {
            input_quantity.value = (input_quantity.value <= 0 ? input_quantity.value : parseInt(input_quantity.value) - 1).toString()
        } else if (operator === '+') {
            ++input_quantity.value
        }
    }

    const edit_product = (id) => {
        window.location.href = `/ShopShoe/src/edit_product.php?product_id=${id}`
    }

    const delete_product = (id) => {
        window.location.href = `/ShopShoe/service/delete_product.php?product_id=${id}`
    }
</script>

</html>