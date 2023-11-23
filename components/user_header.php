<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<?php

// Include your connection file here
include 'components/connect.php';

// Your existing code for user authentication and session handling

if (isset($_POST['logout'])) {
    // Handle logout action
    session_destroy(); // Destroy the session

    // Delete user log entries for the logged-out user
    $user_id = $_SESSION['user_id'];

    if ($user_id) {
        $delete_logs = $conn->prepare("DELETE FROM user_log WHERE user_id = ?");
        $delete_logs->execute([$user_id]);
    }

    // Reset the auto-increment ID in your_table_name
    $resetAutoIncrementQuery = "ALTER TABLE your_table_name AUTO_INCREMENT = 1";
    $conn->exec($resetAutoIncrementQuery);

    header('location: login.php'); // Redirect to the login page or any other desired page
    exit;
}

// Rest of your code
?>


<header class="header" style="border-bottom: .2rem solid var(--black);">

   <section class="flex">

      <a href="home.php" class="logo">Stationary Shoppie</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="about.php">About</a>
         <a href="orders.php">Orders</a>
         <a href="shop.php">Shop</a>
         <!-- <a href="contact.php">Contact</a> -->
      </nav>

      <div class="icons">
         <?php
            $get_wishlist_count = $conn->prepare("SELECT COUNT(*) as total_wishlist_counts FROM `wishlist` WHERE user_id = ?");
            $get_wishlist_count->execute([$user_id]);
            $result = $get_wishlist_count->fetch();
            $total_wishlist_counts = $result['total_wishlist_counts'];

            $get_cart_count = $conn->prepare("SELECT COUNT(*) as total_cart_counts FROM `cart` WHERE user_id = ?");
            $get_cart_count->execute([$user_id]);
            $result = $get_cart_count->fetch();
            $total_cart_counts = $result['total_cart_counts'];

         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i></a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php          
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile["name"]; ?></p>
         <a href="update_user.php" class="btn">update profile</a>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">register</a>
            <a href="user_login.php" class="option-btn">login</a>
         </div>
         <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
         <?php
            }else{
         ?>
         <p>Please login or register first!</p>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">register</a>
            <a href="user_login.php" class="option-btn">login</a>
         </div>
         <?php
            }
         ?>      
         
         
      </div>

   </section>

</header>