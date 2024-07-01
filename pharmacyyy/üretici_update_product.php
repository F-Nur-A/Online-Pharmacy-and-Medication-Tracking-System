<?php

@include 'config.php';

session_start();

$üretici_id = $_SESSION['üretici_id'];

if(!isset($üretici_id)){
   header('location:login.php');
};

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $composition = mysqli_real_escape_string($conn, $_POST['composition']);
   $skt = mysqli_real_escape_string($conn, $_POST['skt']);
   $barcode = mysqli_real_escape_string($conn, $_POST['barcode']);
   $side_effect = mysqli_real_escape_string($conn, $_POST['side_effect']);
   $uses = mysqli_real_escape_string($conn, $_POST['uses']);

   mysqli_query($conn, "UPDATE `products` SET name = '$name', price = '$price', composition = '$composition', skt = '$skt', barcode = '$barcode', side_effect = '$side_effect', uses = '$uses' WHERE ilaç_id = '$update_p_id'") or die('query failed');

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;
   $old_image = $_POST['update_p_image'];
   
   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Görüntü dosyası boyutu çok büyük!';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folter);
         unlink('uploaded_img/'.$old_image);
         $message[] = 'Görüntü başarıyla güncellendi!';
      }
   }

   $message[] = 'Ürün başarıyla güncellendi!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'üretici_header.php'; ?>

<section class="update-product">

<?php

   $update_id = $_GET['update'];
   $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE ilaç_id = '$update_id'") or die('query failed');
   if(mysqli_num_rows($select_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($select_products)){
?>

<form action="" method="post" enctype="multipart/form-data">
   <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="image"  alt="">
   <input type="hidden" value="<?php echo $fetch_products['ilaç_id']; ?>" name="update_p_id">
   <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">
   <input type="text" class="box" value="<?php echo $fetch_products['name']; ?>" required placeholder="ürün ismini güncelle" name="name">
   <input type="number" min="0" class="box" value="<?php echo $fetch_products['price']; ?>" required placeholder="ürün fiyatını güncelle" name="price">
   <textarea name="composition" class="box" required placeholder="ürün bileşenlerini güncelle" cols="30" rows="10"><?php echo $fetch_products['composition']; ?></textarea>
   <input type="text" class="box" value="<?php echo $fetch_products['skt']; ?>" required placeholder="son kullanma tarihini güncelle" name="skt">
   <input type="text" class="box" value="<?php echo $fetch_products['barcode']; ?>" required placeholder="ürün barkodunu güncelle" name="barcode">
   <input type="text" class="box" value="<?php echo $fetch_products['side_effect']; ?>" required placeholder="ürünün yan etkilerini güncelle" name="side_effect">
   <input type="text" class="box" value="<?php echo $fetch_products['uses']; ?>" required placeholder="ürününü kullanım alanlarını güncelle" name="uses">
   <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
   <input type="submit" value="Ürünü Güncelle" name="update_product" class="btn">
   <a href="üretici_products.php" class="option-btn">Geri Dön</a>
</form>

<?php
      }
   }else{
      echo '<p class="empty">Ürün güncellenemedi, lütfen güncellemek istediğiniz ürünü seçin.</p>';
   }
?>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>