<?php
session_start();
ob_start();
include '../model/config.php';
include '../model/user.php';

$error_message = ''; // Khởi tạo biến thông báo lỗi

if (isset($_POST['login']) && ($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $kq = getuserinfo($user, $pass);

    if (count($kq) > 0) { // Kiểm tra xem có kết quả không
        $role = $kq[0]['role'];
        if ($role == 1) {
            $_SESSION['role'] = $role;
            header('Location: ../Admin/index.php');
            exit; // Thêm exit để dừng mã tại đây
        } else {
            $_SESSION['role'] = $role;
            $_SESSION['iduser'] = $kq[0]['id'];
            $_SESSION['username'] = $kq[0]['user'];
            header('Location: ../index.php');
            exit; // Thêm exit để dừng mã tại đây
        }
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng."; // Cập nhật thông báo lỗi
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login_style.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="form-container">
                    <form action="" method="POST">
                        <div class="form-group">
                            <h4 class="text-center font-weight-bold"> Login </h4>
                            <?php if ($error_message != ''): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            <label for="Inputuser1">Username</label>
                            <input type="text" class="form-control" id="Inputuser1" aria-describedby="usernameHelp" placeholder="Enter username" name="user">
                        </div>
                        <div class="form-group">
                            <label for="InputPassword1">Password</label>
                            <input type="password" class="form-control" id="InputPassword1" placeholder="Password" name="pass">
                        </div>
                        <input type="submit" value="Login" class="btn btn-primary btn-block" name="login">
                        <div class="form-footer">
                            <p> Don't have an account? <a href="register.php">Sign Up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>