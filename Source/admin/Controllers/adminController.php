<?php
require __DIR__ . '../../../vendor/autoload.php'; // Sử dụng Composer autoload để hỗ trợ MongoDB
include '../Models/AdminModel.php';
include '../../config/config.php';
session_start();

// Khởi tạo kết nối MongoDB thông qua AdminModel
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->database_bookstore; // Sử dụng cơ sở dữ liệu MongoDB
$adminmodel = new AdminModel($db); // Khởi tạo đối tượng AdminModel với kết nối MongoDB

// DELETE USER
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $message = $adminmodel->adminDeleteUser($user_id);
    $_SESSION['success_msg'] = $message;
    header('location:../View/admin_user.php');
    exit;
}

// DELETE REQUEST
if (isset($_POST['delete_request'])) {
    $request_id = $_POST['request_id'];
    $message = $adminmodel->adminDeleteRequest($request_id);
    $_SESSION['success_msg'] = $message;
    header('location:../View/admin_request.php');
    exit;
}

// DELETE REVIEW
if (isset($_POST['delete_review'])) {
    $review_id = $_POST['review_id'];
    $message = $adminmodel->adminDeleteReview($review_id);
    $_SESSION['success_msg'] = $message;
    header('location:../View/admin_review.php');
    exit;
}
