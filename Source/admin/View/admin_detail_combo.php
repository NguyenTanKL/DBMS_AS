<?php
require '../../vendor/autoload.php';
include '../../config/config.php';
use MongoDB\Client;
// Kết nối đến MongoDB
try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->database_bookstore; // Tên database MongoDB
    $collection = $db->combo_products; // Tên collection MongoDB
} catch (Exception $e) {
    die("Kết nối MongoDB thất bại: " . $e->getMessage());
}

$id = $_GET['id'];

// Kiểm tra tính hợp lệ của ID
if (!isset($id)) {
    die('ID không hợp lệ');
}

// Tìm kiếm sản phẩm trong MongoDB
$product = $collection->findOne(['combo_id' => $id]); 

if (!$product) {
    die('Sản phẩm không tồn tại');
}

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($product['combo_name'], ENT_QUOTES, 'UTF-8'); ?>
    </title>
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include 'admin_header.php'; ?>
    <div class="detail-combo">

        <div class="info-combo">
            <img class="combo-img" src="<?php echo htmlspecialchars($product['image_combo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Combo Image">
            <div class="info-combo-detail">
                <h1 class="combo-name">
                    <?php echo htmlspecialchars($product['combo_name'], ENT_QUOTES, 'UTF-8'); ?>
                </h1>
                <p class="price-combo">
                    Giá: <?php echo number_format($product['price']); ?> ₫
                </p>
                <div class="description-combo">
                    <p> <?php echo nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                </div>
            </div>
        </div>

        <div class="books-combo">
            <h1 class="combo-name">Combo này gồm các sách</h1>
            <p class="combo-name" style="text-align: center;">
                <?php echo htmlspecialchars($product['combo_name'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p class="description-combo">
                <?php echo nl2br(htmlspecialchars($product['description_detail'], ENT_QUOTES, 'UTF-8')); ?>
            </p>
            <?php if (!empty($product['image_1'])) : ?>
                <div class="book-in-combo">
                    <div class="info-book-combo">
                        <p class="name-book-combo">
                            <?php echo htmlspecialchars($product['name_1'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <div class="description-book-combo">
                          <p>  <?php echo nl2br(htmlspecialchars($product['description_1'], ENT_QUOTES, 'UTF-8')); ?></p>
                        </div>
                    </div>
                    <img class="combo-book-img" src="<?php echo htmlspecialchars($product['image_1'], ENT_QUOTES, 'UTF-8'); ?>" alt="Book Image">
                </div>
            <?php endif; ?>
            <?php if (!empty($product['image_2'])) : ?>
                <div class="book-in-combo">
                    <div class="info-book-combo">
                        <p class="name-book-combo">
                            <?php echo htmlspecialchars($product['name_2'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <div class="description-book-combo">
                          <p>  <?php echo nl2br(htmlspecialchars($product['description_2'], ENT_QUOTES, 'UTF-8')); ?></p>
                        </div>
                    </div>
                    <img class="combo-book-img" src="<?php echo htmlspecialchars($product['image_2'], ENT_QUOTES, 'UTF-8'); ?>" alt="Book Image">
                </div>
            <?php endif; ?>
            <?php if (!empty($product['image_3'])) : ?>
                <div class="book-in-combo">
                    <div class="info-book-combo">
                        <p class="name-book-combo">
                            <?php echo htmlspecialchars($product['name_3'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <div class="description-book-combo">
                          <p>  <?php echo nl2br(htmlspecialchars($product['description_3'], ENT_QUOTES, 'UTF-8')); ?></p>
                        </div>
                    </div>
                    <img class="combo-book-img" src="<?php echo htmlspecialchars($product['image_3'], ENT_QUOTES, 'UTF-8'); ?>" alt="Book Image">
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="../../public/js/admin_script.js"></script>
</body>

</html>
