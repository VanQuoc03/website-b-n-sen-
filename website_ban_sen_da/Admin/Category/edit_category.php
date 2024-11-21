<?php
// Thay đổi đường dẫn phù hợp với cấu trúc thư mục của bạn

$connect = connectdb(); // Khởi tạo kết nối với cơ sở dữ liệu

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "Không nhận được ID.";
    exit;
}

if ($id) {
    // Truy vấn dữ liệu từ bảng categories với điều kiện id
    $sql_category = "SELECT * FROM categories WHERE category_id = :id";
    $stmt_category = $connect->prepare($sql_category);
    $stmt_category->bindParam(':id', $id);
    $stmt_category->execute();
    $row_up = $stmt_category->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Không tìm thấy ID của danh mục!";
    exit;
}

$errors = ['name' => ''];

if (isset($_POST['sbm'])) {
    $name_cate = $_POST['name_cate'];

    // Kiểm tra xem tên danh mục đã tồn tại chưa, ngoại trừ danh mục hiện tại
    $sql_check = "SELECT * FROM categories WHERE name_cate = :name_cate AND category_id != :id";
    $stmt_check = $connect->prepare($sql_check);
    $stmt_check->bindParam(':name_cate', $name_cate);
    $stmt_check->bindParam(':id', $id);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        $errors['name'] = "Danh mục đã tồn tại";
    } else {
        $sql = "UPDATE categories SET name_cate = :name_cate WHERE category_id = :id";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':name_cate', $name_cate);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('location: Category/category.php');
        exit;
    }
}
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Cập nhật danh mục</h2>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Tên danh mục</label>
                    <input type="text" name="name_cate" class="form-control" required value="<?php echo isset($row_up['name_cate']) ? $row_up['name_cate'] : ''; ?>">
                    <small class="text-danger"><?php echo $errors['name']; ?></small>
                </div>
                <button name="sbm" class="btn btn-success" type="submit">Sửa</button>
            </form>
        </div>
    </div>
</div>