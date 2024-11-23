<?php


if (!function_exists('connectdb')) {
    function connectdb()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "web_ban_sen_da";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Kết nối thất bại: " . $e->getMessage());
        }
    }
}


global $conn;
$conn = connectdb();
