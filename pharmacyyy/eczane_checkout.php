<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['eczane_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

$eczanedeposu_query = mysqli_query($conn, "SELECT * FROM `eczadeposu`") or die('query failed');

if (isset($_POST['order'])) {
    $eczanedeposu_id = mysqli_real_escape_string($conn, $_POST['eczadeposu']);
    $eczane_name = mysqli_real_escape_string($conn, $_POST['eczane_name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['mahalle'] . ', ' . $_POST['sokak'] . ', ' . $_POST['daire'] . ', ' . $_POST['ilce'] . ', ' . $_POST['il']);
    $placed_on = date('Y-m-d');

    // Üretici adını almak için sorgu
    $eczanedepo_name_query = mysqli_query($conn, "SELECT name FROM `eczadeposu` WHERE ed_id = '$eczanedeposu_id'") or die('query failed');

    if (mysqli_num_rows($eczanedepo_name_query) > 0) {
        $eczanedepo_name = mysqli_fetch_assoc($eczanedepo_name_query)['name'];
    } else {
        die('eczane deposu bulunamadı!');
    }

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `eczanesiparişleri` WHERE eczane_name = '$eczane_name' AND number = '$number' AND method = '$method' AND adres = '$address' AND sipariş_özeti = '$total_products' AND toplam_fiyat = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'Sepetiniz boş!';
    } elseif (mysqli_num_rows($order_query) > 0) {
        $message[] = 'Bu sipariş daha önce verildi!';
    } else {
        mysqli_query($conn, "INSERT INTO `eczanesiparişleri`(depo_name, eczane_name, sipariş_özeti, sipariş_tarihi, method, toplam_fiyat, number, adres) VALUES('$eczanedepo_name', '$eczane_name', '$total_products', '$placed_on', '$method', '$cart_total', '$number', '$address')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'Sipariş başarıyla verildi!';
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'eczane_header.php'; ?>

<section class="display-order">
    <?php
    $grand_total = 0;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo $fetch_cart['price'] . ' TL' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
    <?php
        }
    } else {
        echo '<p class="empty">Sepetiniz boş</p>';
    }
    ?>
    <div class="grand-total">Toplam Tutar : <span><?php echo $grand_total; ?> TL</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>Siparişinizi Verin</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Adınız :</span>
                <input type="text" name="eczane_name" placeholder="adınızı girin" required>
            </div>
            <div class="inputBox">
                <span>Telefon Numaranız :</span>
                <input type="number" name="number" min="0" placeholder="telefon numaranızı girin" required>
            </div>
            <div class="inputBox">
                <span>Ödeme Yöntemi :</span>
                <select name="method">
                    <option value="kapıda ödeme">Kapıda ödeme</option>
                    <option value="kredi kartı">Kredi kartı</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Mahalle adı :</span>
                <input type="text" name="mahalle" placeholder="ör. Telsiz mah." required>
            </div>
            <div class="inputBox">
                <span>Cadde/Sokak adı ve numarası :</span>
                <input type="text" name="sokak" placeholder="ör. Sümbül sok. No:62" required>
            </div>
            <div class="inputBox">
                <span>Daire/Apartman numarası :</span>
                <input type="text" name="daire" placeholder="ör. daire:2" required>
            </div>
            <div class="inputBox">
                <span>İlçe adı :</span>
                <input type="text" name="ilce" required>
            </div>
            <div class="inputBox">
                <span>İl adı :</span>
                <input type="text" name="il" required>
            </div>
            <div class="inputBox">
                <span>Eczane Deposu Seçiniz :</span>
                <select name="eczadeposu" required>
                    <option value="">Eczane Deposu Seçiniz</option>
                    <?php
                    if (mysqli_num_rows($eczanedeposu_query) > 0) {
                        while ($eczanedeposu = mysqli_fetch_assoc($eczanedeposu_query)) {
                            echo '<option value="' . $eczanedeposu['ed_id'] . '">' . $eczanedeposu['name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">eczane deposu bulunamadı</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <input type="submit" name="order" value="Sipariş Ver" class="btn">

    </form>

</section>

<script src="js/script.js"></script>

</body>
</html>
