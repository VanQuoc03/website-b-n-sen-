<?php
// model/functions.php

// Bao gồm tệp config.php sử dụng __DIR__ để đảm bảo đường dẫn đúng
require_once __DIR__ . "/config.php";

/**
 * Lấy danh sách sản phẩm mới
 *
 * @param int $limit Số lượng sản phẩm muốn lấy
 * @return array
 */
function get_new_products($limit = 8)
{
    global $conn;
    $sql = "SELECT * FROM products ORDER BY date_added DESC LIMIT :limit";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

/**
 * Lấy danh sách sản phẩm nổi bật dựa trên số lượt xem
 *
 * @param int $limit Số lượng sản phẩm muốn lấy
 * @return array
 */
function get_featured_products($limit = 8)
{
    global $conn;
    $sql = "SELECT * FROM products ORDER BY view_count DESC LIMIT :limit";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

/**
 * Lấy danh sách tất cả các danh mục sản phẩm
 *
 * @return array
 */
// function get_all_categories()
// {
//     global $conn;
//     $sql = "SELECT * FROM categories";
//     try {
//         $stmt = $conn->query($sql);
//         $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $categories;
//     } catch (PDOException $e) {
//         die("Query failed: " . $e->getMessage());
//     }
// }

/**
 * Lấy danh sách sản phẩm bán chạy nhất
 *
 * @param int $limit Số lượng sản phẩm muốn lấy
 * @return array
 */
function get_best_selling_products($limit = 5)
{
    global $conn;
    $sql = "SELECT p.*, SUM(od.quantity) AS total_sold
            FROM products p
            INNER JOIN orders_detail od ON p.product_id = od.product_id
            GROUP BY p.product_id
            ORDER BY total_sold DESC
            LIMIT :limit";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

/**
 * Lấy danh sách sản phẩm được đánh giá cao nhất
 *
 * @param int $limit Số lượng sản phẩm muốn lấy
 * @return array
 */
function get_top_rated_products($limit = 5)
{
    global $conn;
    $sql = "SELECT p.*, AVG(r.rating) AS average_rating
            FROM products p
            INNER JOIN reviews r ON p.product_id = r.product_id
            GROUP BY p.product_id
            ORDER BY average_rating DESC
            LIMIT :limit";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

/**
 * Lấy danh sách sản phẩm xem nhiều nhất
 *
 * @param int $limit Số lượng sản phẩm muốn lấy
 * @return array
 */
function get_most_viewed_products($limit = 5)
{
    global $conn;
    $sql = "SELECT * FROM products ORDER BY view_count DESC LIMIT :limit";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
function increment_view_count($product_id)
{
    global $conn;
    $sql = "UPDATE products SET view_count = view_count + 1 WHERE product_id = :product_id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}



/**
 * Lấy danh sách các bài viết mới nhất
 *
 * @param int $limit Số lượng bài viết muốn lấy
 * @return array
 */
function get_latest_posts($limit = 3)
{
    global $conn;
    $sql = "SELECT * FROM posts ORDER BY date_created DESC LIMIT :limit";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

/**
 * Lấy thông tin chi tiết sản phẩm theo ID
 *
 * @param int $product_id ID của sản phẩm
 * @return array|null
 */
function get_product_by_id($product_id)
{
    global $conn;
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
