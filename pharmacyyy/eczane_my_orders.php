<?php

@include 'config.php';

session_start();

$eczane_id = $_SESSION['eczane_id'];
$eczane_name = $_SESSION['eczane_name'];

if(!isset($eczane_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'eczane_header.php'; ?>

<section class="placed-orders">
    <h1 class="title">ŞİPARİŞLERİM</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `eczanesiparişleri` WHERE eczane_name = '$eczane_name'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> Sipariş tarihi : <span><?php echo $fetch_orders['sipariş_tarihi']; ?></span> </p>
        <p> İsim : <span><?php echo $fetch_orders['eczane_name']; ?></span> </p>
        <p> Telefon numarası : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> Adres : <span><?php echo $fetch_orders['adres']; ?></span> </p>
        <p> Ödeme yöntemi : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <p> Sipariş detayı : <span><?php echo $fetch_orders['sipariş_özeti']; ?></span> </p>
        <p> Toplam tutar : <span><?php echo $fetch_orders['toplam_fiyat']; ?> TL</span> </p>
        <p> Sipariş durumu : <span style="color:<?php if($fetch_orders['durum'] == 'beklemede'){echo '#597aa2'; }else{echo '#d482ae';} ?>"><?php echo $fetch_orders['durum']; ?></span> </p>
    </div>
    <?php
        }
    }else{
        echo '<p class="empty">Siparişiniz yok!</p>';
    }
    ?>
    </div>

</section>




<script src="js/script.js"></script>

</body>
</html>