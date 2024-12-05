<?php
require __DIR__ . '../../../vendor/autoload.php'; // Sử dụng Composer autoload để hỗ trợ MongoDB
include '../Models/AdminModel.php';
include '../../config/config.php';
session_start();

// Cập nhật thông tin tài khoản
if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $confirmpass = $_POST['confirmpass'];

    $user = new AdminModel($conn);
    $message = $user->adminUpdateProfile($fullname, $username, $email, $phonenumber, $oldpass, $newpass, $confirmpass);

    // Kiểm tra các thông báo lỗi và hiển thị thông báo thích hợp
    if (
        $message == 'Email đã tồn tại!' ||
        $message == 'Username đã tồn tại!' ||
        $message == 'Kích thước hình ảnh quá lớn!' ||
        $message == 'Vui lòng nhập mật khẩu mới!' ||
        $message == 'Xác nhận mật khẩu không khớp!' ||
        $message == 'Không đúng mật khẩu cũ!'
    ) {
        $_SESSION['warning_msg'] = $message;
    } else {
        $_SESSION['success_msg'] = $message;
    }

    // Chuyển hướng về trang cập nhật thông tin cá nhân
    header('Location: ../View/admin_update_profile.php');
    exit;
}

// Xóa hình ảnh tài khoản
if (isset($_POST['delete_image'])) {
    $user = new AdminModel($conn);
    $message = $user->adminDetelePic();

    // Kiểm tra nếu hình ảnh đã được xóa
    if ($message == 'Image already deleted!') {
        $_SESSION['warning_msg'] = $message;
    } else {
        $_SESSION['success_msg'] = $message;
    }

    // Chuyển hướng về trang cập nhật thông tin cá nhân
    header('Location: ../View/admin_update_profile.php');
    exit;
}
