<?php
// Validate and sanitize cookie data
$user_id = isset($_COOKIE['user_id']) ? filter_var($_COOKIE['user_id'], FILTER_SANITIZE_STRING) : '';

// Validate user ID from the database using a prepared statement
$query_check_id = "SELECT * FROM [users] WHERE user_id = ?";
$params_check_id = [$user_id];
$stmt_check_id = sqlsrv_query($conn, $query_check_id, $params_check_id);

if ($stmt_check_id === false || sqlsrv_fetch($stmt_check_id) === false) {
    $user_id = '';
}
sqlsrv_free_stmt($stmt_check_id);
?>


<!-- header section starts  -->
<header class="header">

    <div id="menu-btn" class="fas fa-bars"></div>

    <a data-aos="zoom-in-left" data-aos-delay="150" href="home.php" class="logo"> 
        <i class="fas fa-book-open"></i>Book Store </a>

    <nav class="navbar">
        <a data-aos="zoom-in-left" data-aos-delay="300" href="home.php">Trang chủ</a>
        <a data-aos="zoom-in-left" data-aos-delay="450" href="about.php">Giới thiệu</a>
        <a data-aos="zoom-in-left" data-aos-delay="600" href="shop.php">Sản phẩm</a>
        <a data-aos="zoom-in-left" data-aos-delay="750" href="products_combo.php">Combo sách hay</a>
        <a data-aos="zoom-in-left" data-aos-delay="900" href="support.php">Hỗ trợ</a>
    </nav>
    <div class = "icons btn">
    <a data-aos="zoom-in-left" data-aos-delay="1100" href="../View/searchBook.php">
       <i class="fas fa-search"></i>
    </a>
    <a data-aos="zoom-in-left" data-aos-delay="1250" class ="tcn">
        <i id="fa-user" class="fas fa-user users"></i>
          <div id="profile-box" class="profile">
          <?php
                $query_profile = "SELECT * FROM [users] WHERE user_id = ?";
                $params_profile = [$user_id];
                $stmt_profile = sqlsrv_query($conn, $query_profile, $params_profile);

                if ($stmt_profile && sqlsrv_has_rows($stmt_profile)) {
                    $fetch_profile = sqlsrv_fetch_array($stmt_profile, SQLSRV_FETCH_ASSOC);
          ?>
            <?php if(!empty($fetch_profile['image'])){ ?>
              <img src="../../public/images/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="" class="image">
            <?php $margin_top = '35rem';  ?>
            <?php } else { ?>
                <?php $margin_top = '25rem';?>
            <?php } ?>
              <p><?= htmlspecialchars($fetch_profile['username']); ?></p>
              <a href="update_profile.php" class="btn">Cập nhập thông tin</a>
              <a href="../View/logout.php" class="delete-btn" onclick="return confirm('logout from this website?');">Đăng xuất</a>
            <?php } ?>
          </div>
        <div id="dropdown-box" class="dropdown-content">
                <a href="loginForm.php">Đăng nhập</a>
                <a href="registerForm.php">Đăng ký</a>
        </div>
    </a>
    <?php
      // Query to get the cart item count
      $query_cart = "SELECT COUNT(*) AS cart_count FROM [cart] WHERE user_id = ?";
        $params_cart = [$user_id];
        $stmt_cart = sqlsrv_query($conn, $query_cart, $params_cart);

        $cart_rows_number = 0;
        if ($stmt_cart && sqlsrv_fetch($stmt_cart)) {
            $cart_data = sqlsrv_get_field($stmt_cart, 0);
            $cart_rows_number = $cart_data ? $cart_data : 0;
        }
        sqlsrv_free_stmt($stmt_cart);
        ?>
    <a data-aos="zoom-in-left" data-aos-delay="1400" href="shopping_cart.php" class="shopping-icon">
            <i class="fas fa-shopping-bag"></i>
          <?php if($cart_rows_number > 0){ ?>
            <span class="badge"><?php echo $cart_rows_number; ?></span> 
          <?php } ?>
          
    </a>
    </div>
<script>
const dropdownBox = document.getElementById("dropdown-box");
const profileBox = document.getElementById("profile-box");
const faUser = document.getElementById("fa-user");

let isBoxVisible = false;

faUser.addEventListener("click", () => {
  if ('<?= $user_id ?>' !== '') {
    if (isBoxVisible) {
      profileBox.style.display = 'none';
      isBoxVisible = false;
    } else {
      profileBox.style.display = 'block';
      isBoxVisible = true;
    }
  }
  else {
    if (isBoxVisible) {
      dropdownBox.style.display = 'none';
      isBoxVisible = false;
    } else {
      dropdownBox.style.display = 'block';
      isBoxVisible = true;
    }
  }
});

let isScrolling = false;
window.addEventListener("scroll", () => {
  if (!isScrolling) {
    window.requestAnimationFrame(() => {
      profileBox.style.display = 'none';
      isScrolling = false;
    });
    isScrolling = true;
  }
});

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <?php include '../View/alert.php'; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      offset: 150,
    });
  </script>
</header>
<!-- header section ends -->
<style>
  .header .profile {
    margin-top: <?php echo $margin_top; ?>;
  }
</style>
