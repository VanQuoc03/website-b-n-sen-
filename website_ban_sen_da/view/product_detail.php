<?php
session_start();
ob_start();
require_once "../model/config.php";
require_once "../model/user.php";
require_once "../model/category.php";
require_once "../model/product.php";
require_once "../model/functions.php";

// Lấy ID sản phẩm từ tham số GET
$product_id = isset($_GET['idpro']) ? intval($_GET['idpro']) : 0;

// Kiểm tra nếu ID sản phẩm hợp lệ
if ($product_id > 0) {
    // Tăng view_count
    increment_view_count($product_id);
    // Lấy thông tin sản phẩm để hiển thị
    $product = get_product_by_id($product_id); // Bạn cần định nghĩa hàm này trong functions.php
    if ($product) {
        // Hiển thị thông tin sản phẩm
        // ...
    } else {
        echo "Không tìm thấy sản phẩm.";
    }
} else {
    echo "ID sản phẩm không hợp lệ.";
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

if (isset($_GET['idpro'])) {
    $idpro = $_GET['idpro'];
    $product_detail = get_pro_by_id($idpro);
    if ($product_detail === false) {
        echo "Lỗi: Không tìm thấy sản phẩm với ID được cung cấp.";
    }
    $category_list = getall_category();
    $id_cate = $product_detail['category_id'];
    $related_product = get_product_category_lienquan($id_cate, $idpro, 4);
} else {
    echo "Lỗi: ID sản phẩm không được cung cấp.";
}

if (isset($_GET['idpro'])) {
    $id = $_GET['idpro'];
} else {
    $id = null; // hoặc xử lý trường hợp không có ID phù hợp
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $category_id = isset($_GET['id']) ? $_GET['id'] : 0;
    header("Location: view/product.php?id=$category_id&search=$search");
} else {
    $search = null; // hoặc xử lý trường hợp không có ID phù hợp
}
$current_page = basename($_SERVER['PHP_SELF']);

$category_list = getall_category();

if (is_array($product_detail) && !empty($product_detail)) {
    extract($product_detail);
} else {
    echo "Lỗi: Sản phẩm không tồn tại hoặc không thể truy xuất chi tiết sản phẩm.";
}
$category_product = getall_product($id);
$html_sp_lienquan = show_product_lienquan($related_product);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($product_detail['name_product']) ?> - Chi tiết sản phẩm</title>

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
            <div class="header__cart__price">item: <span><span><?= number_format($total_price, 0, ',', '.') ?>đ</span></div>
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
                <li><a href="../index.php?page_layout=product">Sản phẩm </a>
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
                                    echo '<a href="../index.php?page_layout=userinfo">' . $_SESSION['username'] . '</a>';
                                    if ($current_page == 'product.php') {
                                        echo '<a href="../index.php?page_layout=logout">Thoát</a>';
                                    } else if ($current_page == 'product_detail.php') {
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
                            <li><a href="../index.php?page_layout=product">Sản phẩm <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                <ul class="header__menu__dropdown">
                                    <?php
                                    show_cate($category_list);

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
                        <div class="header__cart__price">item: <span><span><?= number_format($total_price, 0, ',', '.') ?>đ</span></div>
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
                            show_cate($category_list);

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
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Chi tiết sản phẩm</h2>
                        <!-- <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <a href="./index.html">Vegetables</a>
                            <span>Vegetable’s Package</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large" src="../img/product/<?= $product_detail['image_product'] ?>" alt="<?= htmlspecialchars($product_detail['name_product']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Thông tin sản phẩm -->
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3><?= htmlspecialchars($product_detail['name_product']) ?></h3>
                        <div class="product__details__price"><?= number_format($product_detail['price'], 0, ',', '.') ?>đ</div>

                        <div class="product__details__quantity">
                            <form method="GET" action="../view/cart.php">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id" value="<?= $product_detail['product_id'] ?>">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="number" name="quantity" value="1" min="1">
                                    </div>
                                </div>
                                <button type="submit" class="primary-btn">Thêm vào giỏ hàng</button>
                            </form>

                        </div>
                        <ul>
                            <li><b>Trạng thái:</b> <span>Còn hàng</span></li>
                            <li><b>Vận chuyển:</b> <span>Giao hàng trong 1 ngày. Miễn phí nhận hàng hôm nay.</span></li>
                            <li><b>Trọng lượng:</b> <span>0.5 kg</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="product__details__tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a style="margin-left: 0;" class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                            aria-selected="true">MÔ TẢ SẢN PHẨM</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                    <div class="product__details__tab__desc">
                        <p><?= htmlspecialchars($product_detail['description']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->

    <!-- Related Product Section Begin -->
    <section class="related-product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title related__product__title">
                        <h2>Sản phẩm liên quan</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= $html_sp_lienquan;  ?>
                <!-- <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-2.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-3.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-7.jpg">
                            <ul class="product__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">Crab Pool Security</a></h6>
                            <h5>$30.00</h5>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>
    <!-- Related Product Section End -->

    <!-- Footer Section Begin -->
    <?php
    require_once "footer.php";
    ?>


</body>

</html>