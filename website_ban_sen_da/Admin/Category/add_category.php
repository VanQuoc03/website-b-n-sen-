<?php
$connect = connectdb();

if ($connect) {
    $sql_category = "SELECT * FROM categories";
    $query_category = $connect->query($sql_category);
    $errors = ['name' => ''];

    if (isset($_POST['submit'])) {
        $name_cate = htmlspecialchars($_POST['name_cate']);
        if (empty($name_cate)) {
            $errors['name'] = "Name category is required";
        } else {
            $sql_check = "SELECT * FROM categories WHERE name_cate = :name_cate";
            $stmt_check = $connect->prepare($sql_check);
            $stmt_check->bindParam(':name_cate', $name_cate);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $errors['name'] = "Danh mục đã tồn tại";
            }
        }
        if (array_filter($errors) == false) {
            $sql = "INSERT INTO categories (name_cate) VALUES (:name_cate)";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(':name_cate', $name_cate);
            $stmt->execute();
            header('location: Category/category.php');
            exit;
        }
    }
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Thêm danh mục</h2>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Tên danh mục</label>
                    <input type="text" name="name_cate" class="form-control" require>
                    <small class="text-danger"><?php echo $errors['name']; ?></small>
                </div>
                <button name="submit" class="btn btn-success" type="submit">Thêm</button>
            </form>
        </div>
    </div>
</div>