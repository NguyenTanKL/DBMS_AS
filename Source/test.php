<?php
require 'vendor/autoload.php'; // Tải Composer autoloader

use MongoDB\Client;

try {
    $client = new Client("mongodb://localhost:27017"); // Thay đổi kết nối nếu cần
    echo "Kết nối thành công!";
} catch (Exception $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>  