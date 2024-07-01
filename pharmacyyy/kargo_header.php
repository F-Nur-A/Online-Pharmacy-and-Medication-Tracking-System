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

      <a href="kargo_page.php" class="logo">Kargo<span>Paneli</span></a>


      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>Kullanıcı Adı : <span><?php echo $_SESSION['kargo_name']; ?></span></p>
         <p>E-mail : <span><?php echo $_SESSION['kargo_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">Çıkış Yap</a>
      </div>

   </div>

</header>