<?php

@include 'config.php';

session_start();

$üretici_id = $_SESSION['üretici_id'];

if(!isset($üretici_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `deposiparişleri` SET durum = '$update_payment' WHERE id = '$order_id'") or die('query failed');
   $message[] = 'Ödeme durumu güncellendi!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `deposiparişleri` WHERE id  = '$delete_id'") or die('query failed');
   header('location:üretici_orders.php');
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
   
<?php @include 'üretici_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">ŞİPARİŞLER</h1>

   <div class="box-container">

      <?php
      
      $select_orders = mysqli_query($conn, "SELECT * FROM `deposiparişleri`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> Depo İsmi : <span><?php echo $fetch_orders['depo_name']; ?></span> </p>
         <p> Sipariş Detayı : <span><?php echo $fetch_orders['sipariş_özeti']; ?></span> </p>
         <p> Sipariş Tarihi : <span><?php echo $fetch_orders['sipariş_tarihi']; ?></span> </p>
         <p> Toplam Fiyat : <span><?php echo $fetch_orders['toplam_fiyat']; ?> TL</span> </p>
         <p> Adres : <span><?php echo $fetch_orders['adres']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option disabled selected><?php echo $fetch_orders['durum']; ?></option>
               <option value="beklemede">beklemede</option>
               <option value="tamamlandı">tamamlandı</option>
            </select>
            <input type="submit" name="update_order" value="Güncelle" class="option-btn">
            <a href="üretici_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Sipariş silinsin mi?');">Sil</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Henüz sipariş verilmedi!</p>';
      }
      ?>
   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>