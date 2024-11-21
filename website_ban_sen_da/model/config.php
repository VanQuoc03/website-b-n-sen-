<?php


function connectdb()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "web_ban_sen_da";
    try {
        // Tạo kết nối PDO
        $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password);
        // Thiết lập chế độ lỗi PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn; // Trả về đối tượng kết nối
    } catch (PDOException $e) {
        die("Kết nối thất bại: " . $e->getMessage());
    }
}

global $conn;
$conn = connectdb();
