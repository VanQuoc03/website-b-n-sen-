<?php
// Lấy danh sách sản phẩm và bài viết
$new_products = get_new_products();
$featured_products = get_featured_products();
$best_selling_products = get_best_selling_products();
$top_rated_products = get_top_rated_products();
$most_viewed_products = get_most_viewed_products();
$latest_posts = get_latest_posts();

// Lấy danh sách danh mục sản phẩm
$categories = getall_category();

// Tính tổng giá trị giỏ hàng (nếu có)
$total_items = 0;
$total_price = 0.0;

// Tính tổng số lượng sản phẩm và tổng giá trị trong giỏ hàng
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total_items = count($_SESSION['cart']); // Đếm số lượng sản phẩm (không cộng dồn số lượng)
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ogani | Trang Chủ</title>

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
                <li><a href="cart.php"><i class="fa fa-shopping-bag"></i> <span><?php echo count($_SESSION['cart'] ?? []); ?></span></a></li>
            </ul>
            <div class="header__cart__price">Total: <span><?php echo number_format($total_price, 0, ',', '.') ?>đ</span></div>
        </div>

        <!-- Liên kết xác thực -->
        <div class="humberger__menu__widget">
            <div class="header__top__right__auth">
                <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
                    <a href="../index.php?page_layout=userinfo"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <a href="../index.php?page_layout=logout">Thoát</a>
                <?php else: ?>
                    <a href="index.php?page_layout=dangnhap"><i class="fa fa-user"></i> Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="../index.php">Trang Chủ</a></li>
                <li><a href="../index.php?page_layout=product">Sản Phẩmc</a>
                    <ul class="header__menu__dropdown">
                        <?php foreach ($categories as $category): ?>
                            <li><a href="index.php?page_layout=product&id=<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name_cate']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="#">Hướng Dẫn <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <ul class="header__menu__dropdown">
                        <li><a href="#">Hướng dẫn đặt hàng</a></li>
                        <li><a href="#">Hướng dẫn thanh toán</a></li>
                        <li><a href="#">Giao hàng</a></li>
                        <li><a href="#">Xem lại đơn hàng</a></li>
                    </ul>
                </li>
                <li><a href="#">Chăm Sóc</a></li>
                <li><a href="./contact.html">Liên Hệ</a></li>
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
                                    <a href="../index.php?page_layout=userinfo"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                                    <a href="../index.php?page_layout=logout">Thoát</a>
                                <?php else: ?>
                                    <a href="../view/login.php"><i class="fa fa-user"></i> Đăng nhập</a>
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
                            <li class="active"><a href="../index.php">Trang Chủ</a></li>
                            <li><a href="../index.php?page_layout=product">Sản Phẩm <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                <ul class="header__menu__dropdown">
                                    <?php foreach ($categories as $category): ?>
                                        <li><a href="index.php?page_layout=product"><?php echo htmlspecialchars($category['name_cate']); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li><a href="#">Hướng Dẫn</a>
                                <ul class="header__menu__dropdown">
                                    <li><a href="#">Hướng dẫn đặt hàng</a></li>
                                    <li><a href="#">Hướng dẫn thanh toán</a></li>
                                    <li><a href="#">Giao hàng</a></li>
                                    <li><a href="#">Xem lại đơn hàng</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.html">Chăm Sóc</a></li>
                            <li><a href="./contact.html">Liên Hệ</a></li>
                        </ul>
                    </nav>
                </div>

                <!-- Giỏ hàng và Yêu thích -->
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                            <li><a href="cart.php"><i class="fa fa-shopping-bag"></i> <span><?php echo count($_SESSION['cart'] ?? []); ?></span></a></li>
                        </ul>
                        <div class="header__cart__price">Total: <span><?php echo number_format($total_price, 0, ',', '.') ?>đ</span></div>
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
                            <?php foreach ($categories as $category): ?>
                                <li><a href="index.php?page_layout=product&id=<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name_cate']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Thanh tìm kiếm -->
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="view/product.php" method="GET">
                                <input type="hidden" name="page_layout" value="cart">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id ?? ''); ?>">
                                <input type="text" placeholder="Bạn cần gì?" name="search">
                                <button type="submit" class="site-btn">TÌM KIẾM</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <!-- Bạn có thể thêm thông tin hỗ trợ khách hàng ở đây nếu cần -->
                            <!-- <div class="hero__search__phone__text">
                                <h5>+65 11.188.888</h5>
                                <span>Hỗ trợ 24/7</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="../img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Trang Chủ</h2>
                        <!-- Bạn có thể thêm breadcrumb navigation ở đây nếu cần -->
                        <!-- <div class="breadcrumb__option">
                            <a href="../index.php">Trang Chủ</a>
                            <span>Giỏ Hàng</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->