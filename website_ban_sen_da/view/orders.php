<?php
session_start();

require_once "../model/config.php";
require_once "../model/user.php";
require_once "../model/category.php";
require_once "../model/product.php";
require_once "../model/functions.php";
require_once "../model/orders.php";

$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = null; // hoặc xử lý trường hợp không có ID phù hợp
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = null; // hoặc xử lý trường hợp không có ID phù hợp
}

$userName = $_SESSION['username'];
$userData = get_info_user($userName);
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển hướng về trang login
    exit();
}


// Lấy danh sách danh mục sản phẩm
$category_list = getall_category();

// Tính tổng số lượng sản phẩm và tổng giá trị trong giỏ hàng
$total_items = 0;
$total_price = 0.0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
        $total_price += $item['price'] * $item['quantity'];
    }
}

// Nếu giỏ hàng trống, chuyển hướng về trang giỏ hàng
if ($total_items == 0) {
    header("Location: cart.php");
    exit();
}

//Thêm orders và order_detail vào database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $note = $_POST['note'];
    $user = $userData['id'];
    $total_amount = $total_price;

    try {

        $conn->beginTransaction();
        $order_id = addOrder($conn, $fullname, $phone, $address, $email, $note, $user, $total_amount);

        addOrderDetail($conn, $order_id, $_SESSION['cart']);
        $conn->commit();

        unset($_SESSION['cart']);
        $_SESSION['order_success'] = true;
        header("Location: order_success.php?order_id=$order_id");
    } catch (Exception $e) {
        $conn->rollBack();
        $error_message = "Đã xảy ra lỗi khi đặt hàng: " . $e->getMessage();
    }
}


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
</head>

