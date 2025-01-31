<?php
require_once '/wampp/www/website_ban_sen_da/model/config.php';
$connect = connectdb();
$query = $connect->query("SELECT * FROM categories");
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
                <a href="../index?page_layout=logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>

    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h2>Danh mục</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>STT</th>
                            <th>Tên danh mục</th>
                            <th>Sửa</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $query->fetch()) {
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['name_cate']; ?></td>
                                <td>
                                    <a href="../index.php?page_layout=edit_category&id=<?php echo $row['category_id']; ?>">Sửa</a>

                                </td>
                                <td>
                                    <a onclick="return Del('<?php echo $row['name_cate']; ?>')" href="../index.php?page_layout=delete_category&id=<?php echo $row['category_id']; ?>">Xóa</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="../index.php?page_layout=add_category" class="btn btn-primary">Thêm Danh mục</a>
            </div>
        </div>
    </div>
</body>
<script>
    function Del(name) {
        return confirm("Bạn có chắc chắn muốn xóa sản phẩm: " + name + "?");
    }
</script>