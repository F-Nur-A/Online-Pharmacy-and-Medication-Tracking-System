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

      <a href="üretici_page.php" class="logo">Üretici<span>Paneli</span></a>

      <nav class="navbar">
         <a href="üretici_page.php">Anasayfa</a>
         <a href="üretici_products.php">Ürünler</a>
         <a href="üretici_orders.php">Siparişler</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>Kullanıcı Adı : <span><?php echo $_SESSION['üretici_name']; ?></span></p>
         <p>E-mail : <span><?php echo $_SESSION['üretici_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">Çıkış Yap</a>
      </div>

   </div>

</header>