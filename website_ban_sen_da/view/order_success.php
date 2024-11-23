<?php
session_start();

require_once "../model/config.php";
require_once "../model/user.php";
require_once "../model/category.php";
require_once "../model/product.php";
require_once "../model/functions.php";
require_once "../model/orders.php";


// Kiểm tra xem order_id có được truyền qua không
if (!isset($_GET['order_id'])) {
    die("Không tìm thấy đơn hàng.");
}

$order_id = ($_GET['order_id']);

// Kiểm tra nếu đơn hàng đã được đặt
if (!isset($_SESSION['order_success']) || $_SESSION['order_success'] !== true) {
    header("Location: ../index.php");
    exit();
}

 // Nếu chưa đăng nhập, chuyển hướng về trang login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Hủy trạng thái đặt hàng để tránh truy cập lại trang này
unset($_SESSION['order_success']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Tài Khoản</title>

    <!-- Css Styles -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <link rel="stylesheet" href="../css/my.css" type="text/css">

</head>

<body>


    <!-- Page Preloder -->


    <section class="breadcrumb-section set-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Đặt Hàng Thành Công</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad">
        <div class="container text-center">
            <h3 class="mt-5 mb-3">Cảm ơn bạn đã đặt hàng!</h3>
            <p class="mb-4">Đơn hàng của bạn đã được đặt thành công. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
            <p><a href="order_details.php?order_id=<?= htmlspecialchars($order_id) ?>">Xem chi tiết đơn hàng</a></p>
            <a href="../index.php" class="site-btn">Quay lại Trang Chủ</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <!-- Js Plugins -->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>