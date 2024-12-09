<?php
require '../../vendor/autoload.php';
include '../../config/config.php';
use MongoDB\Client;

// Kết nối với MongoDB
try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->database_bookstore; // Tên database MongoDB
    $collection = $db->combo_products; // Tên collection MongoDB
} catch (Exception $e) {
    die("Kết nối MongoDB thất bại: " . $e->getMessage());
}

session_start();

// Function to truncate text
function truncate_text($text)
{
    if (strlen($text) > 70) {
        $text = substr($text, 0, 52) . '...';
        $text = trim($text);
    }
    return $text;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý combo sách</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .content {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        max-width: 700px;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        z-index: 10000;
        display: none;
        white-space: pre-wrap;
        border-radius: 10px;
        line-height: 1.5;
        font-size: 2rem;
    }

    .blackboard {
        position: relative;
        width: 45%;
        margin: 5% auto;
        border: tan solid 12px;
        border-top: #bda27e solid 12px;
        border-left: #b19876 solid 12px;
        border-bottom: #c9ad86 solid 12px;
        box-shadow: 0px 0px 6px 5px rgba(58, 18, 13, 0), 0px 0px 0px 2px #c2a782, 0px 0px 0px 4px #a58e6f, 3px 4px 8px 5px rgba(0, 0, 0, 0.5);
        background-image: radial-gradient(circle at left 30%, rgba(34, 34, 34, 0.3), rgba(34, 34, 34, 0.3) 80px, rgba(34, 34, 34, 0.5) 100px, rgba(51, 51, 51, 0.5) 160px, rgba(51, 51, 51, 0.5)), linear-gradient(215deg, transparent, transparent 100px, #222 260px, #222 320px, transparent), radial-gradient(circle at right, #111, rgba(51, 51, 51, 1));
        background-color: #333;
    }

    .blackboard:before {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(175deg, transparent, transparent 40px, rgba(120, 120, 120, 0.1) 100px, rgba(120, 120, 120, 0.1) 110px, transparent 220px, transparent), linear-gradient(200deg, transparent 80%, rgba(50, 50, 50, 0.3)), radial-gradient(ellipse at right bottom, transparent, transparent 200px, rgba(80, 80, 80, 0.1) 260px, rgba(80, 80, 80, 0.1) 320px, transparent 400px, transparent);
        border: #2c2c2c solid 2px;
        content: "Tìm Kiếm Sản Phẩm";
        font-family: 'Permanent Marker', cursive;
        font-size: 2.2em;
        color: rgba(238, 238, 238, 0.7);
        text-align: center;
        padding-top: 20px;
    }

    .form {
        padding: 70px 20px 20px;
    }

    p {
        position: relative;
        margin-bottom: 1em;
    }

    label {
        vertical-align: middle;
        font-size: 1.6em;
        color: rgba(238, 238, 238, 0.7);
    }

    p:nth-of-type(5)>label {
        vertical-align: top;
    }

    input,
    textarea {
        vertical-align: middle;
        padding-left: 10px;
        background: none;
        border: none;

        font-size: 1.6em;
        color: rgba(238, 238, 238, 0.8);
        line-height: .6em;
        outline: none;
    }

    select {
        vertical-align: middle;
        padding-left: 10px;
        background: transparent;
        border: none;
        width: 40%;

        font-size: 1.6em;
        color: rgba(238, 238, 238, 0.8);
        line-height: .6em;
        outline: none;
    }

    option {
        vertical-align: middle;
        padding-left: 10px;
        background: rgb(63, 62, 70);
        border: none;

        font-size: 1.0em;
        color: rgba(238, 238, 238, 0.8);
        line-height: .6em;
        outline: none;
    }

    textarea {
        margin-top: 1%;
        height: 120px;
        font-size: 1.4em;
        line-height: 1em;
        resize: none;
    }

    .searchandclear {
        cursor: pointer;
        color: rgba(238, 238, 238, 0.7);
        line-height: 1em;
        padding: 0;
    }

    input[type="submit"]:focus {
        background: rgba(238, 238, 238, 0.2);
        color: rgba(238, 238, 238, 0.2);
    }

    ::-moz-selection {
        background: rgba(238, 238, 238, 0.2);
        color: rgba(238, 238, 238, 0.2);
        text-shadow: none;
    }
    </style>
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
        <section class="add-products-combo">
            <?php
            if (isset($_GET['add-product-book'])) {
            ?>
            <form action="../Controllers/adminProductController.php" method="post">
                <h3>Thêm combo</h3>
                <input type="text" name="combo_name" class="box" placeholder="Nhập tên Combo" required>
                <input type="number" min="0" name="price" class="box" placeholder="Nhập giá" required>
                <input type="text" name="image_combo" class="box" placeholder="Nhập ảnh của combo" required>
                <textarea name="description" class="description" placeholder="Nhập mô tả về combo" cols="30" rows="5"></textarea>
                <textarea name="description_detail" class="description" placeholder="Nhập mô tả chi tiết cho combo" cols="30" rows="5"></textarea>
                <input type="text" name="name_1" class="box" placeholder="Nhập tên sách 1" required>
                <input type="text" name="image_1" class="box" placeholder="Nhập url cho sách 1" required>
                <textarea name="description_1" class="description" placeholder="Nhập mô tả về sách 1" cols="30" rows="5"></textarea>
                <input type="text" name="name_2" class="box" placeholder="Nhập tên sách 2" required>
                <input type="text" name="image_2" class="box" placeholder="Nhập url cho sách 2" required>
                <textarea name="description_2" class="description" placeholder="Nhập mô tả về sách 2" cols="30" rows="5"></textarea>
                <input type="text" name="name_3" class="box" placeholder="Nhập tên sách 3">
                <input type="text" name="image_3" class="box" placeholder="Nhập url cho sách 3">
                <textarea name="description_3" class="description" placeholder="Nhập mô tả về sách 3" cols="30" rows="5"></textarea>
                <div style="display:flex;justify-content:center;gap:0.5rem;">
                    <input type="submit" value="Thêm Combo" name="add_product_combo" class="btn">
                    <a href="admin_combo_product.php" class="delete-btn">Đóng</a>
                </div>
            </form>
        <?php
            } else {
                echo '<script>document.querySelector(".add-products-combo").style.display = "none";</script>';
            }
        ?>
        </section>

    <!--  SEARCH PRODUCTS BEGINS -->
        <form action="" method="post">
            <div>
                <div class="blackboard">
                    <div class="form">
                        <p>
                            <label for="combo_name"><b>Tên&emsp;</b></label>
                            <input type="text" placeholder="Nhập tên" name="combo_name">
                        </p>
                        <br>
                        <p>
                            <label for="price">Khoảng giá:</label>&emsp;
                            <input type="number" placeholder="Trên" name="price_min" style="width:20%">&emsp;&emsp;&emsp;
                            <input type="number" placeholder="Dưới" name="price_max" style="width:20%">
                        </p>
                        <br><br>
                        <p class="wipeout">
                            <span style="float: left; margin-left: 10%">
                                <input type="submit" name="search" value="Tìm Kiếm" class="searchandclear" />
                            </span>
                            <span style="float: right; margin-right: 10%">
                                <input type="submit" value="Xóa" class="searchandclear" />
                            </span><br>
                        </p>
                    </div>
                </div>
            </div>
        </form>
        <!--  SEARCH PRODUCTS ENDS -->

     <!--  SHOW PRODUCTS FROM SEARCH BEGINS -->
        <br>
    
        <section class="show-products">
            <div class="list-add-products">
                <div class="list-products">
                    <h1 class="title1">Danh Sách Combo</h1>
                </div>
                <div class="add-products-button">
                    <a href="admin_combo_product.php?add-product-book" class="option-btn">Thêm Combo</a>
                </div>
            </div>
            <div class="box-container" style="margin-top:40px;" id="product">
                <?php
                // Thiết lập phân trang
                $limit = 9; // Số lượng combo mỗi trang
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $skip = ($current_page - 1) * $limit;
        
                // Xử lý tìm kiếm
                $combo_query = [];
                $search_params = [];
                if (isset($_POST['search'])) {
                    $combo_name = htmlspecialchars($_POST['combo_name']);
                    $price_min = htmlspecialchars($_POST['price_min']);
                    $price_max = htmlspecialchars($_POST['price_max']);
                    
                    // Kiểm tra giá trị min/max có phải là số không và chuyển đổi
                    $price_min = is_numeric($price_min) ? (int)$price_min : null;
                    $price_max = is_numeric($price_max) ? (int)$price_max : null;

                    // Điều kiện tìm kiếm kết hợp theo tên và giá
                    if (!empty($combo_name) && !is_null($price_min) && !is_null($price_max)) {
                        $combo_query = [
                            'combo_name' => ['$regex' => $combo_name, '$options' => 'i'],
                            'price' => ['$lte' => $price_max, '$gte' => $price_min] // Điều kiện giá nằm trong khoảng
                        ];
                        $search_params = ['combo_name' => $combo_name, 'price_min' => $price_min, 'price_max' => $price_max];
                    } elseif (!empty($combo_name)) {
                        // Tìm kiếm chỉ theo tên combo
                        $combo_query = ['combo_name' => ['$regex' => $combo_name, '$options' => 'i']];
                        $search_params = ['combo_name' => $combo_name];
                    } elseif (!is_null($price_min) && !is_null($price_max)) {
                        // Tìm kiếm chỉ theo khoảng giá
                        $combo_query = ['price' => ['$gte' => $price_min, '$lte' => $price_max]];
                        $search_params = ['price_min' => $price_min, 'price_max' => $price_max];
                    } elseif (!is_null($price_min)) {
                        // Tìm kiếm theo giá tối thiểu (nếu chỉ có price_min)
                        $combo_query = ['price' => ['$gte' => $price_min]];
                        $search_params = ['price_min' => $price_min];
                    } elseif (!is_null($price_max)) {
                        // Tìm kiếm theo giá tối đa (nếu chỉ có price_max)
                        $combo_query = ['price' => ['$lte' => $price_max]];
                        $search_params = ['price_max' => $price_max];
                    }
                }
 
                // Lấy số lượng combo theo điều kiện tìm kiếm
                $total_combos = $collection->countDocuments($combo_query);
                $total_pages = ceil($total_combos / $limit);
        
                // Lấy các combo sách với điều kiện tìm kiếm
                $cursor = $collection->find($combo_query, [
                    'skip' => $skip,
                    'limit' => $limit
                ]);
                $combos = iterator_to_array($cursor);
        
                if (count($combos) == 0) {
                    echo "<p class='empty'>Không tìm thấy combo sách!!!</p>";
                } else {
                    foreach ($combos as $combo) {
                        ?>
                        <div class="box">
                            <img src="<?php echo $combo['image_combo']; ?>" alt="">
                            <div class="name" style="height: 15vh;">
                                <?php echo $combo['combo_name']; ?>
                            </div>
                            <div class="price">
                                <?php echo $combo['price']; ?> ₫
                            </div>
                            <form action="../Controllers/adminProductController.php" method="post">
                                <a href="./admin_detail_combo.php?id=<?php echo $combo['_id']; ?>" class="detail_book">Xem thêm <i class="fas fa-angle-right"></i></a> <br>
                                <div style="display:flex;justify-content:center;gap:0.5rem;">
                                    <a href="admin_combo_product.php?update=<?php echo $combo['_id']; ?>" class="option-btn">Cập nhật</a>
                                    <input type="submit" value="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="delete-btn" name="delete_combo_product">
                                    <input type="hidden" value="<?php echo $combo['_id']; ?>" name="combo_id">
                                </div>
                            </form>
                        </div>
                 <?php
             }
         }
         ?>
     </div>
     </section>
     <!-- Pagination -->
     <div class="pagination mt-4">
    <ul class="pagination justify-content-center d-flex flex-wrap mx-auto">
        <?php
        // Tổng số combo
        $total_count = $collection->countDocuments($combo_query);
        $total_pages = ceil($total_count / $limit);

        // Nút "Prev" chỉ hiển thị nếu không phải trang đầu
        if ($current_page > 1) {
            echo '<li class="page-item"><a href="admin_combo_product.php?page=' . ($current_page - 1) . '" class="page-link">Prev</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Prev</span></li>';
        }

        // Hiển thị các trang
        for ($page = 1; $page <= $total_pages; $page++) {
            echo '<li class="page-item' . ($page == $current_page ? ' active' : '') . '"><a href="admin_combo_product.php?page=' . $page . '" class="page-link">' . $page . '</a></li>';
        }

        // Nút "Next" chỉ hiển thị nếu không phải trang cuối
        if ($current_page < $total_pages) {
            echo '<li class="page-item"><a href="admin_combo_product.php?page=' . ($current_page + 1) . '" class="page-link">Next</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        ?>
    </ul>
</div>

<section class="edit-product-form">
    <?php
       if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        
        // Truy vấn MongoDB
        $combo = $collection->findOne(['combo_id' => $update_id]);

        if ($combo) {
    ?>
    <form action="../Controllers/adminProductController.php" method="post" enctype="multipart/form-data">
        <h1 class="title">Cập nhật combo sách</h1>
        <input type="hidden" name="update_combo_id" value="<?php echo $combo['combo_id']; ?>">
        <input type="text" name="update_combo_name" value="<?php echo $combo['combo_name']; ?>" class="box" required placeholder="Nhập tên combo sách cần cập nhật">
        <input type="number" name="update_price" value="<?php echo $combo['price']; ?>" min="0" class="box" required placeholder="Nhập giá combo sách cần cập nhật">
        <input type="text" class="box" name="update_image_combo" value="<?php echo $combo['image_combo']; ?>" placeholder="Nhập url ảnh sách cần cập nhật">
        <textarea name="update_description" class="box" cols="30" rows="3" style="width: 100%"><?php echo $combo['description']; ?></textarea>
        <textarea name="update_description_detail" class="box" cols="30" rows="5" style="width: 100%"><?php echo $combo['description_detail']; ?></textarea>
        <input type="text" name="update_name_1" value="<?php echo $combo['name_1']; ?>" class="box" required placeholder="Nhập tên sách 1 cần cập nhật">
        <input type="text" class="box" name="update_image_1" value="<?php echo $combo['image_1']; ?>" placeholder="Nhập url ảnh sách 1 cần cập nhật">
        <textarea name="update_description_1" class="box" cols="30" rows="3" style="width: 100%"><?php echo $combo['description_1']; ?></textarea>
        <input type="text" name="update_name_2" value="<?php echo $combo['name_2']; ?>" class="box" placeholder="Nhập tên sách 1 cần cập nhật">
        <input type="text" class="box" name="update_image_2" value="<?php echo $combo['image_2']; ?>" placeholder="Nhập url ảnh sách 1 cần cập nhật">
        <textarea name="update_description_2" class="box" cols="30" rows="3" style="width: 100%"><?php echo $combo['description_2']; ?></textarea>
        <input type="text" name="update_name_3" value="<?php echo $combo['name_3']; ?>" class="box" placeholder="Nhập tên sách 1 cần cập nhật">
        <input type="text" class="box" name="update_image_3" value="<?php echo $combo['image_3']; ?>" placeholder="Nhập url ảnh sách 1 cần cập nhật">
        <textarea name="update_description_3" class="box" cols="30" rows="3" style="width: 100%"><?php echo $combo['description_3']; ?></textarea>
        <input type="submit" value="Lưu" name="update_product_combo" class="btn">
        <input type="submit" value="Reset" name="reset_combo" id="close-update" class="delete-btn">
    </form>
    <?php
        }
    } else {
        echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
    }
    ?>
</section>

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../View/alert.php'; ?>
</body>
</html>