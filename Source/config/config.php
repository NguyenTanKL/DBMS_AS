<?php

//kết nối mysql cũ
$conn = mysqli_connect('localhost','root','','Database_BookStore') or die('connection failed');
mysqli_set_charset($conn, 'utf8mb4');

// // kết nối mongodb mới
// require __DIR__ . '/../vendor/autoload.php'; // Sử dụng Composer autoload để hỗ trợ MongoDB

// // Kết nối MongoDB
// $client = new MongoDB\Client("mongodb://localhost:27017");
// $db = $client->database_bookstore; // Thay "BookStore" bằng tên database MongoDB của bạn

function create_unique_id(){
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_lenght = strlen($characters);
    $random_string = '';
    for($i = 0; $i < 10; $i++){
       $random_string .= $characters[mt_rand(0, $characters_lenght - 1)];
    }
    return $random_string;
 }
 function create_unique_number_id(){
   $characters = '0123456789';
 $characters_lenght = strlen($characters);
 $random_string = '';
 for($i = 0; $i < 5; $i++){
    $random_string .= $characters[mt_rand(0, $characters_lenght - 1)];
 }
 return $random_string;
}
if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }

 //Kiểm tra user_id trong mysql cũ
 $check_id = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('query failed');
  if(mysqli_num_rows($check_id) == 0) {
    $user_id = '';
  }

// // Kiểm tra user_id trong MongoDB
// $users_collection = $db->users; // Thay "users" bằng tên collection chứa dữ liệu người dùng
// $check_id = $users_collection->findOne(['user_id' => $user_id]);

// if (!$check_id) {
//     $user_id = '';
// }

?>

<!-- ALTER TABLE `reviews` ADD CONSTRAINT `fk_reviews_users` 
FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
 -->
 <!-- ALTER TABLE `orders` ADD CONSTRAINT `fk_order_users` 
FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
 -->
 <!-- ALTER TABLE `cart` ADD CONSTRAINT `fk_user_cart`
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
  ON DELETE CASCADE; 
 fk_reviews_users
 fk_user_review_product ////
 `fk_review_combo` ///
 ALTER TABLE `users` ADD PRIMARY KEY (`user_id`);
-->