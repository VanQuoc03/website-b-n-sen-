<?php
require_once '/wampp/www/website_ban_sen_da/model/config.php';
$stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = :status where id = :order_id");
    $stmt->bindParam(':status', $new_status);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();

    header("Location: orders.php");
    exit();
}
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
    <title>Quản lý đơn đặt hàng</title>
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
                    <span>Quản lý đơn đặt hàng</span>
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
                <h2>Danh sách đơn đặt hàng</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <th><?= htmlspecialchars($order['id']); ?></th>
                                <th><?= htmlspecialchars($order['order_code']); ?></th>
                                <th><?= htmlspecialchars($order['name']); ?></th>
                                <th><?= htmlspecialchars($order['total_amount']); ?></th>
                                <th><?= htmlspecialchars($order['order_date']); ?></th>
                                <th><?= htmlspecialchars($order['status']); ?></th>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" id="" class="form-control">
                                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang giao hàng</option>
                                            <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Cập nhật</button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>