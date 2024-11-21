<?php
session_start();
include '../model/config.php';

$error_user = ""; // Biến lưu lỗi Username
$error_password_old = ""; // Biến lưu lỗi Password Old
$error_password_new = ""; // Biến lưu lỗi Re-enter Password
$success_message = ""; // Thông báo thành công

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $password_old = $_POST['pass_old'];
    $password_new = $_POST['pass_new'];
    $re_enter_password_new = $_POST['re_pass_new'];

    // Kiểm tra username
    if (empty($user)) {
        $error_user = "Vui lòng nhập tên người dùng.";
    }

    // Kiểm tra mật khẩu cũ
    if (empty($password_old)) {
        $error_password_old = "Vui lòng nhập mật khẩu cũ.";
    }

    // Kiểm tra mật khẩu mới và nhập lại mật khẩu
    if ($password_new !== $re_enter_password_new) {
        $error_password_new = "Mật khẩu mới không trùng khớp.";
    }

    if (empty($error_user) && empty($error_password_old) && empty($error_password_new)) {
        // Kết nối cơ sở dữ liệu
        $conn = connectdb();
        if ($conn) {
            // Kiểm tra mật khẩu cũ
            $stmt = $conn->prepare("SELECT * FROM user WHERE user = :user AND password = :password");
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':password', $password_old);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Cập nhật mật khẩu mới
                $stmt = $conn->prepare("UPDATE user SET password = :password WHERE user = :user");
                $stmt->bindParam(':user', $user);
                $stmt->bindParam(':password', $password_new);
                if ($stmt->execute()) {
                    echo "<div class='container mt-4'>
                        <div class='alert alert-success text-center' role='alert'>
                            Đổi mật khẩu thành công!
                        </div>
                    </div>";
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000); // 3000 milliseconds = 3 seconds
                    </script>";
                } else {
                    $error_password_old = "Đã xảy ra lỗi trong quá trình đổi mật khẩu.";
                }
            } else {
                $error_password_old = "Tên người dùng hoặc mật khẩu cũ không chính xác.";
            }
        } else {
            $error_user = "Không thể kết nối đến database.";
        }
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
    <link rel="stylesheet" href="../css/my.css">

    <title>Sign Up</title>
</head>

<body>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="form-container">
                    <form action="" method="POST">
                        <div class="form-group">
                            <h4 class="text-center font-weight-bold">Forgot Password</h4>
                            <label for="Inputuser1">Username</label>
                            <input type="text" class="form-control" id="Inputuser1" placeholder="Enter username" name="user" value="<?= isset($user) ? htmlspecialchars($user) : '' ?>">
                            <!-- Hiển thị lỗi Username -->
                            <?php if (!empty($error_user)) : ?>
                                <small class="text-danger"><?= $error_user ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword1">Password Old</label>
                            <input type="password" class="form-control" id="InputPassword1" placeholder="Password old" name="pass_old">
                            <!-- Hiển thị lỗi Password Old -->
                            <?php if (!empty($error_password_old)) : ?>
                                <small class="text-danger"><?= $error_password_old ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword2">Password New</label>
                            <input type="password" class="form-control" id="InputPassword2" placeholder="Password new" name="pass_new">
                        </div>
                        <div class="form-group">
                            <label for="InputPassword3">Re-enter Password</label>
                            <input type="password" class="form-control" id="InputPassword3" placeholder="Re-enter password" name="re_pass_new">
                            <!-- Hiển thị lỗi Re-enter Password -->
                            <?php if (!empty($error_password_new)) : ?>
                                <small class="text-danger"><?= $error_password_new ?></small>
                            <?php endif; ?>
                        </div>
                        <input type="submit" value="Save" class="btn btn-primary btn-block" name="save">
                        <div class="form-footer">
                            <p>Don't have an account? <a href="register.php">Sign Up</a></p>
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