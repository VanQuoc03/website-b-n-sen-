
<?php

session_start();
ob_start();
require_once "model/config.php";
require_once "model/user.php";
require_once "model/category.php";
require_once "model/product.php";
require_once "model/functions.php";
$category_list = getall_category();
require_once "view/header.php";
include "view/home.php";

if (!is_array($category_list)) {
    $category_list = []; // Gán thành mảng rỗng nếu không phải là mảng
}

$page_layout = isset($_GET['page_layout']) ? $_GET['page_layout'] : null;
switch ($page_layout) {
    case 'logout':
        unset($_SESSION['role']);
        unset($_SESSION['iduser']);
        unset($_SESSION['username']);
        header('Location: index.php');
        break;
    case 'userinfo':
        header('Location: view/userinfo.php');
        break;

    case 'login':
        if (isset($_POST['login']) && ($_POST['login'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $kq = getuserinfo($user, $pass);

            if (count($kq) > 0) { // Kiểm tra xem có kết quả không
                $role = $kq[0]['role'];
                if ($role == 1) {
                    $_SESSION['role'] = $role;
                    header('Location: ../Admin/index.php');
                } else {
                    $_SESSION['role'] = $role;
                    $_SESSION['iduser'] = $kq[0]['id'];
                    $_SESSION['username'] = $kq[0]['user'];
                    header('Location: index.php');
                    break;
                }
            } else {
                echo "Tên đăng nhập hoặc mật khẩu không đúng.";
            }
        }
    case 'product':
        $search = '';
        if (isset($_GET['id']) && ($_GET['id'] > 0)) {
            $category_id = $_GET['id'];
        }
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
        }
        $category_product = getall_product($category_id, $search, "");
        header("Location: view/product.php?id=$category_id&search=$search");
        break;
    case 'product_detail':

        if (isset($_GET['idpro'])) {
            $idpro = $_GET['idpro'];
            $product_detail = get_pro_by_id($idpro);
            $id_cate = $product_detail['category_id'];
            $related_product = get_product_category_lienquan($id_cate, $idpro, 4);
            header("Location: view/product_detail.php?idpro=$idpro");
            exit();
        } else {
            header('Location: index.php');
        }
        break;
}
require_once "view/footer.php";
?>
