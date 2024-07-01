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

<header class="header">

    <div class="flex">

        <a href="home.php" class="logo">İTS MOBİL ECZANE</a>

        <nav class="navbar">
            <ul>
                <li><a href="home.php">Anasayfa</a></li>
                <li><a href="#">Sayfalar +</a>
                    <ul>
                        <li><a href="contact.php">İletişim</a></li>
                    </ul>
                </li>
                <li><a href="kullanıcı_shop.php">Mağaza</a></li>
                <li><a href="kullanıcı_my_orders.php">Siparişlerim</a></li>
                </li>
            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="kullanıcı_search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
                $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                $cart_num_rows = mysqli_num_rows($select_cart_count);
            ?>
            <a href="kullanıcı_cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
        </div>

        <div class="account-box">
            <p>Kullanıcı Adı : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>E-mail : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">Çıkış Yap</a>
        </div>

    </div>

</header>