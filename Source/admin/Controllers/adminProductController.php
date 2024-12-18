<?php
require __DIR__ . '../../../vendor/autoload.php'; // Sử dụng Composer autoload để hỗ trợ MongoDB
include '../Models/AdminModel.php';
include '../../config/config.php';
session_start();

// Khởi tạo kết nối MongoDB thông qua AdminModel
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->database_bookstore; // Sử dụng cơ sở dữ liệu MongoDB
$adminmodel = new AdminModel($db); // Khởi tạo đối tượng AdminModel với kết nối MongoDB

// ADD PRODUCT
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $author = $_POST['author'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $supplier = $_POST['supplier'];
    $publisher = $_POST['publisher'];
    
    $message = $adminmodel->adminAddToProducts($name, $price, $author, $image, $description, $supplier, $publisher);
    $_SESSION['success_msg'] = $message;
    header('Location: ../View/admin_product.php');
    exit;
}

// ADD COMBO PRODUCT
if (isset($_POST['add_product_combo'])) {
    $combo_name = $_POST['combo_name'];
    $price = $_POST['price'];
    $image_combo = $_POST['image_combo'];
    $description = $_POST['description'];
    $description_detail = $_POST['description_detail'];
    $image_1 = $_POST['image_1'];
    $name_1 = $_POST['name_1'];
    $description_1 = $_POST['description_1'];
    $image_2 = $_POST['image_2'];
    $name_2 = $_POST['name_2'];
    $description_2 = $_POST['description_2'];
    $image_3 = $_POST['image_3'];
    $name_3 = $_POST['name_3'];
    $description_3 = $_POST['description_3'];

    $message = $adminmodel->adminAddToComboProducts($combo_name, $price, $image_combo, $description, $description_detail, 
                                                   $image_1, $name_1, $description_1, 
                                                   $image_2, $name_2, $description_2, 
                                                   $image_3, $name_3, $description_3);
    $_SESSION['success_msg'] = $message;
    header('Location: ../View/admin_combo_product.php');
    exit;
}

// DELETE PRODUCT
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $message = $adminmodel->adminDeleteProducts($product_id);
    $_SESSION['success_msg'] = $message;
    header('location:../View/admin_product.php');
    exit;
}

// DELETE COMBO
if (isset($_POST['delete_combo_product'])) {
    $combo_product_id = $_POST['combo_id'];
    $message = $adminmodel->adminDeleteComboProducts($combo_product_id);
    $_SESSION['success_msg'] = $message;
    header('location:../View/admin_combo_product.php');
    exit;
}

// UPDATE PRODUCT
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_author = $_POST['update_author'];
    $update_price = $_POST['update_price'];
    $update_image = $_POST['update_image'];
    $update_description = $_POST['update_description'];
    $update_supplier = $_POST['update_supplier'];
    $update_publisher = $_POST['update_publisher'];
    
    $message = $adminmodel->UpdateToProduct($update_p_id, $update_name, $update_author, $update_price, $update_image, 
                                            $update_description, $update_supplier, $update_publisher);
    $_SESSION['success_msg'] = $message;
    header('Location:../View/admin_product.php');
    exit;
}

// UPDATE COMBO
if (isset($_POST['update_product_combo'])) {
    $update_combo_id = $_POST['update_combo_id'];
    $update_combo_name = $_POST['update_combo_name'];
    $update_price = $_POST['update_price'];
    $update_image_combo = $_POST['update_image_combo'];
    $update_description = $_POST['update_description'];
    $update_description_detail = $_POST['update_description_detail'];
    $update_image_1 = $_POST['update_image_1'];
    $update_name_1 = $_POST['update_name_1'];
    $update_description_1 = $_POST['update_description_1'];
    $update_image_2 = $_POST['update_image_2'];
    $update_name_2 = $_POST['update_name_2'];
    $update_description_2 = $_POST['update_description_2'];
    $update_image_3 = $_POST['update_image_3'];
    $update_name_3 = $_POST['update_name_3'];
    $update_description_3 = $_POST['update_description_3'];

    $message = $adminmodel->UpdateToComboProducts($update_combo_id, $update_combo_name, $update_price, $update_image_combo, 
                                                  $update_description, $update_description_detail, $update_image_1, 
                                                  $update_name_1, $update_description_1, $update_image_2, 
                                                  $update_name_2, $update_description_2, $update_image_3, 
                                                  $update_name_3, $update_description_3);
    $_SESSION['success_msg'] = $message;
    header('Location:../View/admin_combo_product.php');
    exit;
}

// RESET PRODUCT
if (isset($_POST['reset'])) {
    $product_id = $_POST['update_p_id'];
    header("Location: ../View/admin_product.php?update=$product_id");
    exit;
}

// RESET COMBO
if (isset($_POST['reset_combo'])) {
    $combo_id = $_POST['update_combo_id'];
    header("Location: ../View/admin_combo_product.php?update=$combo_id");
    exit;
}
