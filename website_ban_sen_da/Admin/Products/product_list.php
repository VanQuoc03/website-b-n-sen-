<div class="body--wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h2>Danh sách sản phẩm</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Mô tả</th>
                            <th>Ảnh sản phẩm</th>
                            <th>Giá</th>
                            <th>Loại sản phẩm</th>
                            <th>Sửa</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $query->fetch()) {
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['name_product']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td>
                                    <img style="width: 100px" src="/img/<?php echo $row['image_product']; ?>" alt="">
                                </td>
                                <td><?php echo $row['price']; ?></td>
                                <td><?php echo $row['name_cate']; ?></td>
                                <td>
                                    <a href="index.php?page_layout=edit_product&id=<?php echo $row['product_id']; ?>">Sửa</a>
                                </td>
                                <td>
                                    <a onclick="return Del('<?php echo $row['name_product']; ?>')" href="index.php?page_layout=delete_product&id=<?php echo $row['product_id']; ?>">Xóa</a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="index.php?page_layout=add_product" class="btn btn-primary">Thêm sản phẩm</a>
            </div>
        </div>
    </div>
    <script>
        function Del(name) {
            return confirm("Bạn có chắc chắn muốn xóa sản phẩm: " + name + "?");
        }
    </script>
</div>