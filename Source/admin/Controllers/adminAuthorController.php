<?php
require __DIR__ . '../../../vendor/autoload.php'; // Sử dụng Composer autoload để hỗ trợ MongoDB
include '../Models/AdminModel.php';
include '../../config/config.php';
session_start();

// Khởi tạo kết nối với MongoDB thông qua AdminModel
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->database_bookstore; // Sử dụng cơ sở dữ liệu MongoDB
$adminModel = new AdminModel($db); // Khởi tạo đối tượng AdminModel với kết nối MongoDB

// ADD AUTHOR
if (isset($_POST['add_author'])) {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $slogan = $_POST['slogan'];
    $information = $_POST['information'];

    // Gọi phương thức adminAddToAuthors của AdminModel để thêm tác giả mới
    $result = $adminModel->adminAddToAuthors($name, $image, $slogan, $information);

    // Set thông báo thành công hay lỗi
    $_SESSION['success_msg'] = $result;
    header('Location: ../View/admin_author.php');
    exit;
}

// UPDATE AUTHOR
if (isset($_POST['update_author'])) {
    $update_id = $_POST['update_id'];
    $update_name = $_POST['update_name'];
    $update_image = $_POST['update_image'];
    $update_slogan = $_POST['update_slogan'];
    $update_information = $_POST['update_information'];

    // Gọi phương thức adminUpdateAuthor trong AdminModel để cập nhật thông tin tác giả
    $result = $adminModel->adminUpdateAuthor($update_id, $update_name, $update_image, $update_slogan, $update_information);

    // Set thông báo thành công hay lỗi
    $_SESSION['success_msg'] = $result;
    header('Location: ../View/admin_author.php');
    exit;
}

// DELETE AUTHOR
if (isset($_POST['delete_author'])) {
    $author_id = $_POST['author_id'];

    // Gọi phương thức adminDeleteAuthor trong AdminModel để xóa tác giả
    $result = $adminModel->adminDeleteAuthor($author_id);

    // Set thông báo thành công hay lỗi
    $_SESSION['success_msg'] = $result;
    header('Location: ../View/admin_author.php');
    exit;
}

// RESET
if (isset($_POST['reset_author'])) {
    $author_id = $_POST['update_id'];
    header("Location: ../View/admin_author.php?update=$author_id");
    exit;
}
