<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['eczane_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    $product_image = $_POST['product_image'];
    $product_composition = $_POST['product_composition'];
    $product_skt = $_POST['product_skt'];
    $product_barcode = $_POST['product_barcode'];
    $product_side_effect = $_POST['product_side_effect'];
    $product_uses = $_POST['product_uses'];
    
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'Ürün sepete daha önce eklendi.';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, product_id, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Sepete eklendi';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
        .quick-view form .composition,
        .quick-view form .skt,
        .quick-view form .barcode,
        .quick-view form .side_effect,
        .quick-view form .uses {
        margin: 1rem 0;
        font-size: 1.5rem;
        color: var(--black);
        }

    </style>
</head>
<body>
   
<?php @include 'eczane_header.php'; ?>

<section class="quick-view">

    <h1 class="title">ÜRÜN DETAYLARI</h1>

    <?php  
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE ilaç_id = '$product_id'") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
                while($fetch_products = mysqli_fetch_assoc($select_products)){
    ?>
    <form action="" method="POST">
        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_products['name']; ?></div>
        <div class="price"><?php echo $fetch_products['price']; ?> TL</div>
        <div class="composition">Bileşimler: <?php echo $fetch_products['composition']; ?></div>
        <div class="side_effect">Yan Etkiler: <?php echo $fetch_products['side_effect']; ?></div>
        <div class="uses">Kullanım Alanları: <?php echo $fetch_products['uses']; ?></div>
        <div class="skt">Son Kullanma Tarihi: <?php echo $fetch_products['skt']; ?></div>
        <div class="barcode">Barkod: <?php echo $fetch_products['barcode']; ?></div>
        <input type="number" name="product_quantity" value="1" min="0" class="qty">
        <input type="hidden" name="product_id" value="<?php echo $fetch_products['ilaç_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
        <input type="hidden" name="product_composition" value="<?php echo $fetch_products['composition']; ?>">
        <input type="hidden" name="product_skt" value="<?php echo $fetch_products['skt']; ?>">
        <input type="hidden" name="product_barcode" value="<?php echo $fetch_products['barcode']; ?>">
        <input type="hidden" name="product_side_effect" value="<?php echo $fetch_products['side_effect']; ?>">
        <input type="hidden" name="product_uses" value="<?php echo $fetch_products['uses']; ?>">
        <input type="submit" value="Sepete Ekle" name="add_to_cart" class="btn">
      </form>
    <?php
                }
            } else {
                echo '<p class="empty">Ürün detayı mevcut değil!</p>';
            }
        }
    ?>

    <div class="more-btn">
        <a href="eczane_page.php" class="option-btn">Anasayfaya Dön</a>
    </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
