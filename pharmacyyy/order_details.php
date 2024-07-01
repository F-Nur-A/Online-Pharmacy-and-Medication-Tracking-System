<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $select_order = mysqli_query($conn, "SELECT * FROM `siparişler` WHERE id = '$order_id' AND user_name = '$user_name'") or die('query failed');

    if (mysqli_num_rows($select_order) > 0) {
        $fetch_order = mysqli_fetch_assoc($select_order);
    } else {
        echo "<script>alert('Geçersiz Sipariş ID');</script>";
        echo "<script>window.location.href='orders.php';</script>";
    }

    // Teslimat bilgilerini al
    $select_delivery = mysqli_query($conn, "SELECT * FROM `teslimatlar` WHERE sipariş_id = '$order_id'") or die('query failed');
    $fetch_delivery = mysqli_fetch_assoc($select_delivery);

} else {
    header('location:orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order Details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
       .contact form {
           max-width: 50rem;
           background-color: var(--white);
           border-radius: .5rem;
           box-shadow: var(--box-shadow);
           border: var(--border);
           text-align: center;
           padding: 2rem;
           margin: 0 auto;
       }

       .contact form h3 {
           font-size: 2.5rem;
           margin-bottom: 1rem;
           color: var(--black);
           text-transform: uppercase;
       }

       .contact form .box {
           width: 100%;
           border-radius: .5rem;
           border: var(--border);
           margin: 1rem 0;
           padding: 1.2rem 1.4rem;
           font-size: 1.8rem;
           color: var(--black);
           background-color: var(--light-bg);
       }
   </style>
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="order-details">
    <h1 class="title">SİPARİŞ DETAYLARI</h1>

    <div class="contact">
        <form>
            <h3>SİPARİŞ DURUMU</h3>
            <div class="box" style="background-color: <?php echo $fetch_order['durum'] == 'Beklemede' ? '#afd1f9' : ($fetch_order['durum'] == 'tamamlandı' ? '#f2c3dc' : ''); ?>">
                <?php echo $fetch_order['durum']; ?>
            </div>
            <div class="box" style="background-color: <?php echo isset($fetch_delivery['durum']) && $fetch_delivery['durum'] == 'yolda' ? '#afd1f9' : ($fetch_delivery['durum'] == 'teslim edildi' ? '#f2c3dc' : ''); ?>">
                <?php echo isset($fetch_delivery['durum']) ? $fetch_delivery['durum'] : 'Teslimat bilgisi mevcut değil'; ?>
            </div>
            <div class="box">
                <?php
                if (isset($fetch_delivery['durum']) && $fetch_delivery['durum'] == 'teslim edildi') {
                    echo 'Teslim Tarihi: ' . $fetch_delivery['teslimat_tarihi'];
                } else {
                    echo 'Teslimat bekleniyor';
                }
                ?>
            </div>
        </form>
    </div>
    <div class="more-btn">
        <a href="kullanıcı_my_orders.php" class="option-btn">Siparişlerime Dön</a>
    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
