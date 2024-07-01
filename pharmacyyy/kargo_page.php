<?php

@include 'config.php';

session_start();

$kargo_id = $_SESSION['kargo_id'];
$kargo_name = $_SESSION['kargo_name'];

if (!isset($kargo_id)) {
    header('location:login.php');
}

if(isset($_POST['deliver_order'])){
    $order_id = $_POST['order_id'];
    $delivery_time = date('Y-m-d H:i:s');
    
    // Teslimatlar tablosunda durum sütununun güncellenmesi
    $update_query = "UPDATE teslimatlar SET durum = 'teslim edildi', teslimat_tarihi = '$delivery_time' WHERE sipariş_id = '$order_id'";
    $update_result = mysqli_query($conn, $update_query);
    
    if($update_result){
        echo "<script>alert('Sipariş teslim edildi.');</script>";
    } else {
        echo "<script>alert('Sipariş teslim edilirken bir hata oluştu.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kargo Siparişleri</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'kargo_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">KARGO SİPARİŞLERİ</h1>

   <div class="box-container">

      <?php
      $select_orders = mysqli_query($conn, "SELECT siparişler.id as order_id, siparişler.user_name, siparişler.adres as teslimat_adresi, siparişler.durum, siparişler.sipariş_tarihi, siparişler.sipariş_özeti, siparişler.toplam_fiyat, teslimatlar.durum as delivery_status FROM `siparişler` LEFT JOIN `teslimatlar` ON siparişler.id = teslimatlar.sipariş_id WHERE siparişler.kargo = '$kargo_name'") or die('query failed');

      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> Siparişi Veren Kişi : <span><?php echo $fetch_orders['user_name']; ?></span> </p>
         <p> Teslimat Adresi : <span><?php echo $fetch_orders['teslimat_adresi']; ?></span> </p>
         <p> Sipariş Detayı : <span><?php echo $fetch_orders['sipariş_özeti']; ?></span> </p>
         <p> Sipariş Tarihi : <span><?php echo $fetch_orders['sipariş_tarihi']; ?></span> </p>
         <p> Teslimat Durumu : <span><?php echo isset($fetch_orders['delivery_status']) ? $fetch_orders['delivery_status'] : 'Teslimat bilgisi mevcut değil'; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['order_id']; ?>">
            <?php if (!empty($fetch_orders['delivery_status']) && $fetch_orders['delivery_status'] != 'teslim edildi') : ?>
                <input type="submit" name="deliver_order" value="Teslim Edildi" class="option-btn">
            <?php endif; ?>
         </form>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">Henüz sipariş verilmedi!</p>';
      }
      ?>
   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
