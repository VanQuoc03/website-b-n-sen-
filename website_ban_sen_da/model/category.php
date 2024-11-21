<?php
require_once 'config.php';
function getall_category()
{
    global $conn; // Sử dụng biến kết nối toàn cục
    $sql = "SELECT * FROM categories";
    try {
        $stmt = $conn->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}


function show_cate($category_list)
{
    if (!is_array($category_list) || empty($category_list)) {
        echo "<li>Không có danh mục nào.</li>";
        return;
    }
    $current_page = basename($_SERVER['PHP_SELF']);
    foreach ($category_list as $item) {
        if ($current_page == 'index.php') {
            echo '<li><a href="index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
        } else {
            echo '<li><a href="../index.php?page_layout=product&id=' . $item['category_id'] . '">' . $item['name_cate'] . '</a></li>';
        }
    }
}
