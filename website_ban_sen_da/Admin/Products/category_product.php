<body>
    <?php require_once '../../Admin/Products/header.php' ?>
    <div class="main--content">

        <div class="header--wrapper">
            <div class="header--title">
                <h2>Quản lý sản phẩm</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search">
                </div>
                <img src="<?php echo (strpos(__DIR__, 'Admin/Products') !== false || strpos(__DIR__, 'Products') !== false) ? '../../img/avatar.jpg' : '../img/avatar.jpg'; ?>" alt="Avatar">
            </div>
        </div>


        <?php require_once '/wampp/www/website_ban_sen_da/Admin/Products/product_list.php'; ?>
    </div>

</body>


</html>