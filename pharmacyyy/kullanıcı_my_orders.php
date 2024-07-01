<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (!isset($user_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="placed-orders">
    <h1 class="title">SİPARİŞLERİM</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE user_name = '$user_name'") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
    ?>
    <div class="box">
        <p> Sipariş tarihi : <span><?php echo $fetch_orders['sipariş_tarihi']; ?></span> </p>
        <p> Eczane İsmi : <span><?php echo $fetch_orders['eczane_name']; ?></span> </p>
        <p> İsim : <span><?php echo $fetch_orders['user_name']; ?></span> </p>
        <p> Telefon numarası : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> Adres : <span><?php echo $fetch_orders['adres']; ?></span> </p>
        <p> Ödeme yöntemi : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <p> Sipariş detayı : <span><?php echo $fetch_orders['sipariş_özeti']; ?></span> </p>
        <p> Toplam tutar : <span><?php echo $fetch_orders['toplam_fiyat']; ?> TL</span> </p>
        <a href="order_details.php?order_id=<?php echo $fetch_orders['id']; ?>" class="option-btn">Sipariş Durumu</a>
    </div>
    <?php
            }
        } else {
            echo '<p class="empty">Siparişiniz yok!</p>';
        }
    ?>
    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
