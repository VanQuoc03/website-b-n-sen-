<?php
session_start();
ob_start();

// Bao gồm các file cấu hình và model cần thiết
require_once "../model/config.php";
require_once "../model/user.php";
require_once "../model/category.php";
require_once "../model/product.php";
require_once "../model/functions.php";


// Lấy ID sản phẩm từ tham số GET
$product_id = isset($_GET['idpro']) ? intval($_GET['idpro']) : 0;

// Kiểm tra nếu ID sản phẩm hợp lệ
if ($product_id > 0) {
    // Lấy thông tin sản phẩm
    $product = get_product_by_id($product_id);
    if ($product) {
        // Khởi tạo giỏ hàng nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
        if (isset($_SESSION['cart'][$product_id])) {
            // Tăng số lượng lên 1
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $_SESSION['cart'][$product_id] = [
                'id' => $product['product_id'],
                'name' => $product['name_product'],
                'price' => $product['price'],
                'image' => $product['image_product'],
                'quantity' => 1
            ];
        }

        // Chuyển hướng trở lại trang trước đó hoặc trang giỏ hàng
        header("Location: cart.php");
        exit();
    } else {
        echo "Không tìm thấy sản phẩm.";
    }
}
// Lấy tên trang hiện tại
$current_page = basename($_SERVER['PHP_SELF']);

// Xử lý cập nhật số lượng giỏ hàng
if (isset($_POST['update_cart']) && isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$id]); // Xóa sản phẩm nếu số lượng <= 0
        } else {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
    }
}

// Lấy giá trị 'id' và 'search' từ các tham số GET nếu có
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

// Xóa cờ 'cart_update' nếu không phải thêm sản phẩm
if (!isset($_GET['action']) || $_GET['action'] != 'add') {
    unset($_SESSION['cart_update']);
}

// Khởi tạo tổng số lượng sản phẩm và tổng giá trị trong giỏ hàng
$total_items = 0;
$total_price = 0.0;

// Tính tổng số lượng sản phẩm và tổng giá trị trong giỏ hàng
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total_items = count($_SESSION['cart']); // Đếm số lượng sản phẩm (không cộng dồn số lượng)
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'add' && $id !== null) {
    $quantity = isset($_GET['quantity']) ? max(1, intval($_GET['quantity'])) : 1;
    $product = get_pro_by_id($id);
    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity; // Tăng số lượng
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $_SESSION['cart'][$id] = [
                'id' => $product['product_id'],
                'name' => $product['name_product'],
                'price' => $product['price'],
                'image' => $product['image_product'],
                'quantity' => $quantity
            ];
        }
        header("Location: cart.php");
        exit(); // Ngừng thực thi script để tránh gửi lại yêu cầu
    }
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'remove' && $id !== null) {
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]); // Xóa sản phẩm khỏi giỏ hàng
    }
}

// Lấy danh sách danh mục sản phẩm
$category_list = getall_category();

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ogani | Giỏ Hàng</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- CSS Styles -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <link rel="stylesheet" href="../css/my.css" type="text/css">
</head>

