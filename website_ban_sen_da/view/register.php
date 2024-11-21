<?php
session_start();
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $role = 0; // Đặt role mặc định cho người dùng là 0 (người dùng bình thường)

    // Kết nối đến database
    $conn = connectdb();

    if ($conn) {
        // Kiểm tra xem username đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM user WHERE user = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo "<div class='alert alert-danger'>Tên đăng nhập đã tồn tại!</div>";
        } else {
            // Chèn thông tin người dùng vào bảng `user`
            $stmt = $conn->prepare("INSERT INTO user (fullname, user, password, email, phone_number, role) VALUES (:fullname, :user, :password, :email, :phone_number, :role)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                echo "<div class='container mt-4'>
                        <div class='alert alert-success text-center' role='alert'>
                            Đăng ký thành công!
                        </div>
                    </div>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000); // 3000 milliseconds = 3 seconds
                    </script>";
            } else {
                echo "<div class='alert alert-danger'>Đã xảy ra lỗi trong quá trình đăng ký.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Không thể kết nối đến database.</div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6 col-md-4">
                <form action="register.php" method="POST" class="form-container mt-5">
                    <h4 class="text-center font-weight-bold">Sign Up</h4>
                    <div class="form-group">
                        <label for="InputFullName">Full Name</label>
                        <input type="text" class="form-control" id="InputFullName" name="fullname" placeholder="Enter full name" required>
                    </div>
                    <div class="form-group">
                        <label for="InputUsername">Username</label>
                        <input type="text" class="form-control" id="InputUsername" name="user" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Password</label>
                        <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="form-group">
                        <label for="InputEmail">Email</label>
                        <input type="email" class="form-control" id="InputEmail" name="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="InputPhone">Phone Number</label>
                        <input type="tel" class="form-control" id="InputPhone" name="phone_number" placeholder="Enter phone number">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    <div class="form-footer text-center mt-3">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>