<?php
require '../../vendor/autoload.php'; // Ensure you have MongoDB's composer package installed

class AdminModel
{
    private $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    //adminProductController
    public function adminAddToProducts($name, $price, $author, $image, $description, $supplier, $publisher) {
        // Kiểm tra sản phẩm đã tồn tại chưa
        $existingProduct = $this->mongo->products->findOne(['name' => $name]);
        if ($existingProduct) {
            return "Sản phẩm đã tồn tại";
        }
    
        // Thêm sản phẩm vào MongoDB
        $result = $this->mongo->products->insertOne([
            'name' => $name,
            'price' => $price,
            'author' => $author,
            'image' => $image,
            'description' => $description,
            'supplier' => $supplier,
            'publisher' => $publisher
        ]);
        
        if ($result->getInsertedCount() > 0) {
            return "Sản phẩm đã được thêm thành công";
        } else {
            return "Lỗi khi thêm sản phẩm";
        }
    } 
    public function adminDeleteProducts($product_id)
    {
        $productsCollection = $this->mongo->products;
        $result = $productsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($product_id)]);
        
        return $result->getDeletedCount() > 0 ? 'Xóa sản phẩm thành công' : 'Không thể xóa sản phẩm';
    }

    public function UpdateToProduct($update_p_id, $update_name, $update_author, $update_price, $update_image, 
                                $update_description, $update_supplier, $update_publisher) 
    {
        $result = $this->mongo->products->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($update_p_id)], 
            ['$set' => [
                'name' => $update_name,
                'author' => $update_author,
                'price' => $update_price,
                'image' => $update_image,
                'description' => $update_description,
                'supplier' => $update_supplier,
                'publisher' => $update_publisher
            ]]
        );

        if ($result->getModifiedCount() > 0) {
            return "Cập nhật sản phẩm thành công";
        } else {
            return "Lỗi khi cập nhật sản phẩm";
        }
    }
    public function UpdateToComboProducts($update_combo_id, $update_combo_name, $update_price, $update_image_combo, 
                                      $update_description, $update_description_detail, $update_image_1, $update_name_1, 
                                      $update_description_1, $update_image_2, $update_name_2, $update_description_2, 
                                      $update_image_3, $update_name_3, $update_description_3) 
    {
        $ComboCollections = $this->mongo->combo_products;
        $result = $ComboCollections->updateOne(['_id' => $update_combo_id],
        ['$set' => [
            'combo_name' => $update_combo_name,
            'price' => $update_price,
            'image_combo' => $update_image_combo,
            'description' => $update_description,
            'description_detail' => $update_description_detail,
            'name_1' => $update_name_1,
            'image_1' => $update_image_1,
            'description_1' => $update_description_1,
            'name_2' => $update_name_2,
            'image_2' => $update_image_2,
            'description_2' => $update_description_2,
            'name_3' => $update_name_3,
            'image_3' => $update_image_3,
            'description_3' => $update_description_3
        ]]
        );
        return $result->getModifiedCount() > 0 ? 'Cập nhật combo thành công' : 'Cập nhật combo thất bại'; 
    }
    public function adminDeleteComboProducts($delete_id)
    {   
        $comboProductsCollection = $this->mongo->combo_products;
        $result = $comboProductsCollection->deleteOne(['_id' => ($delete_id)]);
        return $result->getDeletedCount() > 0 ? 'Xóa combo thành công' : 'Không thể xóa combo';
    }
    public function resetProduct($product_id) {
        return $product_id;
    }
    public function resetComboProduct($combo_id) {
        return $combo_id;
    }
    public function adminAddToComboProducts($combo_name, $price, $image_combo, $description, $description_detail, 
                                         $image_1, $name_1, $description_1, $image_2, $name_2, $description_2, 
                                         $image_3, $name_3, $description_3) 
    {
        // Kiểm tra combo đã tồn tại chưa
        $existingCombo = $this->mongo->combo_products->findOne(['combo_name' => $combo_name]);
        if ($existingCombo) {
            return "Combo sản phẩm đã tồn tại";
        }

        // Thêm combo vào MongoDB
        $result = $this->mongo->combo_products->insertOne([
            'combo_name' => $combo_name,
            'price' => $price,
            'image_combo' => $image_combo,
            'description' => $description,
            'description_detail' => $description_detail,
            'image_1' => $image_1,
            'name_1' => $name_1,
            'description_1' => $description_1,
            'image_2' => $image_2,
            'name_2' => $name_2,
            'description_2' => $description_2,
            'image_3' => $image_3,
            'name_3' => $name_3,
            'description_3' => $description_3
        ]);
        
        if ($result->getInsertedCount() > 0) {
            return "Combo sản phẩm đã được thêm thành công";
        } else {
            return "Lỗi khi thêm combo sản phẩm";
        }
    }

    // adminAuthorController
    public function adminAddToAuthors($name, $image, $slogan, $information)
    {
        $authorsCollection = $this->mongo->authors;
        $existingAuthor = $authorsCollection->findOne(['name' => $name]);

        if ($existingAuthor) {
            return 'Tác giả đã tồn tại';
        } else {
            $authorsCollection->insertOne([
                'name' => $name,
                'image' => $image,
                'slogan' => $slogan,
                'information' => $information
            ]);
            return 'Thêm tác giả thành công';
        }
    }
    public function adminUpdateAuthor($update_id, $update_name, $update_image, $update_slogan, $update_information)
    {
        $authorsCollection = $this->mongo->authors;
        $result = $authorsCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($update_id)],
            ['$set' => [
                'name' => $update_name,
                'image' => $update_image,
                'slogan' => $update_slogan,
                'information' => $update_information
            ]]
        );

        return $result->getModifiedCount() > 0 ? 'Cập nhật tác giả thành công' : 'Không có thay đổi nào được thực hiện';
    }
    public function adminDeleteAuthor($author_id)
    {
        $authorsCollection = $this->mongo->authors;
        $result = $authorsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($author_id)]);
        return $result->getDeletedCount() > 0 ? 'Xóa tác giả thành công' : 'Không thể xóa tác giả';
    }

    // adminController
    public function adminDeleteUser($user_id)
    {
        $usersCollection = $this->mongo->users;
        $result = $usersCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($user_id)]);
        return $result->getDeletedCount() > 0 ? 'Xóa người dùng thành công' : 'Không thể xóa người dùng';
    }
    public function adminDeleteRequest($request_id)
    {
        $requestsCollection = $this->mongo->requests;
        $result = $requestsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($request_id)]);
        return $result->getDeletedCount() > 0 ? 'Xóa yêu cầu thành công' : 'Không thể xóa yêu cầu';
    }
    public function adminDeleteReview($review_id)
    {
        $reviewsCollection = $this->mongo->reviews;
        $result = $reviewsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($review_id)]);
        return $result->getDeletedCount() > 0 ? 'Xóa đánh giá thành công' : 'Không thể xóa đánh giá';
    }

    // AdminOrderController
    public function OrderToUpdate($order_update_id, $update_payment)
    {
        $ordersCollection = $this->mongo->orders;
        $result = $ordersCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($order_update_id)], // Tìm order theo _id
            ['$set' => ['payment_status' => $update_payment]] // Cập nhật payment status
        );

        return $result->getModifiedCount() > 0 ? 'Cập nhật thành công' : 'Không có thay đổi nào';
    }
    public function deleteOrder($order_id)
    {
        $ordersCollection = $this->mongo->orders;
        $result = $ordersCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($order_id)]);
        return $result->getDeletedCount() > 0 ? 'Xóa đơn hàng thành công' : 'Không thể xóa đơn hàng';
    }

    //adminUpdateProfileCTRL
    public function adminUpdateProfile($fullname, $username, $email, $phonenumber, $oldpass, $newpass, $confirmpass) {
        // Kiểm tra nếu email hoặc username đã tồn tại
        $sql = "SELECT * FROM users WHERE (email = ? OR username = ?) AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $username, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return 'Email đã tồn tại!'; // Hoặc 'Username đã tồn tại!'
        }
        
        // Kiểm tra mật khẩu cũ
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!password_verify($oldpass, $user['password'])) {
            return 'Không đúng mật khẩu cũ!';
        }
    
        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($newpass != $confirmpass) {
            return 'Xác nhận mật khẩu không khớp!';
        }
        if (empty($newpass)) {
            return 'Vui lòng nhập mật khẩu mới!';
        }
        
        // Cập nhật thông tin người dùng
        $hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET fullname = ?, username = ?, email = ?, phonenumber = ?, password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $fullname, $username, $email, $phonenumber, $hashedPassword, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            return 'Cập nhật thành công!';
        } else {
            return 'Lỗi khi cập nhật thông tin!';
        }
    }
    public function adminDetelePic() {
        // Kiểm tra xem người dùng có ảnh đại diện không
        $sql = "SELECT image FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
        if (empty($user['image'])) {
            return 'Image already deleted!'; // Nếu không có ảnh để xóa
        }
    
        // Xóa ảnh
        $imagePath = '../../uploads/' . $user['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa ảnh khỏi thư mục uploads
        }
    
        // Cập nhật trường ảnh trong CSDL thành null
        $sql = "UPDATE users SET image = NULL WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            return 'Xóa ảnh thành công!';
        } else {
            return 'Lỗi khi xóa ảnh!';
        }
    }    
}
