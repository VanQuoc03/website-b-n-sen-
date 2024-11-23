<?php

function getall_product($category_id = 0, $keyword = "", $orderCondition = "", $minPrice = 0, $maxPrice = 0)
{
    $conn = connectdb();
    $sql = "SELECT * FROM products WHERE 1";

    // Lọc theo category_id nếu có
    if ($category_id > 0) {
        $sql .= " AND category_id = :category_id";
    }

    // Lọc theo từ khóa nếu có
    if ($keyword != "") {
        $sql .= " AND name_product LIKE :keyword";
    }

    // Lọc theo khoảng giá nếu có
    if ($minPrice > 0 || $maxPrice > 0) {
        $sql .= " AND price BETWEEN :minPrice AND :maxPrice";
    }

    // Thêm điều kiện sắp xếp nếu có
    if ($orderCondition != "") {
        $sql .= " " . $orderCondition;
    }

    $stmt = $conn->prepare($sql);

    // Gán giá trị tham số
    if ($category_id > 0) {
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }
    if ($keyword != "") {
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    }
    if ($minPrice > 0 || $maxPrice > 0) {
        $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
        $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
    }

    $stmt->execute();

    // Trả kết quả dưới dạng mảng
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    return $kq;
}



function get_pro_by_id($idpro)
{
    $conn = connectdb(); // Đảm bảo kết nối PDO được sử dụng
    $sql = "SELECT * FROM products WHERE product_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $idpro, PDO::PARAM_INT); // Tránh SQL Injection
    $stmt->execute();
    // Trả về một hàng duy nhất
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_product_category_lienquan($id_cate, $idpro, $limi)
{
    $conn = connectdb();
    $sql = "SELECT * FROM products WHERE category_id = ? AND product_id <> ? LIMIT $limi"; // Xóa ORDER BY view DESC
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_cate, $idpro]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






function show_product_lienquan($category_product)
{
    $html_dssp = '';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = null; // hoặc xử lý trường hợp không có ID phù hợp
    }
    foreach ($category_product as $product) {
        if ($product['price'] == 0) {
            $price =  '<h5>Đang cập nhật</h5>';
        } else {
            $price = $product['price'];
        }
        $link = "../index.php?page_layout=product_detail&idpro=" . $product['product_id'];
        $html_dssp .= '<div class="col-lg-4 col-md-6 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg">
                    <a href="' . $link . '">
                        <img src="../img/product/' . $product['image_product'] . '" alt=""></a>
                        <ul class="product__item__pic__hover">
                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">' . $product['name_product'] . '</a></h6>
                        <h5>' . $price . 'đ' . '</h5>
                    </div>
                </div>
            </div>';
    }
    return $html_dssp;
}
function show_product($category_product)
{
    $html_dssp = '';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = null; // hoặc xử lý trường hợp không có ID phù hợp
    }
    foreach ($category_product as $product) {
        $price = number_format($product['price'], 0, ',', '.') == 0 ? '<h5>Đang cập nhật</h5>' : $product['price'];

        $link_detail = "../index.php?page_layout=product_detail&idpro=" . $product['product_id'];
        $link_add_to_cart = "cart.php?action=add&id=" . $product['product_id'];
        echo '<div class="col-lg-4 col-md-6 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg">
                    <a href="' . $link_detail . '">
                        <img src="../img/product/' . $product['image_product'] . '" alt=""></a>
                        <ul class="product__item__pic__hover">
                            <li><a href="' . $link_detail . '"><i class="fa fa-eye"></i></a></li>
                            <li><a href="' . $link_add_to_cart . '"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="' . $link_detail . '">' . $product['name_product'] . '</a></h6>
                        <h5>' . number_format($price, 0, ',', '.') . 'đ' . '</h5>
                    </div>
                </div>
            </div>';
    }
}
