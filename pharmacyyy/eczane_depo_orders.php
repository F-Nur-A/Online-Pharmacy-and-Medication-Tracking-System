<?php
@include 'config.php';

session_start();

$eczane_depo_id = $_SESSION['eczane_depo_id'];
$eczane_depo_name = $_SESSION['eczane_depo_name'];

if(!isset($eczane_depo_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];

    if ($update_payment == 'tamamlandı') {
        // Sipariş özeti bilgisini çek
        $order_query = mysqli_query($conn, "SELECT sipariş_özeti FROM `eczanesiparişleri` WHERE id = '$order_id'") or die('query failed');
        if (mysqli_num_rows($order_query) > 0) {
            $order_data = mysqli_fetch_assoc($order_query);
            $order_summary = $order_data['sipariş_özeti'];
            // Sipariş özetini ayrıştır ve stok güncelle
            $order_items = explode(", ", $order_summary);
            $enough_stock = true;
            foreach ($order_items as $item) {
                preg_match('/(.*?)\s\((\d+)\)/', $item, $matches);
                $product_name = trim($matches[1]);
                $quantity = (int)$matches[2];
                // Ürün stoğunu kontrol et
                $stock_query = mysqli_query($conn, "SELECT stok_miktarı FROM `depostok` 
                                                     JOIN `products` ON depostok.ilaç_id = products.ilaç_id
                                                     WHERE products.name = '$product_name' 
                                                     AND depostok.ed_id = (SELECT ed_id FROM eczadeposu WHERE users = '$eczane_depo_id')") or die('query failed');
                $stock_data = mysqli_fetch_assoc($stock_query);
                $current_stock = $stock_data['stok_miktarı'];
                // Yeterli stok var mı kontrol et
                if ($current_stock - $quantity < 0) {
                    $enough_stock = false;
                    break;
                }
            }
            // Yeterli stok varsa güncelle
            if ($enough_stock) {
                foreach ($order_items as $item) {
                    preg_match('/(.*?)\s\((\d+)\)/', $item, $matches);
                    $product_name = trim($matches[1]);
                    $quantity = (int)$matches[2];
                    // Ürün stoğunu güncelle
                    mysqli_query($conn, "UPDATE `depostok` 
                                         JOIN `products` ON depostok.ilaç_id = products.ilaç_id
                                         SET depostok.stok_miktarı = depostok.stok_miktarı - $quantity 
                                         WHERE products.name = '$product_name' 
                                         AND depostok.ed_id = (SELECT ed_id FROM eczadeposu WHERE users = '$eczane_depo_id')") or die('query failed');
                }
                // Sipariş durumu tamamlandı olarak güncelle
                mysqli_query($conn, "UPDATE `eczanesiparişleri` SET durum = 'tamamlandı' WHERE id = '$order_id'") or die('query failed');
                $message[] = 'Sipariş durumu güncellendi!';
            } else {
                $message[] = 'Yetersiz stok! Sipariş tamamlanamadı.';
                // Durumu beklemede olarak ayarla
                $update_payment = 'beklemede';
            }
        }
    } 

    // Durumu güncelle
    mysqli_query($conn, "UPDATE `eczanesiparişleri` SET durum = '$update_payment' WHERE id = '$order_id'") or die('query failed');
    if (empty($message)) {
        $message[] = 'Sipariş durumu güncellendi!';
    }
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `eczanesiparişleri` WHERE id  = '$delete_id'") or die('query failed');
    header('location:eczane_depo_orders.php');
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
   
<?php @include 'eczane_depo_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">ŞİPARİŞLER</h1>


   <div class="box-container">

      <?php
      
      $select_orders = mysqli_query($conn, "SELECT * FROM `eczanesiparişleri` WHERE depo_name = '$eczane_depo_name'") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> Eczane İsmi : <span><?php echo $fetch_orders['eczane_name']; ?></span> </p>
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
            <a href="eczane_depo_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Sipariş silinsin mi?');">Sil</a>
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

<script src="js/script.js"></script>

</body>
</html>
