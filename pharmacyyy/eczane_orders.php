<?php
@include 'config.php';

session_start();

$eczane_id = $_SESSION['eczane_id'];
$eczane_name = $_SESSION['eczane_name'];

if (!isset($eczane_id)) {
    header('location:login.php');
}

if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $update_shipping = isset($_POST['update_shipping']) ? $_POST['update_shipping'] : null;

    if ($update_payment == 'tamamlandı') {
        // Sipariş özeti bilgisini çek
        $order_query = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE id = '$order_id'") or die('query failed');
        if (mysqli_num_rows($order_query) > 0) {
            $order_data = mysqli_fetch_assoc($order_query);
            $order_summary = $order_data['sipariş_özeti'];
            $user_id = $order_data['id'];  
            $delivery_address = $order_data['adres'];
            
            // Sipariş özetini ayrıştır ve stok güncelle
            $order_items = explode(", ", $order_summary);
            $enough_stock = true;
            foreach ($order_items as $item) {
                preg_match('/(.*?)\s\((\d+)\)/', $item, $matches);
                $product_name = trim($matches[1]);
                $quantity = (int)$matches[2];
                // Ürün stoğunu kontrol et
                $stock_query = mysqli_query($conn, "SELECT stok_miktarı FROM `eczanestok` 
                                                     JOIN `products` ON eczanestok.ilaç_id = products.ilaç_id
                                                     WHERE products.name = '$product_name' 
                                                     AND eczanestok.eczane_id = (SELECT eczane_id FROM eczane WHERE users = '$eczane_id')") or die('query failed');
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
                    mysqli_query($conn, "UPDATE `eczanestok` 
                                         JOIN `products` ON eczanestok.ilaç_id = products.ilaç_id
                                         SET eczanestok.stok_miktarı = eczanestok.stok_miktarı - $quantity 
                                         WHERE products.name = '$product_name' 
                                         AND eczanestok.eczane_id = (SELECT eczane_id FROM eczane WHERE users = '$eczane_id')") or die('query failed');
                }
                // Sipariş durumu tamamlandı olarak güncelle
                mysqli_query($conn, "UPDATE `siparişler` SET durum = 'tamamlandı', kargo = '$update_shipping' WHERE id = '$order_id'") or die('query failed');
                
                $shipping_user_id_query = mysqli_query($conn, "SELECT id FROM users WHERE name = '$update_shipping' AND user_type = 'kargo' LIMIT 1") or die('query failed');
                $shipping_user_id_data = mysqli_fetch_assoc($shipping_user_id_query);
                $shipping_user_id = $shipping_user_id_data['id'];
                
                // Teslimatlar tablosuna ekle
                mysqli_query($conn, "INSERT INTO `teslimatlar` (users_id, sipariş_id, teslimat_adresi) 
                                     VALUES ('$shipping_user_id', '$order_id', '$delivery_address')") or die('query failed');
                
                $message[] = 'Sipariş durumu ve kargo bilgisi güncellendi!';
            } else {
                $message[] = 'Yetersiz stok! Sipariş tamamlanamadı.';
                // Durumu beklemede olarak ayarla
                $update_payment = 'beklemede';
            }
        }
    } else {
        // Durumu ve kargo bilgilerini güncelle
        mysqli_query($conn, "UPDATE `siparişler` SET durum = '$update_payment', kargo = '$update_shipping' WHERE id = '$order_id'") or die('query failed');
        $message[] = 'Sipariş durumu ve kargo bilgisi güncellendi!';
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `siparişler` WHERE id  = '$delete_id'") or die('query failed');
    header('location:eczane_orders.php');
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

<section class="placed-orders">

   <h1 class="title">ŞİPARİŞLER</h1>

   <div class="box-container">

      <?php
      
      $select_orders = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE eczane_name = '$eczane_name'") or die('query failed');
      if (mysqli_num_rows($select_orders) > 0) {
         while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
      ?>
      <div class="box">
         <p> Müşteri İsmi : <span><?php echo $fetch_orders['user_name']; ?></span> </p>
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
            <select name="update_shipping">
               <option disabled selected><?php echo $fetch_orders['kargo']; ?></option>
               <option value="Aras Kargo">Aras Kargo</option>
               <option value="MNG Kargo">MNG Kargo</option>
               <option value="Sürat Kargo">Sürat Kargo</option>
               <option value="Yurtiçi Kargo">Yurtiçi Kargo</option>
               <option value="PTT Kargo">PTT Kargo</option>
            </select>
            <input type="submit" name="update_order" value="Güncelle" class="option-btn">
            <a href="eczane_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Sipariş silinsin mi?');">Sil</a>
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
