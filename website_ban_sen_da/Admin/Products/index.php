<?php
require_once '../../model/config.php';
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
                require_once '../Products/category_product.php';
                break;

            case 'add_product':
                require_once '../Products/add_product.php';
                break;

            case 'edit_product':
                require_once '../Products/edit_product.php';

                break;

            case 'delete_product':
                require_once '../Products/delete_product.php';
                break;


            case 'category':
                require_once '../Category/category.php';
                break;

            case 'add_category':
                require_once '../Products/add_category.php';
                break;

            case 'edit_category':
                require_once '../Products/edit_category.php';

                break;

            case 'delete_category':
                require_once '../Products/delete_category.php';
                break;
            case 'logout':
                if (isset($_SESSION['role'])) {
                    unset($_SESSION['role']);
                }
                header('location: login.php');
                break;

            default:
                require_once '../Admin/Products/category_product.php';
                break;
        }
    } else {
        require_once '../Admin/Products/category_product.php';
    }
    ?>

</body>

</html>