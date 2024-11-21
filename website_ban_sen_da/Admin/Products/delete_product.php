<?php
include_once '../../model/config.php'; // Đảm bảo đường dẫn đúng đến file config.php

// Khởi tạo kết nối bằng hàm connectdb() từ config.php
$conn = connectdb();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($conn) {
        $sql = "DELETE FROM products WHERE product_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header('Location: index.php?page_layout=product');
            exit();
        } else {
            echo "Xóa sản phẩm thất bại.";
        }
    } else {
        echo "Kết nối cơ sở dữ liệu thất bại.";
    }
} else {
    echo "Không tìm thấy id.";
}