<body>

    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Ogani Template">
        <meta name="keywords" content="Ogani, unica, creative, html">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Sản phẩm</title>

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

        <!-- Css Styles -->
        <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="../css/elegant-icons.css" type="text/css">
        <link rel="stylesheet" href="../css/nice-select.css" type="text/css">
        <link rel="stylesheet" href="../css/jquery-ui.min.css" type="text/css">
        <link rel="stylesheet" href="../css/owl.carousel.min.css" type="text/css">
        <link rel="stylesheet" href="../css/slicknav.min.css" type="text/css">
        <link rel="stylesheet" href="../css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/my.css" type="text/css">
        <style>

        </style>
    </head>


    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="#"><img src="img/logo.png" alt=""></a>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                <li><a href="cart.php"><i class="fa fa-shopping-bag"></i> <span><?= $total_items ?></span></a></li>
            </ul>
            <div class="header__cart__price">item: <span><?= number_format($total_price, 0, ',', '.') ?>đ</span></div>
        </div>
        <div class="humberger__menu__widget">
            <!-- <div class="header__top__right__language">
                <img src="img/language.png" alt="">
                <div>English</div>
                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="#">Spanis</a></li>
                    <li><a href="#">English</a></li>
                </ul>
            </div> -->
            <div class="header__top__right__auth">
                <?php
                if (isset($_SESSION['username']) && ($_SESSION['username'] != "")) {
                    echo '<a href="../index.php?page_layout=userinfo">' . $_SESSION['username'] . '</a>';
                    if ($current_page == 'index.php') {
                        echo '<a href="index.php?page_layout=logout">Thoát</a>';
                    } else { // Nếu đang ở trang index.php hoặc các trang khác
                        echo '<a href="../index.php?page_layout=logout">Thoát</a>';
                    }
                    echo '<a href="index.php?page_layout=logout">Thoát</a>';
                } else {
                ?>
                    <a href="index.php?page_layout=dangnhap"><i class="fa fa-user"></i> Login</a>
                <?php } ?>
            </div>
        </div>
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="../index.php">Home</a></li>
                <li><a href="?page_layout=product">Sản phẩm <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <ul class="header__menu__dropdown">
                        <?php
                        // Kiểm tra xem trang hiện tại có phải là trang product.php hay không

                        foreach ($category_list as $item) {
                            // Nếu đang ở trang product.php
                            if ($current_page == 'index.php') {
                                echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                            } else { // Nếu đang ở trang index.php hoặc các trang khác
                                echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                            }
                        }
                        ?>

                        <!-- <li><a href="#">Sen đá</a></li>
                        <li><a href="#">Xương rồng</a></li>
                        <li><a href="#">Chậu đất nung</a></li>
                        <li><a href="#">phụ kiện trang trí</a></li>
                        <li><a href="#">Đất trồng, phân bón</a></li>
                        <li><a href="#">Sỏi trang trí</a></li>
                        <li><a href="#">Dụng cụ trồng sen đá</a></li> -->
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
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
        <!-- <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i> hello@colorlib.com</li>
                <li>Free Shipping for all Order of $99</li>
            </ul>
        </div> -->
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <!-- <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> hello@colorlib.com</li>
                                <li>Free Shipping for all Order of $99</li>
                            </ul>
                        </div> -->
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                            <!-- <div class="header__top__right__language">
                                <img src="img/language.png" alt="">
                                <div>English</div>
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="#">Spanis</a></li>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div> -->
                            <div class="header__top__right__auth">
                                <?php
                                if (isset($_SESSION['username']) && ($_SESSION['username'] != "")) {
                                    echo '<a href="../index.php?page_layout=userinfo">' . $_SESSION['username'] . '</a>';
                                    if ($current_page == 'userinfo.php') {
                                        echo '<a href="../index.php?page_layout=logout">Thoát</a>';
                                    } else { // Nếu đang ở trang index.php hoặc các trang khác
                                        echo '<a href="index.php?page_layout=logout">Thoát</a>';
                                    }
                                } else {
                                ?>
                                    <a href="../view/login.php"><i class="fa fa-user"></i>Login</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="./index.html"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="../index.php">Home</a></li>
                            <li><a href="product.php?page_layout=product">Sản phẩm <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                <ul class="header__menu__dropdown">
                                    <?php
                                    foreach ($category_list as $item) {
                                        if ($current_page == "index.php") {
                                            echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                        } else {
                                            echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                        }
                                    }

                                    ?>
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
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                            <li><a href="cart.php"><i class="fa fa-shopping-bag"></i> <span><?= $total_items ?></span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span><?= number_format($total_price, 0, ',', '.') ?>đ</span></div>
                    </div>
                </div>
            </div>
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
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Danh mục sản phẩm</span>
                        </div>
                        <ul>
                            <?php
                            foreach ($category_list as $item) {
                                if ($current_page == 'index.php') {
                                    echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                } else { // Nếu đang ở trang index.php hoặc các trang khác
                                    echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                }
                            }

                            ?>
                            <!-- <li><a href="#">Sen đá</a></li>
                            <li><a href="#">Xương rồng</a></li>
                            <li><a href="#">Chậu đất nung</a></li>
                            <li><a href="#">Chậu sứ</a></li>
                            <li><a href="#">Phụ kiện trang trí</a></li>
                            <li><a href="#">Đất trồng, phân bón</a></li>
                            <li><a href="#">Sỏi trang trí</a></li>
                            <li><a href="#">Dụng cụ trồng sen đá</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="product.php" method="GET">
                                <input type="hidden" value="<?php echo $id; ?>" name="idpro" />
                                <input type="text" placeholder="What do you need?" name="search">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
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


    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Thanh Toán</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <h4>Chi Tiết Thanh Toán</h4>
                <form action="" method="POST">
                    <div class="row">
                        <!-- Thông tin thanh toán -->
                        <div class="col-lg-7 col-md-6">

                            <div class="checkout__input">
                                <p>Họ và Tên<span>*</span></p>
                                <input type="text" name="fullname" value="<?= htmlspecialchars($userData['fullname']); ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Số Điện Thoại<span>*</span></p>
                                <input type="text" name="phone" value="<?= htmlspecialchars($userData['phone_number']) ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Địa Chỉ<span>*</span></p>
                                <input type="text" name="address" value="<?= htmlspecialchars($userData['address']) ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Email<span>*</span></p>
                                <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
                            </div>
                            <div class="checkout__input">
                                <p>Ghi Chú</p>
                                <textarea type="text" name="note" placeholder="Ghi chú về đơn hàng của bạn" rows="4" style="width: 100%;"></textarea>
                            </div>
                        </div>

                        <!-- Đơn hàng của bạn -->
                        <div class="col-lg-5 col-md-6">
                            <div class="checkout__order">
                                <h4>Đơn Hàng Của Bạn</h4>
                                <div class="checkout__order__products">Sản Phẩm <span>Thành Tiền</span></div>
                                <ul>
                                    <?php foreach ($_SESSION['cart'] as $item): ?>
                                        <li>
                                            <div class="order_item">
                                                <img src="../img/product/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50" height="50">
                                                <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                                                <span><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.') . 'đ'; ?></span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="checkout__order__subtotal">Tạm Tính <span><?php echo number_format($total_price, 0, ',', '.') . 'đ'; ?></span></div>
                                <div class="checkout__order__total">Tổng Cộng <span><?php echo number_format($total_price, 0, ',', '.') . 'đ'; ?></span></div>

                                <!-- Phương thức thanh toán -->
                                <div>
                                    <h4>Phương thức thanh toán</h4>
                                    <div class="checkout__input__checkbox">
                                        <label for="payment1">
                                            Thanh toán khi nhận hàng
                                            <input type="radio" id="payment1" name="payment_method" value="cod" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="payment2">
                                            Thanh toán online
                                            <input type="radio" id="payment2" name="payment_method" value="online">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Nút đặt hàng -->
                                <button type="submit" class="site-btn">ĐẶT HÀNG</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <!-- Footer Section Begin -->
    <?php include "footer.php"; ?>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <!-- Các plugin khác nếu cần -->

</html>