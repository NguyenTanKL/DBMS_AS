<?php
require '../../vendor/autoload.php';
include '../../config/config.php';
use MongoDB\Client;

// Kết nối với MongoDB
try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->database_bookstore; // Tên database MongoDB
    $authors_collection = $db->authors; // Tên collection MongoDB
} catch (Exception $e) {
    die("Kết nối MongoDB thất bại: " . $e->getMessage());
}

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tác giả</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .blackboard {
            position: relative;
            width: 44%;
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
            content: "Tìm Kiếm Tác Giả";
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
            font-family: 'Permanent Marker', cursive;
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
            font-family: 'Permanent Marker', cursive;
            font-size: 1.6em;
            color: rgba(238, 238, 238, 0.8);
            line-height: .6em;
            outline: none;
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

    <form action="" method="post">
        <div>
            <div class="blackboard">
                <div class="form">
                    <p>
                        <label for="name"><b>Tên tác giả&emsp;</b></label>
                        <input type="text" placeholder="Nhập tên" name="name" style="margin-left:6px;">
                    </p>
                    <p>
                        <label for="slogan"><b>Description&emsp;</b></label>
                        <input type="text" placeholder="Nhập mô tả" name="slogan">
                    </p>
                    <p class="wipeout">
                        <span style="float: left; margin-left: 10%">
                            <input type="submit" class="searchandclear" name="search" value="Tìm Kiếm" />
                        </span>
                        <span style="float: right; margin-right: 10%">
                            <input type="submit" class="searchandclear" value="Xóa" />
                        </span><br>
                    </p>
                </div>
            </div>
        </div>
    </form>
    <br>
    <section class="add-products">
    <?php
    if (isset($_GET['add-product-book'])) {
    ?>
        <form action="../Controllers/adminAuthorController.php" method="post">
            <h3>Thêm tác giả</h3>
            <input type="text" name="name" class="box" placeholder="Nhập tên tác giả" required>
            <input type="text" name="image" class="box" placeholder="Nhập ảnh của tác giả" required>
            <input type="text" name="slogan" class="box" placeholder="Nhập slogan" required>
            <input type="text" name="information" class="box" placeholder="Nhập link thông tin của tác giả" required>
            <div style="display:flex;justify-content:center;gap:0.5rem;">
                <input type="submit" value="Thêm" name="add_author" class="btn">
                <a href="admin_author.php" class="delete-btn">Đóng</a>
            </div>
        </form>
    <?php
    } else {
        echo '<script>document.querySelector(".add-products").style.display = "none";</script>';
    }
    ?>
</section>

<section class="edit-product-form">
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_author = $authors_collection->findOne(['_id' => new MongoDB\BSON\ObjectId($update_id)]);
        if ($update_author) {
    ?>
            <form action="../Controllers/adminAuthorController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="update_id" value="<?php echo $update_author['_id']; ?>">
                <input type="text" name="update_name" value="<?php echo $update_author['name']; ?>" class="box" required placeholder="Nhập tên tác giả cần cập nhật">
                <input type="text" class="box" name="update_image" value="<?php echo $update_author['image']; ?>" placeholder="Nhập url ảnh tác giả cần cập nhật">
                <input type="text" class="box" name="update_slogan" value="<?php echo $update_author['slogan']; ?>" placeholder="Nhập slogan cần cập nhật">
                <input type="text" class="box" name="update_information" value="<?php echo $update_author['information']; ?>" placeholder="Nhập link thông tin tác giả cập nhật">
                <div style="display:flex;justify-content:center;gap:0.5rem;">
                    <input type="submit" value="Lưu" name="update_author" class="btn">
                    <input type="submit" value="Reset" name="reset_author" id="close-update" class="delete-btn">
                </div>
            </form>
    <?php
        }
    } else {
        echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
    }
    ?>
</section>

