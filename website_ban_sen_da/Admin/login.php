<?php
session_start();
ob_start();
require_once "../model/config.php";
include "../model/user.php";
if ((isset($_POST['smb-login'])) && ($_POST['smb-login'])) {
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $role = checkuser($user, $pass);
    $_SESSION['role'] = $role;
    if ($role == 1)
        header('location: index.php');
    else {
        $txt_error = "Username hoặc password không tồn tại!";
    }
    // header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
</head>

<body>
    <div class="main">
        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="user" id="">
            <input type="text" name="password">
            <input type="submit" value="ĐĂNG NHẬP" name="smb-login">
            <?php if (isset($txt_error) && ($txt_error != "")) {
                echo "<font color=red>" . $txt_error . "</font>";
            } ?>
        </form>
    </div>
</body>

</html>