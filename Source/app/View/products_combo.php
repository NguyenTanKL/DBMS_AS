<?php
include '../../config/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Book Store Online Website</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../../public/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- custom js file link  -->
    <script src="../../public/js/script.js" defer></script>

</head>

<body>

    <?php include 'header.php'; ?>
    <!-- banner section starts  -->
    <div class="cart_banner">
        <div class="banner">

            <div class="content" data-aos="zoom-in-up" data-aos-delay="500">
                <h3>COMBO SÁCH HAY</h3>
                <p><a href="./home.php">Trang chủ</a>
                    <i class="fas fa-arrow-right"></i>
                    Combo sách hay
                </p>
            </div>

        </div>
    </div>
    <!-- banner section ends -->

    <!-- products section starts  -->

<section class="product combo_product" id="product" data-aos="fade-up" data-aos-delay="500">
        <div class="box-container ">
            <?php
             $per_page = 8;
             $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
             $offset = ($page - 1) * $per_page;
     
             // Query to get the total number of combo products
             $query_total = "SELECT COUNT(*) AS total FROM combo_products";
             $stmt_total = sqlsrv_query($conn, $query_total);
     
             if ($stmt_total === false) {
                 die(print_r(sqlsrv_errors(), true));
             }
     
             $row_total = sqlsrv_fetch_array($stmt_total, SQLSRV_FETCH_ASSOC);
             $total_products = $row_total['total'];
             $total_pages = ceil($total_products / $per_page);
     
             sqlsrv_free_stmt($stmt_total);
     
             // Query to fetch combo products for the current page
             $query_products = "
                 SELECT * FROM combo_products 
                 ORDER BY date ASC 
                 OFFSET ? ROWS 
                 FETCH NEXT ? ROWS ONLY";
             $params = [$offset, $per_page];
             $stmt_products = sqlsrv_query($conn, $query_products, $params);
     
             if ($stmt_products === false) {
                 die(print_r(sqlsrv_errors(), true));
             }
             if (sqlsrv_has_rows($stmt_products)) {
                while ($fetch_combo_products = sqlsrv_fetch_array($stmt_products, SQLSRV_FETCH_ASSOC)) {
            ?>
                    <form method="post" action="../Controllers/cartController.php">
                        <div class="box combo_box" data-aos="fade-up" data-aos-delay="300">
                            <div class="image">
                                <img src="<?php echo htmlspecialchars($fetch_combo_products['image_combo']); ?>" alt="">
                            </div>
                            <div class="content">
                                <h3><?php echo htmlspecialchars($fetch_combo_products['combo_name']); ?></h3>
                                <a href="detail_combo_book.php?get_id=<?php echo $fetch_combo_products['combo_id']; ?>">Xem thêm<i class="fas fa-angle-right"></i></a>
                            </div>
                            <div class="purchase">
                                <h3><?php echo htmlspecialchars($fetch_combo_products['price']); ?>
                                    <span class="rate">₫</span>
                                </h3>
                                <input type="hidden" name="product_quantity" value="1">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_combo_products['combo_name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_combo_products['price']); ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_combo_products['image_combo']); ?>">
                                <input type="hidden" name="current_page" value="<?php echo $page; ?>">
                                <button type="submit" name="add_to_cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php
                        }
                    } else {
                        echo '<p class="empty">No products added yet!</p>';
                    }
                        sqlsrv_free_stmt($stmt_products)
            ?>
        <!-- Pagination -->
        <nav aria-label="Page navigation example" class="toolbar">
            <ul class="pagination justify-content-center d-flex flex-wrap">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
                </li>
                <?php
                $start_page = ($page <= 3) ? 1 : $page - 2;
                $end_page = ($total_pages - $page >= 2) ? $page + 2 : $total_pages;

                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    echo '<li class="page-item ' . (($i == $page) ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                }
                ?>
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</section>


    <?php include 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include '../View/alert.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 150,
        });
    </script>
</body>

</html>