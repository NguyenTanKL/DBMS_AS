<?php
include '../../config/config.php';
session_start();

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->selectDatabase('bookstore');  // Select the "bookstore" database
$comboCollection = $database->combo_products;  // "combo_products" collection
$reviewCollection = $database->reviews;  // "reviews" collection
$userCollection = $database->users;  // "users" collection

// Get combo_id from the URL (GET method)
$get_id = isset($_GET['get_id']) ? $_GET['get_id'] : '';
if (empty($get_id)) {
    header('location:home.php');
    exit;
}

// Deleting a review
if (isset($_POST['delete_review'])) {
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Verify if the review exists
    $review = $reviewCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($delete_id)]);
    if ($review) {
        // Delete the review
        $reviewCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($delete_id)]);
        $_SESSION['success_msg'] = 'Review deleted!';
    } else {
        $_SESSION['warning_msg'] = 'Review already deleted!';
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
            $product = $comboCollection->findOne(['combo_id' => $get_id]);
            if ($product) {
            ?>
                <div class="box">
                    <div class="image">
                        <img src="<?php echo $product['image_combo']; ?>" alt="">
                    </div>
                </div>
            <?php
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
        ?>
    </div>

    <div class="information-detail" data-aos="fade-left" data-aos-delay="600">
        <h3><?php echo $product['combo_name']; ?></h3>
        <p><?php echo $product['description']; ?></p>
        
        <div class="evaluate-average">
            <?php
                $total_ratings = 0;
                $total_reviews = 0;
                $reviews = $reviewCollection->find(['combo_id' => $get_id]);

                foreach ($reviews as $review) {
                    $total_ratings += $review['rating'];
                    $total_reviews++;
                }

                $average = $total_reviews ? round($total_ratings / $total_reviews, 1) : 0;
            ?>

            <h3>
                <?php
                for ($i = 1; $i <= $average; $i++) {
                    echo '<i class="fas fa-star"></i>';
                }
                ?>
            </h3>
            <p><?php echo $total_reviews; ?> đánh giá</p>
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
            <input type="hidden" name="product_name" value="<?php echo $product['combo_name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $product['price']?>">
            <input type="hidden" name="product_image" value="<?php echo $product['image_combo']; ?>">
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
        <p><?php echo $product['combo_name']?></p>
        <div class="description"><?php echo nl2br($product['description_detail'])?></div>
        <div class="book">
            <?php 
                for ($i = 1; $i <= 3; $i++) {
                    $name_key = 'name_' . $i;
                    $desc_key = 'description_' . $i;
                    $image_key = 'image_' . $i;

                    if ($product[$name_key]) {
                        echo "<p>$i. {$product[$name_key]}</p>";
                        echo "<div class='description'>" . nl2br($product[$desc_key]) . "</div>";
                        if ($product[$image_key]) {
                            echo "<img src='{$product[$image_key]}' alt=''>";
                        }
                    }
                }
            ?>
        </div>
        <!-- <button class="btn-toggle">Xem thêm</button> --> 
</section>


<!-- reviews section starts  -->

<section class="reviews-container">

   <div class="heading">
    <h1>Đánh giá sản phẩm</h1> 
    <a href="add_review.php?get_id=<?= $get_id; ?>" class="add-btn">Thêm đánh giá</a>
    </div>
    <div class="box-review">
    <div class="view-post">
    <?php
        $product = $comboCollection->findOne(['combo_id' => $get_id]);
        if ($product) {
            $total_ratings = 0;
            $rating_1 = 0;
            $rating_2 = 0;
            $rating_3 = 0;
            $rating_4 = 0;
            $rating_5 = 0;

            $reviews = $reviewCollection->find(['combo_id' => $get_id]);
            $total_reviews = $reviews->isDead() ? 0 : iterator_count($reviews);

            foreach ($reviews as $review) {
                $total_ratings += $review['rating'];
                if ($review['rating'] == 1) {
                    $rating_1 += $review['rating'];
                }
                if ($review['rating'] == 2) {
                    $rating_2 += $review['rating'];
                }
                if ($review['rating'] == 3) {
                    $rating_3 += $review['rating'];
                }
                if ($review['rating'] == 4) {
                    $rating_4 += $review['rating'];
                }
                if ($review['rating'] == 5) {
                    $rating_5 += $review['rating'];
                }
            }

            $average = $total_reviews ? round($total_ratings / $total_reviews, 1) : 0;
        ?>
        <div class="row">
            <div class="col">
                <div class="flex">
                    <div class="total-reviews">
                        <h3><?= $average; ?><i class="fas fa-star"></i></h3>
                        <p>(<?= $total_reviews; ?> đánh giá)</p>
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
        } else {
            echo '<p class="empty">post is missing!</p>';
        }
        ?>

        </div>
   
   <div class="box-container">
   <?php
            $reviews = $reviewCollection->find(['combo_id' => $get_id]);
            if ($reviews->isDead()) {
                echo '<p class="empty">Chưa có đánh giá!</p>';
            }

            foreach ($reviews as $fetch_review) {
                $user = $userCollection->findOne(['user_id' => $fetch_review['user_id']]);
                if ($user) {
            ?>
                    <div class="box">
                        <div class="user">
                            <?php if ($user['image']) { ?>
                                <img src="../../public/images/<?= $user['image']; ?>" alt="">
                            <?php } else { ?>
                                <h3><?= substr($user['username'], 0, 1); ?></h3>
                            <?php } ?>
                            <div>
                                <p><?= $user['username']; ?></p>
                                <span><?= $fetch_review['date']; ?></span>
                            </div>
                        </div>
                        <div class="ratings">
                            <?php
                            for ($i = 0; $i < $fetch_review['rating']; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }
                            ?>
                        </div>
                        <h3 class="title"><?= $fetch_review['title']; ?></h3>
                        <?php if ($fetch_review['description']) { ?>
                            <p class="description"><?= $fetch_review['description']; ?></p>
                        <?php } ?>

                        <?php if ($fetch_review['user_id'] == $user_id) { ?>
                            <form action="" method="post" class="flex-btn">
                                <input type="hidden" name="delete_id" value="<?= $fetch_review['_id']; ?>">
                                <input type="submit" value="Delete" class="btn" name="delete_review" onclick="return confirm('Delete this review?');">
                            </form>
                        <?php } ?>
                    </div>
            <?php
                }
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
