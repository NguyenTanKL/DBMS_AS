<?php
include '../../config/config.php';
// $get_author = mysqli_query($conn, "SELECT * FROM `authors` WHERE ")
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
    <!-- <link rel="stylesheet" href="../../public/css/admin.css"> -->
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
                    <!-- <hr><br> -->
                    <p>
                        <label for="name"><b>Tên tác giả&emsp;</b></label>
                        <input type="text" placeholder="Nhập tên" name="name">
                        <br>
                    </p>
                    <p><br>
                    <p>
                        <label for="slogan"><b>Description&emsp;</b></label>
                        <input type="text" placeholder="Nhập tên" name="slogan">
                        <br>
                    </p>
                    <p><br>
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
    <section class="show-products" style="margin-top:30px; ">
        <div class="box-container" style="border-bottom: 1px solid #111;padding-bottom:30px">
            <?php
            $sql_authors = null;
            if (isset($_POST['search'])) {
                $_POST['name'] = addslashes($_POST['name']);
                $_POST['slogan'] = addslashes($_POST['slogan']);
                if (!empty($_POST['name']) && !empty($_POST['slogan'])) {
                    $sql_authors = mysqli_query($conn, "SELECT * FROM `authors` WHERE name LIKE '%{$_POST['name']}%' AND slogan LIKE '%{$_POST['slogan']}%'") or die('query failed');
                } elseif (!empty($_POST['name'])) {
                    $sql_authors = mysqli_query($conn, "SELECT * FROM `authors` WHERE name LIKE '%{$_POST['name']}%'") or die('query failed');
                } elseif (!empty($_POST['slogan'])) {
                    $sql_authors = mysqli_query($conn, "SELECT * FROM `authors` WHERE slogan LIKE '%{$_POST['slogan']}%'") or die('query failed');
                }
                if ($sql_authors && $sql_authors->num_rows > 0) {
                    while ($fetch_authors_sql = mysqli_fetch_assoc($sql_authors)) {
            ?>
                        <div class="box">
                            <img src="<?php echo $fetch_authors_sql['image']; ?>" alt="">
                            <div class="info-author">
                                <h3 class="name"><?php echo $fetch_authors_sql['name']; ?></h3>
                                <p class="slogan"><?php echo $fetch_authors_sql['slogan']; ?></p>
                                <form action="../Controllers/adminAuthorController.php" method="post">
                                    <a href="<?php echo $fetch_authors_sql['information']; ?>" class="detail_book">Xem thêm về tác
                                        giả
                                        <i class="fas fa-angle-right"></i> </a>
                                    <div style="display:flex;justify-content:center;gap:0.5rem; ">
                                        <a href="admin_author.php?update=<?php echo $fetch_authors_sql['id']; ?>" class="option-btn">Cập
                                            nhật</a>
                                        <input type="submit" value="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="delete-btn" name="delete_author">
                                        <input type="hidden" value="<?php echo $fetch_authors_sql['id'] ?>" name="author_id">
                                    </div>
                                </form>

                            </div>
                        </div>
                    <?php
                    };
                    ?>
            <?php
                } else {
                    echo "<p class='empty'>Không tìm thấy tác giả!!!</p>";
                }
            }
            ?>
        </div>
    </section>

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
                <div style="display:flex;justify-content:center;gap:0.5rem; ">
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
    <section class="show-products" style="margin-top:30px;">
        <div class="list-add-products">
            <div class="list-products">
                <h1 class="title1">Danh Sách Tác Giả</h1>
            </div>
            <div class="add-products-button">
                <a href="admin_author.php?add-product-book" class="option-btn">Thêm Tác Giả</a>
            </div>
        </div>
        <div class="box-container" style="margin-top:40px;">
            <?php
            $per_page = 6;
            $total_pages = 0;
            $current_page = 0;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $start = ($page - 1) * $per_page;
            $select_authors = mysqli_query($conn, "SELECT * FROM `authors`") or die('query failed');
            $sql = "SELECT * FROM `authors`";
            // $author = $conn->query($sql);
            if (mysqli_num_rows($select_authors) > 0) {
                while ($row = mysqli_fetch_assoc($select_authors)) {
            ?>
                    <?php
                    $total_products = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `authors`") or die('query failed');
                    $total_products = mysqli_fetch_assoc($total_products)['total'];
                    $total_pages = ceil($total_products / $per_page);
                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $url = "http://localhost:3000/admin/View/admin_author.php?page=";
                    // Tính toán giới hạn của LIMIT trong câu truy vấn SQL
                    $offset = ($current_page - 1) * $per_page;
                    // Truy vấn sản phẩm trong cơ sở dữ liệu với LIMIT và OFFSET
                    $select_authors = mysqli_query($conn, "SELECT * FROM authors LIMIT $per_page OFFSET $offset") or die('query failed');
                    if (mysqli_num_rows($select_authors) > 0) {
                        while ($row = mysqli_fetch_assoc($select_authors)) {
                    ?>
                            <div class="box">
                                <img src="<?php echo $row['image']; ?>" alt="">
                                <div class="info-author">
                                    <h3 class="name"><?php echo $row['name']; ?></h3>
                                    <p class="slogan"><?php echo $row['slogan']; ?></p>
                                    <form action="../Controllers/adminAuthorController.php" method="post">
                                        <a href="<?php echo $row['information']; ?>" class="detail_book">Xem thêm về tác giả <i class="fas fa-angle-right"></i> </a>
                                        <div style="display:flex;justify-content:center;gap:0.5rem; ">
                                            <a href="admin_author.php?update=<?php echo $row['id']; ?>" class="option-btn">Cập nhật</a>
                                            <input type="submit" value="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="delete-btn" name="delete_author">
                                            <input type="hidden" value="<?php echo $row['id'] ?>" name="author_id">
                                        </div>
                                    </form>

                                </div>
                            </div>
            <?php
                        }
                    }
                }
            } else {
                echo '<p class="empty">Không có tác giả nào tại đây</p>';
            }
            ?>
        </div>
        <?php
        if ($total_pages != null || $current_page != null) {
        ?>
            <nav aria-label="Page navigation example" class="toolbar">
                <ul class="pagination justify-content-center d-flex flex-wrap">
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $url . ($current_page - 1); ?>" tabindex="-1">Previous</a>
                    </li>
                    <?php
                    $start_page = ($current_page <= 3) ? 1 : $current_page - 2;
                    $end_page = ($total_pages - $current_page >= 2) ? $current_page + 2 : $total_pages;
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . $url . '1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                        }
                    }
                    $num_displayed_pages = $end_page - $start_page + 1;
                    $display_ellipsis = ($num_displayed_pages >= 7);
                    for ($i = $start_page; $i <= $end_page; $i++) {
                        if ($num_displayed_pages >= 7) {
                            if ($i == $start_page + 3 || $i == $end_page - 3) {
                                if (!$display_ellipsis) {
                                    echo '<li class="page-item"><a class="page-link" href="#">' . $i . '</a></li>';
                                }
                                continue;
                            }
                        }
                        if ($num_displayed_pages <= 5 || ($i >= $current_page - 2 && $i <= $current_page + 2)) {
                            echo '<li class="page-item ' . (($i == $current_page) ? 'active' : '') . '"><a class="page-link" href="' . $url . $i . '">' . $i . '</a></li>';
                        }
                    }
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . $url . $total_pages . '">' . $total_pages . '</a></li>';
                    }
                    ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $url . ($current_page + 1); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php
        }
        ?>
    </section>

    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `authors` WHERE id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
        ?>
                    <form action="../Controllers/adminAuthorController.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Nhập tên tác giả cần cập nhật">
                        <input type="text" class="box" name="update_image" value="<?php echo $fetch_update['image']; ?>" placeholder="Nhập url ảnh tác giả cần cập nhật">
                        <input type="text" class="box" name="update_slogan" value="<?php echo $fetch_update['slogan']; ?>" placeholder="Nhập slogan cần cập nhật">
                        <input type="text" class="box" name="update_information" value="<?php echo $fetch_update['information']; ?>" placeholder="Nhập link thông tin tác giả cập nhật">
                        <div style="display:flex;justify-content:center;gap:0.5rem; ">
                            <input type="submit" value="Lưu" name="update_author" class="btn">
                            <input type="submit" value="Reset" name="reset_author" id="close-update" class="delete-btn">
                        </div>

                    </form>
        <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
        ?>

    </section>
    <script src="../../public/js/admin_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../View/alert.php'; ?>

</body>

</html>