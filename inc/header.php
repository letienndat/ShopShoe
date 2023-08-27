<header>
    <div class="header-top">
        <div class="row header-top-sub">
            <div class="hotline">
                Hotline:&nbsp;<a class="a-hotline" href="tel:18000080">1800.0080</a>
            </div>

            <?php
            require_once $root . '/ShopShoe/local/data.php';

            if ($username_local === null) {
            ?>
                <div class="sign-up">
                    <a class="sign a-sign-up" href="/ShopShoe/src/sign_up.php">
                        <i class="fa fa-lock"></i>
                        Đăng Ký
                    </a>
                </div>
                <div class="sign-in">
                    <a class="sign a-sign-in" href="/ShopShoe/src/sign_in.php">
                        <i class="fa fa-user"></i>
                        Đăng Nhập
                    </a>
                </div>
            <?php
            } else {
            ?>
                <?php
                if ($role === 1) {
                ?>
                    <div class="add">
                        <a class="sign a-add" href="/ShopShoe/src/add_product.php">
                            <i class="fa fa-plus-circle"></i>
                            Thêm sản phẩm
                        </a>
                    </div>
                <?php
                }
                ?>
                <div title="Thông tin cá nhân" class="user">
                    <a class="sign a-user" href="/ShopShoe/src/profile.php">
                        <i class="fa fa-user"></i>
                        <?php echo $username_local . ($role === 1 ? " (Admin)" : ""); ?>
                    </a>
                </div>
                <div class="sign-out">
                    <a class="sign a-sign-out" href="/ShopShoe/src/sign_out.php">
                        <i class="fa fa-sign-out"></i>
                        Đăng Xuất
                    </a>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
    <div class="header-center">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <a href="/ShopShoe/src/home.php">
                        <img class="logo" src="/ShopShoe/public/images/drake_logo.png" alt="">
                    </a>
                </div>
                <div class="col-sm-3 text-right">
                    <a class="a-heart" href="/ShopShoe/src/list_favorite.php">
                        <i title="Danh sách yêu thích" class="fa-regular fa-heart heart"></i>
                    </a>
                    <div class="shopcart">
                        <a href="/ShopShoe/src/shop_card.php">
                            <i title="Giỏ hàng" class="fa fa-shopping-cart"></i>
                        </a>
                        <?php
                        if ($username_local !== null) {
                            // Lấy tổng số lượng đơn hàng trong giỏ hàng của người dùng
                            $stmt = $conn->prepare("SELECT COUNT(shoe_id) AS total_orders FROM shop_card WHERE username = :username");
                            $stmt->bindParam(':username', $username_local);
                            $stmt->execute();

                            // Lấy dữ liệu kết quả trả về từ câu truy vấn
                            $result = join($stmt->fetch(PDO::FETCH_ASSOC));

                            $total_orders = $result['total_orders'];
                            echo '<span id="total_order" class="text-shopping-cart">' . ($total_orders > 99 ? '99+' : $total_orders) . '</span>';
                        } else {
                            echo '<span id="total_order" class="text-shopping-cart">0</span>';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="notify-top">
            <span class="span-notify-top">Miễn phí vận chuyển toàn quốc</span>
        </div>
    </div>
    <div class="header-botton">
        <div class="container ">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="navbar">
                        <li class="with-sub-menu"><a class="effect-navbar" href="/ShopShoe/src/home.php"><strong class="strong-menu">CONVERSE</strong></a></li>
                        <li class="with-sub-menu"><a class="effect-navbar" href="/ShopShoe/src/home.php?type=classic"><strong class="strong-menu">CLASSIC</strong></a></li>
                        <li class="with-sub-menu"><a class="effect-navbar" href="/ShopShoe/src/home.php?type=chuck_1970s"><strong class="strong-menu">CHUCK 1970S</strong></a></li>
                        <li class="with-sub-menu"><a class="effect-navbar" href="/ShopShoe/src/home.php?type=chuck_2"><strong class="strong-menu">CHUCK II</strong></a></li>
                        <li class="with-sub-menu"><a class="effect-navbar" href="/ShopShoe/src/home.php?type=seasonal"><strong class="strong-menu">SEASONAL</strong></a></li>
                        <li class="with-sub-menu"><a class="effect-navbar" href="/ShopShoe/src/home.php?type=sneaker"><strong class="strong-menu">SNEAKER</strong></a></li>
                    </ul>
                    <div class="sb-search">
                        <div class="search-content">
                            <form action="/ShopShoe/src/home.php" method="get">
                                <?php
                                $search = $_GET['search'];
                                ?>
                                <input class="sb-search-input" name="search" type="text" placeholder="Tìm kiếm" value="<?php echo (isset($search) ? trim(htmlspecialchars($search, ENT_QUOTES, 'UTF-8')) : "") ?>">
                                <button class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>