<?php
require_once '/wampp/www/website_ban_sen_da/model/config.php';
$connect = connectdb();
$query = $connect->query("SELECT * FROM products INNER JOIN categories ON products.category_id = categories.category_id");
$query->setFetchMode(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style1.css">
    <title>Quản lý sản phẩm</title>
</head>

<body>
    <div class="sidebar">

        <ul class="menu">
            <div class="logo">
                <a href="/Admin/index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>DashBoard</span>
                </a>
            </div>

            <li class="active">
                <a href="/Admin/Products/category_product.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Quản lý sản phẩm</span>
                    <ul class="submenu">
                        <li><a href="/Admin/Products/category_product.php">
                                <span>Danh sách sản phẩm</span>
                            </a></li>
                        <li><a href="/Admin/Category/category.php">
                                <span>Danh mục sản phẩm</span>
                            </a></li>
                    </ul>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-user"></i>
                    <span>Quản lý người dùng</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Quản lý đơn đặt hàng</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Quản lý tài khoản</span>
                </a>
            </li>

            <li class="logout">
                <a href="../../Admin/index?page_layout=logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</body>

</html>