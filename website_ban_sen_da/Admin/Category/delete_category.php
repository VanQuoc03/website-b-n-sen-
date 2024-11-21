<?php
include_once '../../model/config.php';

$connect = connectdb();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Xóa các sản phẩm liên quan trước
    $sql_delete_products = "DELETE FROM products WHERE category_id = :id";
    $stmt = $connect->prepare($sql_delete_products);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Xóa danh mục
    $sql = "DELETE FROM categories WHERE category_id = :id";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: Category/category.php');
    exit;
} else {
    echo "Không tìm thấy id.";
    exit;
}
