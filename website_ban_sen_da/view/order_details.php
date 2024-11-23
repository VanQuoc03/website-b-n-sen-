<?php
// Kết nối cơ sở dữ liệu
require '../model/config.php';
require '../model/orders.php';


$order_code = randomOrderID();

// Lấy order_id từ GET
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
// Truy vấn thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order_id) {
    die("Đã xảy ra lỗi. Không tìm thấy ID đơn hàng.");
}
// Nếu không tìm thấy đơn hàng
if (!$order) {
    die("Không tìm thấy đơn hàng.");
}

// Truy vấn chi tiết đơn hàng
$stmt = $conn->prepare("SELECT * FROM order_details WHERE order_id = :order_id");
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../css/my.css" type="text/css">
</head>

<body>
    <div class="container mt-5">
        <div class="order-summary">
            <h2 class="text-center">Chi tiết đơn hàng</h2>
            <p>Mã đơn hàng: <strong>#<?= htmlspecialchars($order_code) ?></strong></p>
            <p>Người mua: <?= htmlspecialchars($order['name']) ?></p>
            <p>Ngày đặt hàng: <?= htmlspecialchars($order['order_date']) ?></p>
            <p>Tổng tiền: <?= number_format($order['total_amount'], 0, ',', '.') ?>đ</p>
            <p>Ghi chú: <?= htmlspecialchars($order['note']) ?></p>

            <h3>Sản phẩm:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td><?= number_format($item['product_price'], 0, ',', '.') ?>đ</td>
                            <td><?= number_format($item['subtotal'], 0, ',', '.') ?>đ</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3">Tổng cộng:</td>
                        <td><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                    </tr>
                </tfoot>
            </table>
            <a href="../index.php" class="btn-back">Quay lại Trang chủ</a>
        </div>
    </div>
</body>

</html>