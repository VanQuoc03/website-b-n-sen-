<?php



?>
<!-- Categories Section Begin -->
<section class="categories">
    <div class="container">
        <div class="row">
            <div class="categories__slider owl-carousel">
                <?php foreach ($categories as $category): ?>
                    <div class="col-lg-3">
                        <div class="categories__item set-bg">
                            <img src="../img/categories/<?php echo htmlspecialchars($category['image']); ?>" alt="<?php echo htmlspecialchars($category['name_cate']); ?>">
                            <h5><a href="category.php?id=<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name_cate']); ?></a></h5>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->

<!-- New Products Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Sản phẩm mới</h2>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <?php foreach ($new_products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix">
                    <div class="featured__item">
                        <div class="featured__item__pic">
                            <a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>">
                                <img src="../img/product/<?php echo htmlspecialchars($product['image_product']); ?>" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
                            </a>
                            <ul class="featured__item__pic__hover">
                                <li><a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>"><i class="fa fa-eye"></i></a></li>
                                <li><a href="view/cart.php?idpro=<?php echo $product['product_id']; ?>"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>"><?php echo htmlspecialchars($product['name_product']); ?></a></h6>
                            <h5><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- New Products Section End -->

<!-- Featured Products Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Sản phẩm nổi bật</h2>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <?php foreach ($featured_products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix">
                    <div class="featured__item">
                        <div class="featured__item__pic">
                            <a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>">
                                <img src="../img/product/<?php echo htmlspecialchars($product['image_product']); ?>" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
                            </a>
                            <ul class="featured__item__pic__hover">
                                <li><a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>"><i class="fa fa-eye"></i></a></li>
                                <li><a href="add-to-cart.php?id=<?php echo $product['product_id']; ?>"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>"><?php echo htmlspecialchars($product['name_product']); ?></a></h6>
                            <h5><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Featured Products Section End -->

<!-- Banner Begin -->
<div class="banner">
    <div class="container">
        <div class="row">
            <!-- Bạn có thể thêm các banner quảng cáo ở đây -->
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="banner__pic">
                    <img src="../img/banner/banner-1.jpg" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="banner__pic">
                    <img src="../img/banner/banner-2.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner End -->

<!-- Latest Products Section Begin -->
<section class="latest-product spad">
    <div class="container">
        <div class="row">
            <!-- Sản phẩm bán chạy -->
            <div class="col-lg-4 col-md-6">
                <div class="latest-product__text">
                    <h4>Sản phẩm bán chạy</h4>
                    <div class="latest-product__slider owl-carousel">
                        <?php foreach ($best_selling_products as $product): ?>
                            <a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>" class="latest-product__item">
                                <div class="latest-product__item__pic">
                                    <img src="../img/product/<?php echo htmlspecialchars($product['image_product']); ?>" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
                                </div>
                                <div class="latest-product__item__text">
                                    <h6><?php echo htmlspecialchars($product['name_product']); ?></h6>
                                    <span><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Sản phẩm được đánh giá cao -->
            <div class="col-lg-4 col-md-6">
                <div class="latest-product__text">
                    <h4>Sản phẩm được đánh giá cao</h4>
                    <div class="latest-product__slider owl-carousel">
                        <?php foreach ($top_rated_products as $product): ?>
                            <a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>" class="latest-product__item">
                                <div class="latest-product__item__pic">
                                    <img src="../img/product/<?php echo htmlspecialchars($product['image_product']); ?>" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
                                </div>
                                <div class="latest-product__item__text">
                                    <h6><?php echo htmlspecialchars($product['name_product']); ?></h6>
                                    <span><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Sản phẩm xem nhiều -->
            <div class="col-lg-4 col-md-6">
                <div class="latest-product__text">
                    <h4>Sản phẩm xem nhiều</h4>
                    <div class="latest-product__slider owl-carousel">
                        <?php foreach ($most_viewed_products as $product): ?>
                            <a href="index.php?page_layout=product_detail&idpro=<?php echo $product['product_id']; ?>" class="latest-product__item">
                                <div class="latest-product__item__pic">
                                    <img src="../img/product/<?php echo htmlspecialchars($product['image_product']); ?>" alt="<?php echo htmlspecialchars($product['name_product']); ?>">
                                </div>
                                <div class="latest-product__item__text">
                                    <h6><?php echo htmlspecialchars($product['name_product']); ?></h6>
                                    <span><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Products Section End -->

<!-- Blog Section Begin -->
<section class="from-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title from-blog__title">
                    <h2>Bài viết mới</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Hiển thị các bài viết từ cơ sở dữ liệu -->
            <?php foreach ($latest_posts as $post): ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="../img/blog/<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> <?php echo date('d/m/Y', strtotime($post['date_created'])); ?></li>
                                <!-- Nếu có chức năng bình luận, bạn có thể hiển thị số bình luận -->
                                <!-- <li><i class="fa fa-comment-o"></i> 5</li> -->
                            </ul>
                            <h5><a href="blog-detail.php?id=<?php echo $post['post_id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h5>
                            <p><?php echo htmlspecialchars($post['summary']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Blog Section End -->

<!-- Footer Section Begin -->
<?php require_once "footer.php"; ?>
<!-- Footer Section End -->
</body>

</html>