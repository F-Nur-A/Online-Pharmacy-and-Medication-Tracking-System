<?php

@include 'config.php';

session_start();

$eczane_id = $_SESSION['eczane_id'];
$eczane_name = $_SESSION['eczane_name'];

$query = "SELECT * FROM `users` WHERE id = '$eczane_id' AND user_type = 'eczane'";
$result = mysqli_query($conn, $query) or die('query failed');
$user = mysqli_fetch_assoc($result);

// Kullanıcı bulunamazsa anasayfaya yönlendir
if(!$user){
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>GÖSTERGE PANOSU</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'eczane_header.php'; ?>

<section class="dashboard">

   <h1 class="title">GÖSTERGE PANOSU</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE durum = 'beklemede' AND  eczane_name = '$eczane_name'") or die('query failed');
            while($fetch_pendings = mysqli_fetch_assoc($select_pendings)){
               $total_pendings += $fetch_pendings['toplam_fiyat'];
            };
         ?>
         <h3><?php echo $total_pendings; ?> TL</h3>
         <p>toplam bekleyen sipariş tutarı</p>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE durum = 'tamamlandı' AND eczane_name = '$eczane_name'") or die('query failed');
            while($fetch_completes = mysqli_fetch_assoc($select_completes)){
               $total_completes += $fetch_completes['toplam_fiyat'];
            };
         ?>
         <h3><?php echo $total_completes; ?> TL</h3>
         <p>tamamlanmış ödemeler</p>
      </div>

      <div class="box">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE eczane_name = '$eczane_name'") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>verilen siparişler</p>
      </div>

      <div class="box">
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>ürün sayısı</p>
      </div>

      <div class="box">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'kullanıcı'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>kullanıcılar</p>
      </div>

      <div class="box">
         <?php
            $select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'eczane'") or die('query failed');
            $number_of_admin = mysqli_num_rows($select_admin);
         ?>
         <h3><?php echo $number_of_admin; ?></h3>
         <p>yöneticiler</p>
      </div>

      <div class="box">
         <?php
            $select_account = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'eczane' or user_type = 'kullanıcı'") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>toplam hesaplar</p>
      </div>
  

   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>