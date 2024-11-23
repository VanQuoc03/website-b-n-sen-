<?php
// Lấy thông tin đơn hàng từ bảng orders
function getOrder($conn, $order_id)
{
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một dòng dữ liệu
}

// Lấy chi tiết sản phẩm của đơn hàng từ bảng order_details
function getOrderDetails($conn, $order_id)
{
    $stmt = $conn->prepare("SELECT * FROM order_details WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách các sản phẩm
}

function randomOrderID($length = 8)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Bao gồm chữ hoa và số
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function addOrder($conn, $fullname, $address, $email, $phone, $note, $user, $total_amount)
{
    $order_code = randomOrderID();
    $stmt = $conn->prepare("INSERT INTO orders (order_code, name, address, email, tel, note, user_id, total_amount)
        VALUES (:order_code, :name, :address, :email, :tel, :note, :user_id, :total_amount)");
    $stmt->bindParam(':order_code', $order_code);
    $stmt->bindParam(':name', $fullname);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':tel', $phone);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':user_id', $user);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->execute();

    // Trả về ID đơn hàng vừa thêm
    return $conn->lastInsertId();
}
function addOrderDetail($conn, $order_id, $cart)
{
    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, product_name, product_price, quantity, subtotal)
        VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :subtotal)");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $item['id']);
        $stmt->bindParam(':product_name', $item['name']);
        $stmt->bindParam(':product_price', $item['price']);
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':subtotal', $subtotal);
        $stmt->execute();
    }
}