<section class="show-products" style="margin-top:30px;">
    <div class="list-add-products">
        <div class="list-products">
            <h1 class="title1">Danh Sách Tác Giả</h1>
        </div>
        <div class="add-products-button">
            <a href="admin_author.php?add-product-book" class="option-btn">Thêm Tác Giả</a>
        </div>
    </div>
    <div class="box-container" style="margin-top: 40px; border-bottom: 1px solid #111;padding-bottom:30px">
        <?php
        // Lấy dữ liệu phân trang từ MongoDB
        $limit = 6; // Số lượng tác giả mỗi trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
        $skip = ($current_page - 1) * $limit; // Số lượng bản ghi bỏ qua

        // Xử lý tham số tìm kiếm từ form
        $authors_query = [];
        $search_params = [];
        if (isset($_POST['search'])) {
            $name = htmlspecialchars($_POST['name']);
            $slogan = htmlspecialchars($_POST['slogan']);
            if (!empty($name) && !empty($slogan)) {
                $authors_query = [
                    'name' => ['$regex' => $name, '$options' => 'i'],
                    'slogan' => ['$regex' => $slogan, '$options' => 'i']
                ];
                $search_params = ['name' => $name, 'slogan' => $slogan];
            } elseif (!empty($name)) {
                $authors_query = ['name' => ['$regex' => $name, '$options' => 'i']];
                $search_params = ['name' => $name];
            } elseif (!empty($slogan)) {
                $authors_query = ['slogan' => ['$regex' => $slogan, '$options' => 'i']];
                $search_params = ['slogan' => $slogan];
            }
        }

        // Tính lại số lượng tác giả theo điều kiện tìm kiếm
        $total_authors = $authors_collection->countDocuments($authors_query);
        $total_pages = ceil($total_authors / $limit); // Tổng số trang

        // Lấy các tác giả cho trang hiện tại với điều kiện tìm kiếm
        $cursor = $authors_collection->find($authors_query, [
            'skip' => $skip,
            'limit' => $limit
        ]);
        $authors = iterator_to_array($cursor);

        // Kiểm tra số lượng tác giả trước khi hiển thị
        if (count($authors) == 0) {
            echo "<p class='empty'>Không tìm thấy tác giả!!!</p>";
        } else {
            foreach ($authors as $author) {
                ?>
                <div class="box">
                    <img src="<?php echo $author['image']; ?>" alt="">
                    <div class="info-author">
                        <h3 class="name"><?php echo $author['name']; ?></h3>
                        <p class="slogan"><?php echo $author['slogan']; ?></p>
                        <form action="../Controllers/adminAuthorController.php" method="post">
                            <a href="<?php echo $author['information']; ?>" class="detail_book">Xem thêm về tác giả <i class="fas fa-angle-right"></i> </a>
                            <div style="display:flex;justify-content:center;gap:0.5rem;">
                                <a href="admin_author.php?update=<?php echo $author['_id']; ?>" class="option-btn">Cập nhật</a>
                                <input type="submit" value="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="delete-btn" name="delete_author">
                                <input type="hidden" value="<?php echo $author['_id']; ?>" name="author_id">
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation example" class="toolbar">
            <ul class="pagination justify-content-center d-flex flex-wrap">
                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($current_page - 1); ?><?php echo http_build_query($search_params); ?>" tabindex="-1">Previous</a>
                </li>
                <?php
                $start_page = ($current_page <= 3) ? 1 : $current_page - 2;
                $end_page = ($total_pages - $current_page >= 2) ? $current_page + 2 : $total_pages;
                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=1' . http_build_query($search_params) . '">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    }
                }
                for ($i = $start_page; $i <= $end_page; $i++) {
                    echo '<li class="page-item ' . (($i == $current_page) ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . http_build_query($search_params) . '">' . $i . '</a></li>';
                }
                if ($end_page < $total_pages) {
                    echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . http_build_query($search_params) . '">' . $total_pages . '</a></li>';
                }
                ?>
                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($current_page + 1); ?><?php echo http_build_query($search_params); ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../View/alert.php'; ?>

</body>
</html>
