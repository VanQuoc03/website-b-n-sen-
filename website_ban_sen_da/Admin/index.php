<?php
session_start();
ob_start();
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1)) {
    if (strpos(__DIR__, 'Admin/Products') !== false) {
        $config_path = '../model/config.php';
    } elseif (strpos(__DIR__, 'Admin/Category') !== false) {
        $config_path = '../../model/config.php';
    } else {
        // Đường dẫn mặc định nếu không ở trong thư mục Products hoặc Category
        $config_path = realpath(__DIR__ . '/../model/config.php');
    }

    // Kết nối với file config
    require_once $config_path;

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
            integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
            crossorigin="anonymous">

        <title>Quốc sen đá</title>
    </head>

    <body>

    <?php
    if (isset($_GET['page_layout'])) {
        switch ($_GET['page_layout']) {
            case 'product':
                require_once '../Admin/Products/category_product.php';
                break;

            case 'add_product':
                require_once '../Admin/Products/add_product.php';
                break;

            case 'edit_product':
                require_once '../Admin/Products/edit_product.php';
                break;

            case 'delete_product':
                require_once '../Admin/Products/delete_product.php';

                break;
            default:
                require_once '../Admin/Products/category_product.php';
                break;

            case 'category':
                require_once '../Admin/Category/category.php';
                break;

            case 'add_category':
                require_once '../Admin/Category/add_category.php';
                break;

            case 'edit_category':
                require_once '../Admin/Category/edit_category.php';
                break;

            case 'delete_category':
                require_once '../Admin/Category/delete_category.php';
                break;
            case 'logout':
                if (isset($_SESSION['role'])) {
                    unset($_SESSION['role']);
                }
                header('location: login.php');
                break;
        }
    } else {
        require_once '../Admin/Products/category_product.php';
    }
} else {
    header('location: login.php');
}
    ?>

    </body>

    </html>