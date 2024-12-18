<?php
require '../../vendor/autoload.php'; // MongoDB library
session_start();

$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->database_bookstore; // Đảm bảo tên database đúng
$collection = $database->cart; // Sử dụng collection 'cart'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin sách đã order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/admin_cart.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="../../public/js/admin_script.js" defer></script>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <section class="listcart" id="listcart" data-aos="zoom-in-up" data-aos-delay="600">
        <h1>Danh sách sản phẩm</h1>
        <?php
        $grand_total = 0;
        $per_page = 9;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $per_page;

        $total_products = $collection->countDocuments();
        $total_pages = ceil($total_products / $per_page);
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $url = "http://localhost:3000/admin/View/admin_cart.php?page=";

        $products = $collection->find([], [
            'skip' => $start,
            'limit' => $per_page
        ])->toArray();

        if (count($products) > 0) {
            echo '<table>
            <thead>
                <tr>
                    <th style="padding-left:2rem;">Tên sản phẩm</th>
                    <th style="padding-left:2rem;">Đơn giá</th>
                    <th style="width: 12rem;">Số lượng</th>
                </tr>
            </thead>
            <tbody>';

            foreach ($products as $product) {
                $total_price = ($product['price'] * $product['quantity']);
                $grand_total += $total_price;
                echo "
                <tr>
                    <td>{$product['product_name']}</td>
                    <td>{$product['price']}<span>₫</span></td>
                    <td>{$product['quantity']}</td>
                </tr>
                ";
            }

            echo '</tbody></table>';
            echo '<div class="grand-total" style="color:#000"> Tổng số tiền cần thanh toán : <span>' . $grand_total . '₫</span> </div>';
        } else {
            echo '<p class="empty">Hiện tại không có yêu cầu nào!</p>';
        }
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

                for ($i = $start_page; $i <= $end_page; $i++) {
                    echo '<li class="page-item ' . (($i == $current_page) ? 'active' : '') . '"><a class="page-link" href="' . $url . $i . '">' . $i . '</a></li>';
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
    </section>

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
