<?php
session_start();
ob_start();
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
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển hướng về trang login
    exit();
}

// Tính tổng số lượng sản phẩm trong giỏ hàng
$total_items = 0;
$total_price = 0.0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
    }

    //Tổng tiền của giỏ hàng
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity']; // Giá * Số lượng
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
require_once "../model/config.php";
require_once "../model/user.php";
require_once "../model/category.php";
require_once "../model/product.php";
$category_list = getall_category();
$category_product = getall_product($id, $search, "");
$username = $_SESSION['username']; // Lấy username từ session

// Lấy thông tin tài khoản
// $conn = connectdb();
// $stmt = $conn->prepare("SELECT * FROM user WHERE user = :username");
// $stmt->bindParam(':username', $username);
// $stmt->execute();
$user_data = get_info_user($username);

if (!$user_data) {
    echo "Không tìm thấy thông tin tài khoản.";
    exit();
}

// Xử lý cập nhật thông tin
$error_message = "";
$success_message = "";


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

    </head>

    <body>
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
                <div class="header__cart__price">item: <span>$150.00</span></div>
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
                        echo '<a href="index.php?page_layout=userinfo">' . $_SESSION['username'] . '</a>';
                        if ($current_page == 'product.php') {
                            echo '<a href="../index.php?page_layout=logout">Thoát</a>';
                        } else { // Nếu đang ở trang index.php hoặc các trang khác
                            echo '<a href="index.php?page_layout=logout">Thoát</a>';
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
                                if ($current_page == 'product.php') {
                                    echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                } else { // Nếu đang ở trang index.php hoặc các trang khác
                                    echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
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
                                        echo '<a href="index.php?page_layout=userinfo">' . $_SESSION['username'] . '</a>';
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
                                            if ($current_page == "userinfo.php") {
                                                echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                            } else {
                                                echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
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
                            <div class="header__cart__price">item: <span>$150.00</span></div>
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
                                    if ($current_page == 'product.php') {
                                        echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
                                    } else { // Nếu đang ở trang index.php hoặc các trang khác
                                        echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
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
        <section class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="breadcrumb__text">
                            <h2>Thông Tin Tài Khoản</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Breadcrumb Section End -->

        <!-- Account Section Begin -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="account__form">
                        <h3>Chào <?= htmlspecialchars($user_data['fullname'] ?? '') ?> </h3>
                        <!-- Hiển thị thông báo -->
                        <?php if (!empty($error_message)) : ?>
                            <div class="alert alert-danger"><?= $error_message ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)) : ?>
                            <div class="alert alert-success"><?= $success_message ?></div>
                        <?php endif; ?>

                        <!-- Form cập nhật thông tin -->
                        <form action="" method="POST">
                            <a href="userinfo.php">Cập nhật thông tin</a>
                            <a href="change_pass.php">Đổi mật khẩu</a>


                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Account Section End -->

        <!-- Footer -->
        <?php include 'footer.php'; ?>

        <!-- Js Plugins -->
        <script src="../js/jquery-3.3.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>

</html>