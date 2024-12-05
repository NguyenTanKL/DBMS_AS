<?php
include '../../config/config.php';
session_start();

if (isset($_GET['get_id'])) {
    $get_id = filter_var($_GET['get_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
} else {
    $get_id = '';
    header('Location: home.php');
    exit();
}

if (isset($_POST['delete_review'])) {
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Verify the review exists
    $verify_query = "SELECT * FROM reviews WHERE id = ?";
    $verify_stmt = sqlsrv_prepare($conn, $verify_query, [$delete_id]);

    if ($verify_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_execute($verify_stmt) && sqlsrv_fetch($verify_stmt)) {
        // Delete the review
        $delete_query = "DELETE FROM reviews WHERE id = ?";
        $delete_stmt = sqlsrv_prepare($conn, $delete_query, [$delete_id]);

        if ($delete_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_execute($delete_stmt)) {
            $_SESSION['success_msg'] = 'Review deleted!';
        } else {
            $_SESSION['error_msg'] = 'Failed to delete the review.';
        }
    } else {
        $_SESSION['warning_msg'] = 'Review already deleted or does not exist!';
    }

    // Free statement resources
    sqlsrv_free_stmt($verify_stmt);
    if (isset($delete_stmt)) {
        sqlsrv_free_stmt($delete_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../../public/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- custom js file link  -->
    <script src="../../public/js/script.js" defer></script>
    <title>Detail Book</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- review section starts  -->

<section class="detailBook" id= "detailBook">

    <div class="book-img" data-aos="fade-right" data-aos-delay="300">
        <?php
            $query = "SELECT * FROM combo_products WHERE combo_id = ?";
            $params = [$get_id];
            $select_combo_products = sqlsrv_query($conn, $query, $params);
    
            if ($select_combo_products === false) {
                die(print_r(sqlsrv_errors(), true));
            }
    
            if ($product = sqlsrv_fetch_array($select_combo_products, SQLSRV_FETCH_ASSOC)) {
        ?>
        <div class="box">
            <div class="image">
                 <img src="<?php echo htmlspecialchars($product['image_combo'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
            </div>
        </div>
        <?php
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            sqlsrv_free_stmt($select_combo_products);
        ?>
    </div>
    <div class="information-detail" data-aos="fade-left" data-aos-delay="600">
        <h3><?php echo htmlspecialchars($product['combo_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="evaluate-average">
    <?php
        $average = 0;
        $total_ratings = 0;

        $query = "SELECT rating FROM reviews WHERE combo_id = ?";
        $params = [$get_id];
        $select_ratings = sqlsrv_query($conn, $query, $params);

        if ($select_ratings === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $total_reviews = 0;
        while ($fetch_rating = sqlsrv_fetch_array($select_ratings, SQLSRV_FETCH_ASSOC)) {
            $total_ratings += $fetch_rating['rating'];
            $total_reviews++;
        }

        if ($total_reviews != 0) {
            $average = round($total_ratings / $total_reviews, 1);
            $decimal_part = fmod($total_ratings / $total_reviews, 1);
        }
        ?>
        <h3>
            <?php 
            for ($i = 1; $i <= floor($average); $i++) {
                echo '<i class="fas fa-star"></i>';
            }
            if ($total_reviews > 0 && $decimal_part > 0) {
                echo '<i class="fas fa-star-half-alt"></i>';
            }
            ?>
        </h3>
        <p><?= $total_reviews; ?> đánh giá</p>
    </div>
    

    
    <div class="price-box">
        <div class="box">
        <p>Combo</p>
        <i aria-hidden="true" class="fa fa-book"></i>
        </div>
        <div class="price">
        <span><?php echo $product['price']?>đ</span>
        </div>
    </div>
    <form action="../Controllers/cartController.php" method="post">
    <div class="quantity-input">
        <p>Số lượng: </p>
        <button type="button" class="minus-btn cart-btn" onClick="decrease()">-</button>
        <input type="number" min="1" name="product_quantity" value="1" readonly class="cart-quantity-input" id="product-quantity">
        <button type="button" class="plus-btn cart-btn" onClick="increase()">+</button>
    </div>
        <div>
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['combo_name'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="product_price" value="<?php echo $product['price']?>">
            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image_combo'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="option-cart">
            <div class="add_to_cart combo">
                <i class="fas fa-shopping-cart "></i>
                <input type="submit" name="add_to_cart" value="Thêm combo vào giỏ hàng" class="btnAddCart">
            <input type="hidden" name="product_id" value="<?php echo $product['combo_id']; ?>">
            </div>
            <input type="submit" name="add_to_cart" value="Mua ngay" class="buy_now_btn">
        </div>
    </form>
</section>

<section class="descriptionBook" data-aos="zoom-in-up" data-aos-delay="300">
    <div class="heading combo">
        <h1>Mô tả sản phẩm</h1>

        <?php
        // Fetch product details using MS SQL
        if (isset($_GET['get_id'])) {
            $get_id = $_GET['get_id'];

            // MS SQL Query
            $query = "SELECT * FROM combo_products WHERE combo_id = ?";
            $params = array($get_id);
            $stmt = sqlsrv_query($conn, $query, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_has_rows($stmt)) {
                $product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            } else {
                echo '<p class="empty">No product found!</p>';
                exit;
            }
        } else {
            echo '<p class="empty">Invalid product ID!</p>';
            exit;
        }
        ?>

        <p><?php echo $product['combo_name']?></p>
        <div class="description"><?php echo nl2br($product['description_detail'])?></div>

        <div class="book">
        <?php if (!empty($product['name_1'])) : ?>
                <p>1. <?php echo $product['name_1']; ?></p>
                <div class="description"><?php echo nl2br($product['description_1']); ?></div>
                <?php if (!empty($product['image_1'])) : ?>
                    <img src="<?php echo $product['image_1']; ?>" alt="">
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="book">
        <?php if (!empty($product['name_2'])) : ?>
                <p>2. <?php echo $product['name_2']; ?></p>
                <div class="description"><?php echo nl2br($product['description_2']); ?></div>
                <?php if (!empty($product['image_2'])) : ?>
                    <img src="<?php echo $product['image_2']; ?>" alt="">
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="book">
        <?php if (!empty($product['name_3'])) : ?>
                <p>3. <?php echo $product['name_3']; ?></p>
                <div class="description"><?php echo nl2br($product['description_3']); ?></div>
                <?php if (!empty($product['image_3'])) : ?>
                    <img src="<?php echo $product['image_3']; ?>" alt="">
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <!-- <button class="btn-toggle">Xem thêm</button> --> 
</section>


<!-- reviews section starts  -->

<section class="reviews-container">

   <div class="heading">
    <h1>Đánh giá sản phẩm</h1> 
    <a href="add_review.php?get_id=<?= htmlspecialchars($get_id); ?>" class="add-btn">Thêm đánh giá</a>
    </div>
    <div class="box-review">
    <div class="view-post">
    <?php
        if (isset($get_id)) {
            // Fetch product details using MS SQL
            $query_products = "SELECT * FROM combo_products WHERE combo_id = ?";
            $params_products = array($get_id);
            $stmt_products = sqlsrv_query($conn, $query_products, $params_products);

            if ($stmt_products === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_has_rows($stmt_products)) {
                while ($fetch_products = sqlsrv_fetch_array($stmt_products, SQLSRV_FETCH_ASSOC)) {
                    // Initialize rating counters
                    $total_ratings = 0;
                    $rating_1 = 0;
                    $rating_2 = 0;
                    $rating_3 = 0;
                    $rating_4 = 0;
                    $rating_5 = 0;

                    // Fetch reviews for the product
                    $query_ratings = "SELECT * FROM reviews WHERE combo_id = ?";
                    $params_ratings = array($get_id);
                    $stmt_ratings = sqlsrv_query($conn, $query_ratings, $params_ratings);

                    if ($stmt_ratings === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    $total_reviews = 0;
                    while ($fetch_rating = sqlsrv_fetch_array($stmt_ratings, SQLSRV_FETCH_ASSOC)) {
                        $total_reviews++;
                        $total_ratings += $fetch_rating['rating'];

                        // Count ratings by stars
                        switch ($fetch_rating['rating']) {
                            case 1:
                                $rating_1++;
                                break;
                            case 2:
                                $rating_2++;
                                break;
                            case 3:
                                $rating_3++;
                                break;
                            case 4:
                                $rating_4++;
                                break;
                            case 5:
                                $rating_5++;
                                break;
                        }
                    }

                    // Calculate average rating
                    $average = ($total_reviews > 0) ? round($total_ratings / $total_reviews, 1) : 0;
        ?>
        <div class="row">
            <div class="col">
                <div class="flex">
                    <div class="total-reviews">
                        <h3><?= $average; ?><i class="fas fa-star"></i></h3>
                        <p>(<?= $total_reivews; ?> đánh giá)</p>
                    </div>
                    <div class="total-ratings">
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_5; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_4; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_3; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_2; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_1; ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
   <?php
         }
      }else{
         echo '<p class="empty">post is missing!</p>';
      }
    } else {
        echo '<p class="empty">Invalid product ID!</p>';
    }
   ?>

        </div>
   
   <div class="box-container">
   <?php
    if (isset($get_id)) {
        // Fetch reviews
        $query_reviews = "SELECT * FROM reviews WHERE combo_id = ?";
        $params_reviews = array($get_id);
        $stmt_reviews = sqlsrv_query($conn, $query_reviews, $params_reviews);

        if ($stmt_reviews === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($stmt_reviews)) {
            while ($fetch_review = sqlsrv_fetch_array($stmt_reviews, SQLSRV_FETCH_ASSOC)) {
                ?>
                <div class="box" <?php if ($fetch_review['user_id'] == $user_id) { echo 'style="order: -1;"'; } ?>>
                    <?php
                    // Fetch user details
                    $query_user = "SELECT * FROM users WHERE user_id = ?";
                    $params_user = array($fetch_review['user_id']);
                    $stmt_user = sqlsrv_query($conn, $query_user, $params_user);

                    if ($stmt_user === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    while ($fetch_user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC)) {
                        ?>
                        <div class="user">
                            <?php if (!empty($fetch_user['image'])) { ?>
                                <img src="../../public/images/<?= htmlspecialchars($fetch_user['image']); ?>" alt="">
                            <?php } else { ?>
                                <h3><?= htmlspecialchars(substr($fetch_user['username'], 0, 1)); ?></h3>
                            <?php } ?>
                            <div>
                                <p><?= htmlspecialchars($fetch_user['username']); ?></p>
                                <span><?= htmlspecialchars($fetch_review['date']->format('Y-m-d')); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
      <div class="ratings">
                        <p>
                            <?php for ($i = 0; $i < $fetch_review['rating']; $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                        </p>
                    </div>
                    <h3 class="title"><?= htmlspecialchars($fetch_review['title']); ?></h3>
                    <?php if (!empty($fetch_review['description'])) { ?>
                        <p class="description"><?= nl2br(htmlspecialchars($fetch_review['description'])); ?></p>
                    <?php } ?>
                    <?php if ($fetch_review['user_id'] == $user_id) { ?>
                        <form action="" method="post" class="flex-btn">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($fetch_review['id']); ?>">
                            <a href="update_review.php?get_id=<?= htmlspecialchars($fetch_review['id']); ?>" class="update">Chỉnh sửa</a>
                            <input type="submit" value="Xóa" class="delete-review" name="delete_review">
                        </form>
                    <?php } ?>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">Chưa có đánh giá!</p>';
        }
    } else {
        echo '<p class="empty">Invalid product ID!</p>';
    }
    ?>

   </div>

    </div>
</section>

<!-- reviews section ends -->
<script>
    function decrease() {
        var quantityInput = document.getElementById('product-quantity');
        var currentQuantity = parseInt(quantityInput.value);
        if(currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }

    function increase() {
        var quantityInput = document.getElementById('product-quantity');
        var currentQuantity = parseInt(quantityInput.value);
        quantityInput.value = currentQuantity + 1;
    }
    const btnToggle = document.querySelector('.btn-toggle');
    const description = document.querySelector('.description');

    btnToggle.addEventListener('click', () => {
        if (description.classList.contains('active')) {
            description.classList.remove('active');
            btnToggle.innerText = 'Xem thêm';
        } else {
            description.classList.add('active');
            btnToggle.innerText = 'Rút gọn';
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../View/alert.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
        duration: 800,
        offset:150,
    });
    </script>
</body>

</html>