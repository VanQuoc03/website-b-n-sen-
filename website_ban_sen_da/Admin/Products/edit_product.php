<?php
try {
    include_once '/wampp/www/website_ban_sen_da/model/config.php';
    $connect = connectdb();
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối đến cơ sở dữ liệu không thành công: " . $e->getMessage());
}

$id = $_GET['id'];

// Lấy danh sách loại sản phẩm
$sql_category = "SELECT * FROM categories";
$query_category = $connect->query($sql_category);

// Lấy thông tin sản phẩm hiện tại
$sql_up = "SELECT * FROM products WHERE product_id = :id";
$stmt_up = $connect->prepare($sql_up);
$stmt_up->execute(['id' => $id]);
$row_up = $stmt_up->fetch(PDO::FETCH_ASSOC);
$current_product_category_id = $row_up['category_id'];

if (isset($_POST['sbm'])) {
    $name_product = $_POST['name_product'];
    $description = $_POST['description'];

    if ($_FILES['image_product']['name'] == '') {
        $image_product = $row_up['image_product'];
    } else {
        $image_product = $_FILES['image_product']['name'];
        $image_tmp = $_FILES['image_product']['tmp_name'];
        $base_path = (strpos(realpath(__DIR__), 'Products') !== false) ? realpath(__DIR__ . '/../../img/product') : realpath(__DIR__ . '/../img/product');

        if (!file_exists($base_path)) {
            mkdir($base_path, 0777, true);
        }

        move_uploaded_file($image_tmp, $base_path . '/' . $image_product);
    }

    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Cập nhật sản phẩm
    $sql = "UPDATE products SET name_product = :name_product, description = :description, 
        image_product = :image_product, price = :price, category_id = :category_id WHERE product_id = :id";
    $stmt = $connect->prepare($sql);
    $stmt->execute([
        'name_product' => $name_product,
        'description' => $description,
        'image_product' => $image_product,
        'price' => $price,
        'category_id' => $category_id,
        'id' => $id
    ]);

    header('location: index.php?page_layout=product');
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Cập nhật sản phẩm</h2>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Tên sản phẩm</label>
                    <input type="text" name="name_product" class="form-control" required value="<?php echo $row_up['name_product'] ?>">
                </div>

                <div class="form-group">
                    <label for="">Mô tả sản phẩm</label>
                    <input type="text" name="description" class="form-control" required value="<?php echo $row_up['description'] ?>">
                </div>

                <div class="form-group">
                    <label for="">Ảnh sản phẩm</label><br>
                    <input type="file" name="image_product">
                </div>

                <div class="form-group">
                    <label for="">Giá sản phẩm</label>
                    <input type="number" name="price" class="form-control" required value="<?php echo $row_up['price'] ?>">
                </div>

                <div class="form-group">
                    <label for="">Loại sản phẩm</label>
                    <select name="category_id" class="form-control">
                        <?php
                        while ($row_category = $query_category->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value="<?php echo $row_category['category_id']; ?>" <?php echo ($row_category['category_id'] == $current_product_category_id) ? 'selected' : ''; ?>>
                                <?php echo $row_category['name_cate']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button name="sbm" class="btn btn-success" type="submit">Sửa</button>
            </form>
        </div>
    </div>
</div>