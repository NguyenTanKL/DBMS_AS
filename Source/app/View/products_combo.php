<?php
require 'vendor/autoload.php'; // Include MongoDB library
include '../../config/config.php';
session_start();

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->selectDatabase('bookstore');
$comboProductsCollection = $database->combo_products;

// Pagination setup
$per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Total products and pages calculation
$total_products = $comboProductsCollection->countDocuments();
$total_pages = ceil($total_products / $per_page);

// Fetch combo products for the current page
$comboProducts = $comboProductsCollection->find(
    [],
    [
        'sort' => ['date' => 1],
        'limit' => $per_page,
        'skip' => $start
    ]
);
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
            if ($comboProducts->toArray()) {
                foreach ($comboProducts as $product) {
            ?>
                    <form method="post" action="../Controllers/cartController.php">
                        <div class="box combo_box" data-aos="fade-up" data-aos-delay="300">
                            <div class="image">
                                <img src="<?= $product['image_combo']; ?>" alt="">
                            </div>
                            <div class="content">
                                <h3><?= $product['combo_name']; ?></h3>
                                <a href="detail_combo_book.php?get_id=<?= $product['_id']; ?>">Xem thêm<i class="fas fa-angle-right"></i></a>
                            </div>
                            <div class="purchase">
                                <h3><?= $product['price']; ?>
                                    <span class="rate">₫</span>
                                </h3>
                                <input type="hidden" name="product_quantity" value="1">
                                <input type="hidden" name="product_name" value="<?= $product['combo_name']; ?>">
                                <input type="hidden" name="product_price" value="<?= $product['price']; ?>">
                                <input type="hidden" name="product_image" value="<?= $product['image_combo']; ?>">
                                <input type="hidden" name="current_page" value="<?= $page; ?>">
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
            ?>
            <nav aria-label="Page navigation example" class="toolbar">
                        <ul class="pagination justify-content-center d-flex flex-wrap">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page - 1; ?>" tabindex="-1">Previous</a>
                            </li>
                            <?php
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo '<li class="page-item ' . (($i == $page) ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                }
                            ?>
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
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
