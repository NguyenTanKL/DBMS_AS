<?php
require __DIR__ . '../../../vendor/autoload.php'; // Sử dụng Composer autoload để hỗ trợ MongoDB
include '../Models/AdminModel.php';
include '../../config/config.php';
session_start();

// Khởi tạo kết nối MongoDB thông qua AdminModel
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->database_bookstore; // Sử dụng cơ sở dữ liệu MongoDB
$adminmodel = new AdminModel($db); // Khởi tạo đối tượng AdminModel với kết nối MongoDB

// UPDATE ORDER
if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $message = $adminmodel->OrderToUpdate($order_update_id, $update_payment);
    if ($message == 'Cập nhật thành công') {
        $_SESSION['success_msg'] = $message;
        header('Location:../View/admin_order.php');
        exit;
    }
}

// DELETE ORDER
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    $message = $adminmodel->deleteOrder($order_id);
    $_SESSION['success_msg'] = $message;
    header('location:../View/admin_order.php');
    exit;
}
