<?php

@include 'config.php';

session_start();

$üretici_id = $_SESSION['üretici_id'];

if(!isset($üretici_id)){
   header('location:login.php'); 
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $composition = mysqli_real_escape_string($conn, $_POST['composition']);
   $skt = mysqli_real_escape_string($conn, $_POST['skt']);
   $barcode = mysqli_real_escape_string($conn, $_POST['barcode']);
   $side_effect = mysqli_real_escape_string($conn, $_POST['side_effect']);
   $uses = mysqli_real_escape_string($conn, $_POST['uses']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'Bu ürün adı zaten var!';
   }else{
      $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, price, composition, skt, barcode, side_effect, uses, image) VALUES('$name', '$price', '$composition', '$skt', '$barcode', '$side_effect', '$uses', '$image')") or die('query failed');

      if($insert_product){
         if($image_size > 2000000){
            $message[] = 'Görsel boyutu çok büyük!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folter);
            $message[] = 'Ürün başarıyla eklendi!';
         }
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE ilaç_id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE ilaç_id = '$delete_id'") or die('query failed');
   //mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
   header('location:üretici_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ürünler</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
        .image {
            max-width: 100%;
            max-height: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>

</head>
<body>
   
<?php @include 'üretici_header.php'; ?>

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>YENİ ÜRÜN EKLE</h3>
      <input type="text" class="box" required placeholder="ürün adını girin" name="name">
      <input type="number" min="0" class="box" required placeholder="ürün fi̇yatını gi̇ri̇n" name="price">
      <textarea name="composition" class="box" required placeholder="ilacın bileşenlerini girin" cols="30" rows="10"></textarea>
      <input type="date" class="box" required placeholder="son kullanma tarihini girin" name="skt">
      <input type="text" class="box" required placeholder="barkod numarasını girin" name="barcode">
      <textarea name="side_effect" class="box" required placeholder="yan etkileri girin" cols="30" rows="10"></textarea>
      <textarea name="uses" class="box" required placeholder="kullanım alanlarını girin" cols="30" rows="10"></textarea>
      <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
      <input type="submit" value="Ürün Ekle" name="add_product" class="btn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <div class="price"><?php echo $fetch_products['price']; ?> TL</div>
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="composition"><?php echo $fetch_products['composition']; ?></div>
         <div class="skt">Son Kullanma Tarihi: <?php echo $fetch_products['skt']; ?></div>
         <div class="barcode">Barkod: <?php echo $fetch_products['barcode']; ?></div>
         <div class="side_effect">Yan Etkiler: <?php echo $fetch_products['side_effect']; ?></div>
         <div class="uses">Kullanım Alanları: <?php echo $fetch_products['uses']; ?></div>
         <a href="üretici_update_product.php?update=<?php echo $fetch_products['ilaç_id']; ?>" class="option-btn">Güncelle</a>
         <a href="üretici_products.php?delete=<?php echo $fetch_products['ilaç_id']; ?>" class="delete-btn" onclick="return confirm('Ürün silinsin mi?');">Sil</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Henüz ürün eklenmedi!</p>';
      }
      ?>
   </div>
   

</section>












<script src="js/admin_script.js"></script>

</body>
</html>