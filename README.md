# WEBSITE BÁN GIÀY (NHÓM 17)
## Hướng dẫn sử dụng
### Yêu cầu:
-   MySQL
-   Xampp/Appserv
### Hướng dẫn chạy chương trình:
-   Đối với Appserv: Đặt project nằm trong thư mục www của Appserv (Mặc định nằm ở ổ đĩa C, folder C:/Appserv/www)
-   Đối với Xampp: Đặt project nằm trong thư mục htdocs của Xampp (Mặc định nằm ở ổ đĩa C, folder C:/xampp/htdocs)
-   Sửa thông tin kết nối với MySQL phù hợp trong file info_connect_db.php (Chỉ nên sửa password, đối với Xampp thì mặc định mật khẩu thường không có nên ta bỏ trống password)
 -  Truy cập lần lượt vào các đường dẫn sau bằng trình duyệt của bạn:
    -   Khởi tạo database, các bảng và thông tin admin
    ```
        http://localhost/ShopShoe/database/init_db_table.php
    ```
    -   Khởi tạo dữ liệu sản phẩm cho website
    ```
        http://localhost/ShopShoe/database/init_data.php
    ```
    -   Đường dẫn tới trang chủ của website
    ```
        http://localhost/ShopShoe/src/home.php
    ```
-   Truy cập với tài khoản admin mặc định được cấp hoặc tạo tài khoản cá nhân để sử dụng (Lưu ý: Tài khoản cá nhân chỉ là tài khoản bình thường nên không có các chức năng của quản trị viên, chỉ có tài khoản admin với có đặc quyền đó)
```
    Tài khoản: admin
    Mật khẩu: admin
```
## GOOD LUCK!