<body>
    <!-- Page Preloader -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Menu Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <!-- Logo -->
        <div class="humberger__menu__logo">
            <a href="#"><img src="../img/logo.png" alt="Logo"></a>
        </div>

        <!-- Cart trong Humberger Menu -->
        <div class="humberger__menu__cart">
            <ul>
                <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                <li><a href="cart.php"><i class="fa fa-shopping-bag"></i> <span><?= $total_items ?></span></a></li>
            </ul>
            <div class="header__cart__price">Total: <span><?= number_format($total_price, 0, ',', '.') ?>đ</span></div>
        </div>

        <!-- Liên kết xác thực -->
        <div class="humberger__menu__widget">
            <div class="header__top__right__auth">
                <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
                    <a href="../index.php?page_layout=userinfo"><?= htmlspecialchars($_SESSION['username']) ?></a>
                    <a href="../index.php?page_layout=logout">Thoát</a>
                <?php else: ?>
                    <a href="index.php?page_layout=dangnhap"><i class="fa fa-user"></i> Login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="../index.php">Home</a></li>
                <li><a href="../index.php?page_layout=product">Sản phẩm</a>
                    <ul class="header__menu__dropdown">
                        <?php show_cate($category_list); ?>
                    </ul>
                </li>
                <li><a href="#">Hướng dẫn <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <ul class="header__menu__dropdown">
                        <li><a href="#">Hướng dẫn đặt hàng</a></li>
                        <li><a href="#">Hướng dẫn thanh toán</a></li>
                        <li><a href="#">Giao hàng</a></li>
                        <li><a href="#">Xem lại đơn hàng</a></li>
                    </ul>
                </li>
                <li><a href="#">Chăm sóc</a></li>
                <li><a href="./contact.html">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>

        <!-- Liên kết mạng xã hội -->
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
    </div>
    <!-- Humberger Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <!-- Bên trái (Có thể thêm nội dung ở đây nếu cần) -->
                    <div class="col-lg-6 col-md-6">
                        <!-- Bạn có thể thêm nội dung ở đây nếu cần -->
                    </div>
                    <!-- Bên phải -->
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <!-- Liên kết mạng xã hội -->
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>

                            <!-- Liên kết xác thực -->
                            <div class="header__top__right__auth">
                                <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
                                    <a href="../index.php?page_layout=userinfo"><?= htmlspecialchars($_SESSION['username']) ?></a>
                                    <a href="../index.php?page_layout=logout">Thoát</a>
                                <?php else: ?>
                                    <a href="../view/login.php"><i class="fa fa-user"></i> Login</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung chính của Header -->
        <div class="container">
            <div class="row">
                <!-- Logo -->
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="../index.php"><img src="../img/logo.png" alt="Logo"></a>
                    </div>
                </div>

                <!-- Menu điều hướng -->
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="../index.php">Home</a></li>
                            <li><a href="../index.php?page_layout=product">Sản phẩm <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                <ul class="header__menu__dropdown">
                                    <?php show_cate($category_list); ?>
                                </ul>
                            </li>
                            <li><a href="#">Hướng dẫn</a>
                                <ul class="header__menu__dropdown">
                                    <li><a href="#">Hướng dẫn đặt hàng</a></li>
                                    <li><a href="#">Hướng dẫn thanh toán</a></li>
                                    <li><a href="#">Giao hàng</a></li>
                                    <li><a href="#">Xem lại đơn hàng</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.html">Chăm sóc</a></li>
                            <li><a href="./contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>

                <!-- Giỏ hàng và Yêu thích -->
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                            <li><a href="cart.php"><i class="fa fa-shopping-bag"></i> <span><?= $total_items ?></span></a></li>
                        </ul>
                        <div class="header__cart__price">Total: <span><?= number_format($total_price, 0, ',', '.') ?>đ</span></div>
                    </div>
                </div>
            </div>
            <!-- Nút mở menu trên thiết bị di động -->
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <!-- Sidebar danh mục sản phẩm -->
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Danh mục sản phẩm</span>
                        </div>
                        <ul>
                            <?php show_cate($category_list); ?>
                        </ul>
                    </div>
                </div>

                <!-- Thanh tìm kiếm -->
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="product.php" method="GET">
                                <input type="hidden" name="page_layout" value="cart">
                                <!-- Sửa lỗi htmlspecialchars bằng cách đảm bảo $id không null -->
                                <input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>">
                                <input type="text" placeholder="What do you need?" name="search">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <!-- Bạn có thể thêm thông tin hỗ trợ khách hàng ở đây nếu cần -->
                            <!-- <div class="hero__search__phone__text">
                                <h5>+65 11.188.888</h5>
                                <span>support 24/7 time</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Giỏ Hàng</h2>
                        <!-- Bạn có thể thêm breadcrumb navigation ở đây nếu cần -->
                        <!-- <div class="breadcrumb__option">
                            <a href="../index.php">Home</a>
                            <span>Giỏ Hàng</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <!-- Bảng giỏ hàng -->
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <form method="POST" action="">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="shoping__product">Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                            <?php $subtotal = $item['price'] * $item['quantity']; ?>
                                            <tr>
                                                <td class="shoping__cart__item">
                                                    <img src="../img/product/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                                    <h5><?= htmlspecialchars($item['name']) ?></h5>
                                                </td>
                                                <td class="shoping__cart__price">
                                                    <?= number_format($item['price'], 0, ',', '.') ?>đ
                                                </td>
                                                <td class="shoping__cart__quantity">
                                                    <input type="number" name="quantity[<?= htmlspecialchars($id) ?>]" value="<?= htmlspecialchars($item['quantity']) ?>" min="0">
                                                </td>
                                                <td class="shoping__cart__total">
                                                    <?= number_format($subtotal, 0, ',', '.') ?>đ
                                                </td>
                                                <td class="shoping__cart__item__close">
                                                    <a href="cart.php?action=remove&id=<?= htmlspecialchars($id) ?>" class="icon_close"></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Giỏ hàng của bạn đang trống.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button type="submit" name="update_cart" class="primary-btn cart-btn cart-btn-right">Cập nhật giỏ hàng</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Nút tiếp tục mua sắm -->
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="product.php" class="primary-btn cart-btn">TIẾP TỤC MUA SẮM</a>
                    </div>
                </div>

                <!-- Mã giảm giá và Tóm tắt giỏ hàng -->
                <div class="col-lg-6">
                    <div class="shoping__continue">
                        <div class="shoping__discount">
                            <h5>Mã giảm giá</h5>
                            <form action="#">
                                <input type="text" placeholder="Nhập mã giảm giá">
                                <button type="submit" class="site-btn">ÁP DỤNG MÃ</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tóm tắt giỏ hàng -->
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Tổng giỏ hàng</h5>
                        <ul>
                            <li>Tạm tính <span><?= number_format($total_price, 0, ',', '.') ?>đ</span></li>
                            <li>Tổng cộng <span><?= number_format($total_price, 0, ',', '.') ?>đ</span></li>
                        </ul>
                        <a href="orders.php" class="primary-btn">TIẾN HÀNH THANH TOÁN</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

    <!-- Footer Section Begin -->
    <?php require_once "footer.php"; ?>
    <!-- Footer Section End -->
</body>

</html>