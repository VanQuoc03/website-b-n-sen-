<?php
$connect = connectdb();
$sql_category = "SELECT * FROM categories";
$query_category = $connect->query($sql_category);
$query_category->setFetchMode(PDO::FETCH_ASSOC);

$errors = ['name' => '', 'description' => '', 'image_product' => '', 'price' => '', 'category_id' => '',];

if (isset($_POST['submit'])) {
    $name_product = htmlspecialchars($_POST['name_product']);
    $description = htmlspecialchars($_POST['description'] ?? '');
    $image_product = $_FILES['image_product']['name'] ?? '';
    $image_tmp = $_FILES['image_product']['tmp_name'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    if (empty($name_product)) {
        $errors['name'] = "Name is required";
    }
    if (empty($description)) {
        $errors['description'] = "Description is required";
    }
    if (empty($image_product)) {
        $errors['image_product'] = "Image is required";
    }
    if (empty($price)) {
        $errors['price'] = "Price is required";
    }
    if (empty($category_id)) {
        $errors['category_id'] = "Category is required";
    }
    if (array_filter($errors) == false) {
        $sql = "INSERT INTO products (name_product, description, image_product, price, category_id)
            VALUES(:name_product, :description, :image_product, :price, :category_id)";
        $stmt = $connect->prepare($sql);
        $stmt->execute([
            ':name_product' => $name_product,
            ':description' => $description,
            ':image_product' => $image_product,
            ':price' => $price,
            ':category_id' => $category_id,
        ]);
        $base_path = (strpos(realpath(__DIR__), 'Products') !== false) ? realpath(__DIR__ . '/../../img/product') : realpath(__DIR__ . '/../img/product');

        if (!file_exists($base_path)) {
            mkdir($base_path, 0777, true); // Tạo thư mục nếu không tồn tại
        }

        move_uploaded_file($image_tmp, $base_path . '/' . $image_product);
        header('location: index.php?page_layout=product');
    }
}

?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Thêm sản phẩm</h2>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Tên sản phẩm</label>
                    <input type="text" name="name_product" class="form-control" require>
                    <small class="text-danger"><?php echo $errors['name']; ?></small>
                </div>

                <div class="form-group">
                    <label for="">Mô tả sản phẩm</label>
                    <input type="text" name="description" class="form-control" require>
                    <small class="text-danger"><?php echo $errors['description']; ?></small>

                </div>

                <div class="form-group">
                    <label for="">Ảnh sản phẩm</label><br>
                    <input type="file" name="image_product"><br>
                    <small class="text-danger"><?php echo $errors['image_product']; ?></small>

                </div>

                <div class="form-group">
                    <label for="">Giá sản phẩm</label>
                    <input type="number" name="price" class="form-control" require>
                    <small class="text-danger"><?php echo $errors['price']; ?></small>

                </div>

                <div class="form-group">
                    <label for="">Loại sản phẩm</label>
                    <select type="number" name="category_id" class="form-control">
                        <?php
                        while ($row_category = $query_category->fetch()) {
                        ?>
                            <option value="<?php echo $row_category['category_id'] ?>"><?php echo $row_category['name_cate'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <button name="submit" class="btn btn-success" type="submit">Thêm</button>
            </form>
        </div>
    </div>
</div>