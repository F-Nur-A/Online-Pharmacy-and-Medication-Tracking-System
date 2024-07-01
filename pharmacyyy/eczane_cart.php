<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['eczane_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:eczane_cart.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:eczane_cart.php');
};

if(isset($_POST['update_quantity'])){
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'Ürün miktarı ve toplam fiyat güncellendi!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   
</head>
<body>
   
<?php @include 'eczane_header.php'; ?>


<section class="shopping-cart">

    <h1 class="title">EKLENEN ÜRÜNLER</h1>

    <div class="box-container">

    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
    ?>
    <div  class="box">
        <a href="eczane_cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Sepetten silinsin mi?');"></a>
        <a href="eczane_view_page.php?product_id=<?php echo $fetch_cart['product_id']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_cart['name']; ?></div>
        <div class="price"><?php echo $fetch_cart['price']; ?> TL</div>
        <form action="" method="post">
            <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
            <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity" class="qty">
            <input type="submit" value="Güncelle" class="option-btn" name="update_quantity">
        </form>
        <div class="sub-total"> Toplam Tutar : <span><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?> TL</span> </div>
    </div>
    <?php
    $grand_total += $sub_total;
        }
    }else{
        echo '<p class="empty">Sepetiniz boş</p>';
    }
    ?>
    </div>

    <div class="more-btn">
        <a href="eczane_cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('Sepetteki her şey silinsin mi?');">Tümünü Sil</a>
    </div>

    <div class="cart-total">
        <p>Sipariş Toplam Tutarı : <span><?php echo $grand_total; ?> TL</span></p>
        <a href="eczane_shop.php" class="option-btn">Alışverişe Devam Et</a>
        <a href="eczane_checkout.php" class="btn  <?php echo ($grand_total > 1)?'':'disabled' ?>">Ödeme İşlemine geç</a>
    </div>

</section>





<script src="js/script.js"></script>

</body>
</html>